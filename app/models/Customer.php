<?php
require_once __DIR__ . '/../config/database.php';

class Customer {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll() {
        // Lấy danh sách khách hàng và thống kê tổng đơn, tổng chi tiêu
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

    public function toggleStatus($id) {
        $sql = "UPDATE customers SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }
}
