<?php
require_once __DIR__ . '/../config/database.php';

class InventoryTransaction {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function create($inventory_id, $user_id, $type, $quantity, $note = '', $price = 0) {
        $sql = "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iisdds", $inventory_id, $user_id, $type, $quantity, $price, $note);
            return $stmt->execute();
        }
        return false;
    }

    public function getAll() {
        $sql = "SELECT t.*, i.ingredient_name, i.unit, u.username as user_name 
                FROM inventory_transactions t
                JOIN inventory i ON t.inventory_id = i.id
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
