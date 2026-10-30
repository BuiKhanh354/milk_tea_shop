<?php
require_once __DIR__ . '/../config/database.php';

class Payment {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Lấy danh sách giao dịch thanh toán phân trang có bộ lọc
     */
    public function getPaginated($page = 1, $limit = 10, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $conditions = [];
        $params = [];
        $types = "";

        if (!empty($filters['search'])) {
            $conditions[] = "(p.id LIKE ? OR p.order_id LIKE ? OR p.transaction_code LIKE ? OR c.full_name LIKE ? OR u.full_name LIKE ?)";
            $cleanSearch = ltrim($filters['search'], '#ORD0');
            $searchWildcard = "%" . ($cleanSearch !== '' ? $cleanSearch : $filters['search']) . "%";
            $rawSearchWildcard = "%" . $filters['search'] . "%";

            $params[] = $searchWildcard;
            $params[] = $searchWildcard;
            $params[] = $rawSearchWildcard;
            $params[] = $rawSearchWildcard;
            $params[] = $rawSearchWildcard;
            $types .= "sssss";
        }

        if (!empty($filters['method'])) {
            $conditions[] = "LOWER(p.method) = LOWER(?)";
            $params[] = $filters['method'];
            $types .= "s";
        }

        if (!empty($filters['status'])) {
            $conditions[] = "p.status = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }

        if (!empty($filters['start_date'])) {
            $conditions[] = "DATE(p.created_at) >= ?";
            $params[] = $filters['start_date'];
            $types .= "s";
        }

        if (!empty($filters['end_date'])) {
            $conditions[] = "DATE(p.created_at) <= ?";
            $params[] = $filters['end_date'];
            $types .= "s";
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        // Đếm tổng số bản ghi
        $countSql = "SELECT COUNT(*) as total FROM payments p 
                     LEFT JOIN orders o ON p.order_id = o.id
                     LEFT JOIN customers c ON o.customer_id = c.id
                     LEFT JOIN users u ON o.user_id = u.id 
                     $whereClause";

        if (!empty($params)) {
            $stmtCount = $this->conn->prepare($countSql);
            $stmtCount->bind_param($types, ...$params);
            $stmtCount->execute();
            $total = $stmtCount->get_result()->fetch_assoc()['total'] ?? 0;
        } else {
            $total = $this->conn->query($countSql)->fetch_assoc()['total'] ?? 0;
        }

        // Truy vấn dữ liệu phân trang
        $sql = "SELECT p.*, 
                       o.order_type,
                       o.status as order_status,
                       o.final_amount as order_final_amount,
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(c.phone, u.phone, '') as customer_phone
                FROM payments p
                LEFT JOIN orders o ON p.order_id = o.id
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                $whereClause
                ORDER BY p.id DESC
                LIMIT ? OFFSET ?";

        $pTypes = $types . "ii";
        $pParams = array_merge($params, [$limit, $offset]);

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($pTypes, ...$pParams);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $totalPages = ceil($total / $limit);

        return [
            'data' => $data,
            'total' => $total,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }

    /**
     * Lấy các chỉ số thống kê thanh toán tổng thể
     */
    public function getStats($filters = []) {
        $conditions = [];
        $params = [];
        $types = "";

        if (!empty($filters['start_date'])) {
            $conditions[] = "DATE(p.created_at) >= ?";
            $params[] = $filters['start_date'];
            $types .= "s";
        }

        if (!empty($filters['end_date'])) {
            $conditions[] = "DATE(p.created_at) <= ?";
            $params[] = $filters['end_date'];
            $types .= "s";
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT 
                    COUNT(p.id) as total_transactions,
                    COALESCE(SUM(CASE WHEN p.status = 'paid' THEN p.amount ELSE 0 END), 0) as total_paid_amount,
                    COALESCE(SUM(CASE WHEN p.status = 'paid' AND LOWER(p.method) IN ('cash', 'tiền mặt') THEN p.amount ELSE 0 END), 0) as cash_paid_amount,
                    COALESCE(SUM(CASE WHEN p.status = 'paid' AND LOWER(p.method) NOT IN ('cash', 'tiền mặt') THEN p.amount ELSE 0 END), 0) as online_paid_amount,
                    COALESCE(SUM(CASE WHEN p.status = 'pending' THEN 1 ELSE 0 END), 0) as pending_count,
                    COALESCE(SUM(CASE WHEN p.status = 'pending' THEN p.amount ELSE 0 END), 0) as pending_amount,
                    COALESCE(SUM(CASE WHEN p.status = 'failed' THEN 1 ELSE 0 END), 0) as failed_count
                FROM payments p
                $whereClause";

        if (!empty($params)) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        return $this->conn->query($sql)->fetch_assoc();
    }

    /**
     * Cập nhật trạng thái thanh toán & mã giao dịch
     */
    public function updateStatus($id, $status, $transactionCode = null) {
        $validStatuses = ['pending', 'paid', 'failed'];
        if (!in_array($status, $validStatuses)) return false;

        if ($status === 'paid') {
            $sql = "UPDATE payments SET status = ?, paid_at = NOW(), transaction_code = COALESCE(?, transaction_code) WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $transactionCode, $id);
        } else {
            $sql = "UPDATE payments SET status = ?, transaction_code = COALESCE(?, transaction_code) WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $transactionCode, $id);
        }

        return $stmt->execute();
    }

    /**
     * Lấy chi tiết 1 thanh toán theo ID
     */
    public function getById($id) {
        $sql = "SELECT p.*, o.order_type, o.status as order_status, o.final_amount as order_final_amount,
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name
                FROM payments p
                LEFT JOIN orders o ON p.order_id = o.id
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                WHERE p.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
