<?php
require_once __DIR__ . '/../config/database.php';

class ItemOption {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getSugarOptions($includeHidden = false) {
        $statusCondition = $includeHidden ? "" : "AND status = 1";
        $sql = "SELECT * FROM item_options WHERE type = 'sugar' $statusCondition ORDER BY value ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIceOptions($includeHidden = false) {
        $statusCondition = $includeHidden ? "" : "AND status = 1";
        $sql = "SELECT * FROM item_options WHERE type = 'ice' $statusCondition ORDER BY value ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO item_options (type, name, value, is_default, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiii", $data['type'], $data['name'], $data['value'], $data['is_default'], $data['status']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $sql = "UPDATE item_options SET name=?, value=?, is_default=?, status=? WHERE id=? AND type=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siiiss", $data['name'], $data['value'], $data['is_default'], $data['status'], $id, $data['type']);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM item_options WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
