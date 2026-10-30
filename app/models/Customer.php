<?php
require_once __DIR__ . '/../config/database.php';

class Customer {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getPaginated($page = 1, $limit = 10, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $conditions = [];
        $params = [];
        $types = "";
        
        if (!empty($filters['search'])) {
            $conditions[] = "(c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%"; 
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "c.status = ?";
            $params[] = (int)$filters['status'];
            $types .= "i";
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $countSql = "SELECT COUNT(*) as total FROM customers c $whereClause";
                     
        if (!empty($params)) {
            $cStmt = $this->conn->prepare($countSql);
            $cStmt->bind_param($types, ...$params);
            $cStmt->execute();
            $total = $cStmt->get_result()->fetch_assoc()['total'] ?? 0;
        } else {
            $total = $this->conn->query($countSql)->fetch_assoc()['total'] ?? 0;
        }
        
        $sql = "SELECT c.*, 
                       COUNT(o.id) as total_orders, 
                       SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END) as total_spent
                FROM customers c
                LEFT JOIN orders o ON c.id = o.customer_id
                $whereClause
                GROUP BY c.id
                ORDER BY c.created_at DESC
                LIMIT ? OFFSET ?";
                
        $stmt = $this->conn->prepare($sql);
        $allParams = array_merge($params, [$limit, $offset]);
        $allTypes = $types . "ii";
        $stmt->bind_param($allTypes, ...$allParams);
        $stmt->execute();
        $customers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return [
            'data' => $customers,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    public function getAll() {
        $sql = "SELECT c.*, 
                       COUNT(o.id) as total_orders, 
                       SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END) as total_spent
                FROM customers c
                LEFT JOIN orders o ON c.id = o.customer_id
                GROUP BY c.id
                ORDER BY c.created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM customers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                return $res->fetch_assoc();
            }
        }
        return null;
    }

    public function toggleStatus($id) {
        $sql = "UPDATE customers SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }

    public function createGuestCustomer($name, $phone, $address) {
        $sql = "SELECT id FROM customers WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['id'];
        }

        $sqlInsert = "INSERT INTO customers (full_name, phone, address, point, tier, status) VALUES (?, ?, ?, 0, 'Thành viên', 1)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sss", $name, $phone, $address);
        if ($stmtInsert->execute()) {
            return $stmtInsert->insert_id;
        }
        return null;
    }

    /**
     * Tìm khách hàng theo email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM customers WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Đăng ký tài khoản khách hàng mới
     */
    public function register($name, $email, $plainPassword) {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $sql = "INSERT INTO customers (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
        }
        return false;
    }

    /**
     * Cập nhật thông tin cá nhân khách hàng
     */
    public function updateProfile($id, $name, $phone, $address) {
        $sql = "UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssi", $name, $phone, $address, $id);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Xác thực mật khẩu hiện tại của khách hàng
     */
    public function verifyPassword($id, $plainPassword) {
        $customer = $this->getById($id);
        if ($customer && !empty($customer['password'])) {
            return password_verify($plainPassword, $customer['password']);
        }
        return false;
    }

    /**
     * Cập nhật mật khẩu mới cho khách hàng
     */
    public function updatePassword($id, $plainPassword) {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE customers SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $hashedPassword, $id);
            return $stmt->execute();
        }
        return false;
    }
}
