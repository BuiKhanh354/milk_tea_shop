<?php
require_once __DIR__ . '/../config/database.php';

class Inventory {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT * FROM inventory ORDER BY ingredient_name ASC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStats() {
        $stats = [
            'total' => 0,
            'in_stock' => 0,
            'low_stock' => 0,
            'out_of_stock' => 0
        ];

        $sql = "SELECT quantity, min_quantity FROM inventory";
        $result = $this->conn->query($sql);
        
        if ($result) {
            $items = $result->fetch_all(MYSQLI_ASSOC);
            $stats['total'] = count($items);
            
            foreach ($items as $item) {
                if ($item['quantity'] <= 0) {
                    $stats['out_of_stock']++;
                } elseif ($item['quantity'] <= $item['min_quantity']) {
                    $stats['low_stock']++;
                } else {
                    $stats['in_stock']++;
                }
            }
        }
        
        return $stats;
    }

    public function findById($id) {
        $sql = "SELECT * FROM inventory WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        return null;
    }

    public function create($name, $unit, $min_quantity, $price, $note = '') {
        // Initial quantity is 0, status can be 'active' by default
        $sql = "INSERT INTO inventory (ingredient_name, unit, quantity, min_quantity, price) VALUES (?, ?, 0, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssdd", $name, $unit, $min_quantity, $price);
            return $stmt->execute();
        }
        return false;
    }

    public function update($id, $name, $unit, $min_quantity, $price) {
        // Note: quantity is NOT updated here!
        $sql = "UPDATE inventory SET ingredient_name = ?, unit = ?, min_quantity = ?, price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssddi", $name, $unit, $min_quantity, $price, $id);
            return $stmt->execute();
        }
        return false;
    }

    public function updateQuantity($id, $quantity) {
        $sql = "UPDATE inventory SET quantity = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("di", $quantity, $id);
            return $stmt->execute();
        }
        return false;
    }

    public function delete($id) {
        // Soft delete could be implemented, but for now we'll do physical delete if not used in product_ingredients
        // Check usage in product_ingredients
        $sql_check = "SELECT COUNT(*) as count FROM product_ingredients WHERE inventory_id = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $result = $stmt_check->get_result()->fetch_assoc();
            if ($result['count'] > 0) {
                return false; // In use
            }
        }
        
        $sql = "DELETE FROM inventory WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }
}
