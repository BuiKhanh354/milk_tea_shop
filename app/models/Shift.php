<?php
require_once __DIR__ . '/../config/database.php';

class Shift {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // --- QUẢN LÝ DANH MỤC CA LÀM VIỆC (shifts table) ---
    public function getAllShifts() {
        $sql = "SELECT * FROM shifts ORDER BY start_time ASC";
        $result = $this->conn->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createShift($name, $startTime, $endTime) {
        $sql = "INSERT INTO shifts (name, start_time, end_time) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $startTime, $endTime);
        return $stmt->execute();
    }

    public function updateShift($id, $name, $startTime, $endTime, $status) {
        $sql = "UPDATE shifts SET name = ?, start_time = ?, end_time = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $name, $startTime, $endTime, $status, $id);
        return $stmt->execute();
    }

    public function deleteShift($id) {
        $sql = "DELETE FROM shifts WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // --- QUẢN LÝ PHÂN CÔNG (employee_shifts table) ---
    public function getEmployeeShifts($startDate = null, $endDate = null) {
        $sql = "SELECT es.*, u.full_name as employee_name, s.name as shift_name, s.start_time, s.end_time 
                FROM employee_shifts es
                JOIN users u ON es.employee_id = u.id
                JOIN shifts s ON es.shift_id = s.id";
        
        $params = [];
        $types = "";

        if ($startDate && $endDate) {
            $sql .= " WHERE es.work_date BETWEEN ? AND ?";
            $types .= "ss";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $sql .= " ORDER BY es.work_date DESC, s.start_time ASC";

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStaffShifts($userId) {
        $sql = "SELECT es.*, s.name as shift_name, s.start_time, s.end_time 
                FROM employee_shifts es
                JOIN shifts s ON es.shift_id = s.id
                WHERE es.employee_id = ?
                ORDER BY es.work_date DESC, s.start_time ASC LIMIT 30";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function assignEmployee($employeeId, $shiftId, $date) {
        // Kiểm tra xem đã phân công chưa
        $sqlCheck = "SELECT id FROM employee_shifts WHERE employee_id = ? AND shift_id = ? AND work_date = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bind_param("iis", $employeeId, $shiftId, $date);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            return false; // Đã tồn tại
        }

        $sql = "INSERT INTO employee_shifts (employee_id, shift_id, work_date, status) VALUES (?, ?, ?, 'assigned')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $employeeId, $shiftId, $date);
        return $stmt->execute();
    }

    public function updateAssignmentStatus($id, $status) {
        $validStatuses = ['assigned', 'completed', 'absent', 'cancelled'];
        if (!in_array($status, $validStatuses)) return false;

        $sql = "UPDATE employee_shifts SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
