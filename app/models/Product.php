<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function findById($id) {
        // Fetch product and category
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ? AND p.status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if (!$product) return null;

        // Fetch rating and review count
        $ratingSql = "SELECT AVG(rating) as rating, COUNT(*) as review_count FROM reviews WHERE product_id = ? AND status = 1";
        $rStmt = $this->conn->prepare($ratingSql);
        $rStmt->bind_param("i", $id);
        $rStmt->execute();
        $rRes = $rStmt->get_result()->fetch_assoc();
        
        $product['rating'] = $rRes['rating'] ? (float)$rRes['rating'] : 5.0; // Default to 5.0 if no reviews
        $product['review_count'] = $rRes['review_count'] ? (int)$rRes['review_count'] : 0;

        // Generic mock data for fields that don't exist in the current database schema
        if (empty($product['story'])) {
            $product['story'] = 'Mỗi thức uống là một câu chuyện được VAA THÉ ấp ủ, tuyển chọn từ những nguyên liệu tươi ngon nhất để mang đến trải nghiệm tuyệt vời.';
        }
        if (empty($product['ingredients'])) {
            $product['ingredients'] = 'Nguyên liệu tự nhiên, an toàn cho sức khỏe.';
        }

        return $product;
    }

    public function getAll() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 1
                ORDER BY p.id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSizes() {
        $sql = "SELECT * FROM sizes WHERE status = 1 ORDER BY extra_price ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getToppings() {
        $sql = "SELECT * FROM toppings WHERE status = 1";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRelatedProducts($categoryId, $excludeId) {
        $sql = "SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 1 LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $excludeId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getReviews($id) {
        $sql = "SELECT r.rating, r.comment, DATE_FORMAT(r.created_at, '%d/%m/%Y') as date, c.full_name as name 
                FROM reviews r 
                JOIN customers c ON r.customer_id = c.id 
                WHERE r.product_id = ? AND r.status = 1 
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO products (name, category_id, description, price, image, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisdsi", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['image'], $data['status']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        if (!empty($data['image'])) {
            $sql = "UPDATE products SET name=?, category_id=?, description=?, price=?, image=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sisdsii", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['image'], $data['status'], $id);
        } else {
            $sql = "UPDATE products SET name=?, category_id=?, description=?, price=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sisdii", $data['name'], $data['category_id'], $data['description'], $data['price'], $data['status'], $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM products WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $sql = "UPDATE products SET status = IF(status=1, 0, 1) WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
