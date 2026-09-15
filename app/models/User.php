<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function countCustomers() {
        $sql = "SELECT COUNT(id) as total FROM customers";
        $result = $this->conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return (int)$row['total'];
        }
        return 0;
    }

    public function getAllStaff() {
        $sql = "SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createStaff($username, $password, $fullName, $email, $phone) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, full_name, email, phone, role, status) VALUES (?, ?, ?, ?, ?, 'staff', 1)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $username, $hashed, $fullName, $email, $phone);
            return $stmt->execute();
        }
        return false;
    }

    public function toggleStatus($id) {
        $sql = "UPDATE users SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ? AND role = 'staff'";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }

    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ? AND status = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
