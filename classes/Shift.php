<?php
/**
 * Shift — Quản lý ca làm việc & phân công ca (bảng shifts, employee_shifts).
 */
class Shift extends BaseModel
{
    protected string $table = 'shifts';

    public function store(string $name, string $startTime, string $endTime): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO shifts (name, start_time, end_time, status) VALUES (?, ?, ?, 1)",
            [$name, $startTime, $endTime]
        );
    }

    public function hasAssignment(int $employeeId, int $shiftId, string $workDate): bool
    {
        $row = $this->db->fetchOne(
            "SELECT id FROM employee_shifts WHERE employee_id = ? AND shift_id = ? AND work_date = ?",
            [$employeeId, $shiftId, $workDate]
        );
        return !empty($row);
    }

    public function assignEmployee(int $employeeId, int $shiftId, string $workDate): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO employee_shifts (employee_id, shift_id, work_date, status) VALUES (?, ?, ?, 'assigned')",
            [$employeeId, $shiftId, $workDate]
        );
    }

    public function updateAssignmentStatus(int $assignmentId, string $status): bool
    {
        if (!in_array($status, ['assigned', 'completed', 'absent', 'cancelled'], true)) {
            return false;
        }
        return (bool)$this->db->execute(
            "UPDATE employee_shifts SET status = ? WHERE id = ?",
            [$status, $assignmentId]
        );
    }

    public function deleteShift(int $id): bool
    {
        $check = $this->db->fetchOne("SELECT COUNT(*) as c FROM employee_shifts WHERE shift_id = ?", [$id]);
        if ($check && (int)$check['c'] > 0) {
            return false;
        }
        return $this->deleteById($id);
    }

    public function getAssignmentsByDateRange(string $startDate, string $endDate): array
    {
        return $this->db->fetchAll(
            "SELECT es.*, u.full_name as employee_name, s.name as shift_name, s.start_time, s.end_time
             FROM employee_shifts es
             JOIN users u ON es.employee_id = u.id
             JOIN shifts s ON es.shift_id = s.id
             WHERE es.work_date BETWEEN ? AND ?
             ORDER BY es.work_date DESC, s.start_time ASC",
            [$startDate, $endDate]
        );
    }

    public function getEmployeeSchedule(int $employeeId, int $limit = 50): array
    {
        return $this->db->fetchAll(
            "SELECT es.*, s.name as shift_name, s.start_time, s.end_time
             FROM employee_shifts es
             JOIN shifts s ON es.shift_id = s.id
             WHERE es.employee_id = ?
             ORDER BY es.work_date DESC, s.start_time ASC LIMIT ?",
            [$employeeId, $limit]
        );
    }
}
