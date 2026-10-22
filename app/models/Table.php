<?php
require_once __DIR__ . '/../config/database.php';

class TableModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM tables ORDER BY table_number ASC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function findById($id)
    {
        $sql = "SELECT * FROM tables WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($data)
    {
        $sql = "INSERT INTO tables (table_number, capacity, status)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sis",
            $data['table_number'],
            $data['capacity'],
            $data['status']
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE tables SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function existsByTableNumber($tableNumber)
    {
        $sql = "SELECT id FROM tables WHERE table_number = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("s", $tableNumber);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }
}
