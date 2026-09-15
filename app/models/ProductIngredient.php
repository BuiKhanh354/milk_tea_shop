<?php
require_once __DIR__ . '/../config/database.php';

class ProductIngredient {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getByProductId($product_id) {
        $sql = "SELECT pi.*, i.ingredient_name, i.unit 
                FROM product_ingredients pi
                JOIN inventory i ON pi.inventory_id = i.id
                WHERE pi.product_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function setIngredients($product_id, $ingredients) {
        // $ingredients is array of ['inventory_id' => x, 'quantity' => y]
        $this->conn->begin_transaction();
        try {
            // Delete old
            $sql_del = "DELETE FROM product_ingredients WHERE product_id = ?";
            $stmt_del = $this->conn->prepare($sql_del);
            $stmt_del->bind_param("i", $product_id);
            $stmt_del->execute();

            // Insert new
            if (!empty($ingredients)) {
                $sql_in = "INSERT INTO product_ingredients (product_id, inventory_id, quantity) VALUES (?, ?, ?)";
                $stmt_in = $this->conn->prepare($sql_in);
                foreach ($ingredients as $ing) {
                    if ($ing['quantity'] > 0) {
                        $stmt_in->bind_param("iid", $product_id, $ing['inventory_id'], $ing['quantity']);
                        $stmt_in->execute();
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
