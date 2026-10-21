<?php
require_once __DIR__ . '/../config/database.php';

class Favorite {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function isFavorite($customerId, $productId) {
        $sql = "SELECT * FROM favorites WHERE customer_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $customerId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function addFavorite($customerId, $productId) {
        if ($this->isFavorite($customerId, $productId)) return true;
        
        $sql = "INSERT INTO favorites (customer_id, product_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $customerId, $productId);
        return $stmt->execute();
    }

    public function removeFavorite($customerId, $productId) {
        $sql = "DELETE FROM favorites WHERE customer_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $customerId, $productId);
        return $stmt->execute();
    }

    public function toggleFavorite($customerId, $productId) {
        if ($this->isFavorite($customerId, $productId)) {
            $this->removeFavorite($customerId, $productId);
            return false; // Result is now not-favorite
        } else {
            $this->addFavorite($customerId, $productId);
            return true; // Result is now favorite
        }
    }

    public function getCustomerFavorites($customerId) {
        $sql = "SELECT p.*, c.name as category_name
                FROM favorites f 
                JOIN products p ON f.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE f.customer_id = ?
                ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) return [];
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as &$row) {
            $image = $row['image'] ?? '';
            if (empty($image)) {
                $row['image'] = 'https://static.vecteezy.com/system/resources/previews/011/946/312/original/illustration-of-browser-error-icon-file-not-found-vector.jpg';
            } elseif (strpos($image, 'http') !== 0) {
                $row['image'] = 'assets/images/products/' . $image;
            }
        }
        return $rows;
    }
}

