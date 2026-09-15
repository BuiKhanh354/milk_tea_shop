<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT o.*, 
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(p.method, 'Tiền mặt') as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                ORDER BY o.created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecent($limit = 5) {
        $sql = "SELECT o.*, 
                       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                       COALESCE(p.method, 'Tiền mặt') as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                ORDER BY o.created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStats() {
        $stats = [
            'total_revenue' => 0,
            'today_orders' => 0,
            'revenue_growth' => '+0%',
            'order_growth' => '+0%'
        ];

        // Lấy doanh thu ngày hôm nay (chỉ tính đơn hoàn thành)
        $sqlRev = "SELECT SUM(final_amount) as total FROM orders WHERE status = 'completed' AND DATE(created_at) = CURDATE()";
        $resRev = $this->conn->query($sqlRev);
        if ($resRev) {
            $row = $resRev->fetch_assoc();
            $stats['total_revenue'] = $row['total'] ? (float)$row['total'] : 0;
        }

        // Đếm đơn hôm nay (tất cả trạng thái)
        $sqlOrd = "SELECT COUNT(id) as total FROM orders WHERE DATE(created_at) = CURDATE()";
        $resOrd = $this->conn->query($sqlOrd);
        if ($resOrd) {
            $row = $resOrd->fetch_assoc();
            $stats['today_orders'] = $row['total'] ? (int)$row['total'] : 0;
        }

        return $stats;
    }

    public function findByIdWithDetails($id) {
        $sql = "SELECT o.*, 
                       c.full_name as customer_name, c.phone as customer_phone, c.address as customer_address,
                       u.full_name as user_name, u.phone as user_phone,
                       p.method as payment_method
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN payments p ON o.id = p.order_id
                WHERE o.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        if (!$order) return null;

        // Fetch details
        $sqlDetails = "SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
                       FROM order_details od
                       JOIN products p ON od.product_id = p.id
                       LEFT JOIN sizes s ON od.size_id = s.id
                       WHERE od.order_id = ?";
        $stmtD = $this->conn->prepare($sqlDetails);
        $stmtD->bind_param("i", $id);
        $stmtD->execute();
        $order['items'] = $stmtD->get_result()->fetch_all(MYSQLI_ASSOC);

        return $order;
    }

    public function updateStatus($id, $status) {
        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) return false;

        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
