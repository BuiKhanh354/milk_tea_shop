<?php
/**
 * ShiftSession — Quản lý phiên ca làm việc & kiểm kho đầu/cuối ca (bảng shift_sessions, shift_session_inventory).
 */
class ShiftSession extends BaseModel
{
    protected string $table = 'shift_sessions';

    public function getOpenSession(): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT s.*, sh.name as shift_name
             FROM shift_sessions s
             LEFT JOIN shifts sh ON s.shift_id = sh.id
             WHERE s.status = 'open'
             ORDER BY s.opened_at DESC
             LIMIT 1"
        );
        return $row ?: null;
    }

    public function openSession(int $userId, int $shiftId, float $initialCash, string $note = '', array $inventoryInput = []): int
    {
        $employeeShiftId = null;
        if ($shiftId > 0) {
            $today = date('Y-m-d');
            $validAssignment = $this->db->fetchOne(
                "SELECT id FROM employee_shifts WHERE employee_id = ? AND shift_id = ? AND work_date = ? LIMIT 1",
                [$userId, $shiftId, $today]
            );
            if ($validAssignment) {
                $employeeShiftId = (int)$validAssignment['id'];
            }
        }

        $sessionCode = 'CA-' . date('Ymd-His');
        $this->db->execute(
            "INSERT INTO shift_sessions (session_code, user_id, shift_id, employee_shift_id, opened_at, initial_cash, expected_cash, status, note)
             VALUES (?, ?, ?, ?, NOW(), ?, ?, 'open', ?)",
            [$sessionCode, $userId, $shiftId ?: null, $employeeShiftId, $initialCash, $initialCash, $note]
        );
        $sessionId = (int)$this->db->insertId();

        if (!empty($inventoryInput)) {
            foreach ($inventoryInput as $invId => $qty) {
                $startQty = (float)$qty;
                $this->db->execute(
                    "INSERT INTO shift_session_inventory (session_id, inventory_id, start_quantity, expected_ending_qty) VALUES (?, ?, ?, ?)",
                    [$sessionId, (int)$invId, $startQty, $startQty]
                );
            }
        } else {
            $allInv = $this->db->fetchAll("SELECT id, quantity FROM inventory");
            foreach ($allInv as $item) {
                $startQty = (float)$item['quantity'];
                $this->db->execute(
                    "INSERT INTO shift_session_inventory (session_id, inventory_id, start_quantity, expected_ending_qty) VALUES (?, ?, ?, ?)",
                    [$sessionId, (int)$item['id'], $startQty, $startQty]
                );
            }
        }

        if ($employeeShiftId) {
            $this->db->execute("UPDATE employee_shifts SET status = 'in_progress' WHERE id = ?", [$employeeShiftId]);
        }

        return $sessionId;
    }

    public function getDetail(int $sessionId): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT s.*, u.full_name as staff_name, sh.name as shift_name, sh.start_time, sh.end_time
             FROM shift_sessions s
             LEFT JOIN users u ON s.user_id = u.id
             LEFT JOIN shifts sh ON s.shift_id = sh.id
             WHERE s.id = ?",
            [$sessionId]
        );
        return $row ?: null;
    }

    public function getSessionInventory(int $sessionId): array
    {
        return $this->db->fetchAll(
            "SELECT ssi.*, i.ingredient_name, i.unit
             FROM shift_session_inventory ssi
             JOIN inventory i ON ssi.inventory_id = i.id
             WHERE ssi.session_id = ?
             ORDER BY i.ingredient_name ASC",
            [$sessionId]
        );
    }

    public function getAllWithStaff(?int $userId = null): array
    {
        $where = $userId ? "WHERE s.user_id = ?" : "";
        $params = $userId ? [$userId] : [];
        return $this->db->fetchAll(
            "SELECT s.*, u.full_name as staff_name, sh.name as shift_name
             FROM shift_sessions s
             LEFT JOIN users u ON s.user_id = u.id
             LEFT JOIN shifts sh ON s.shift_id = sh.id
             $where
             ORDER BY s.opened_at DESC",
            $params
        );
    }
}
