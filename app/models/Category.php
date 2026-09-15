<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT c.*, COUNT(p.id) as products_count 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.id ASC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($name, $description) {
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $name, $description);
            return $stmt->execute();
        }
        return false;
    }

    public function update($id, $name, $description) {
        $sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssi", $name, $description, $id);
            return $stmt->execute();
        }
        return false;
    }

    public function delete($id) {
        // Kiểm tra xem danh mục có sản phẩm nào không
        $sql_check = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $result = $stmt_check->get_result()->fetch_assoc();
            if ($result['count'] > 0) {
                return false; // Không cho phép xóa nếu đang có sản phẩm
            }
        }

        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }
}
