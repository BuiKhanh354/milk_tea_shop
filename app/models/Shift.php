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

    /**
     * Lấy danh sách ca được phân công cho nhân viên trong ngày hôm nay (không lấy ca đã qua ngày)
     */
    public function getAssignedShiftsForStaffToday($userId, $date = null) {
        $workDate = $date ?: date('Y-m-d');
        $sql = "SELECT es.*, s.name as shift_name, s.start_time, s.end_time 
                FROM employee_shifts es
                JOIN shifts s ON es.shift_id = s.id
                WHERE es.employee_id = ? 
                  AND es.work_date = ? 
                  AND es.status != 'cancelled'
                ORDER BY s.start_time ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $userId, $workDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Tự động xác định ca làm việc của nhân viên khớp với thời điểm hiện tại
     * Cho phép mở sớm trước giờ bắt đầu tối đa 45 phút
     */
    public function getActiveAssignedShiftForStaffNow($userId, $currentTime = null) {
        $assigned = $this->getAssignedShiftsForStaffToday($userId);
        if (empty($assigned)) {
            return null;
        }

        if (count($assigned) === 1) {
            return $assigned[0];
        }

        $now = $currentTime ?: date('H:i:s');
        $nowSec = strtotime($now);

        // Tìm ca đang trong khung giờ (cho phép mở sớm 45 phút)
        foreach ($assigned as $sh) {
            $startSec = strtotime($sh['start_time']) - (45 * 60);
            $endSec = strtotime($sh['end_time']);
            if ($nowSec >= $startSec && $nowSec <= $endSec) {
                return $sh;
            }
        }

        // Nếu ngoài khung giờ, ưu tiên ca đầu tiên chưa hoàn thành (status = 'assigned')
        foreach ($assigned as $sh) {
            if ($sh['status'] === 'assigned') {
                return $sh;
            }
        }

        return $assigned[0];
    }

    /**
     * Kiểm tra xem nhân viên có được phân công ca làm này vào ngày hôm nay hay không
     * Trả về bản ghi phân công nếu hợp lệ, false nếu ca không được giao hoặc đã qua ngày
     */
    public function getValidTodayAssignment($userId, $shiftId, $date = null) {
        $workDate = $date ?: date('Y-m-d');
        $sql = "SELECT es.*, s.name as shift_name, s.start_time, s.end_time 
                FROM employee_shifts es
                JOIN shifts s ON es.shift_id = s.id
                WHERE es.employee_id = ? 
                  AND es.shift_id = ? 
                  AND es.work_date = ? 
                  AND es.status != 'cancelled'
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $userId, $shiftId, $workDate);
        $stmt->execute();
        $res = $stmt->get_result();
        return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;
    }
}
