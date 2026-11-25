<?php
/**
 * InventoryAudit — Quản lý phiếu kiểm kê kho (bảng inventory_audits, inventory_audit_details).
 */
class InventoryAudit extends BaseModel
{
    protected string $table = 'inventory_audits';

    public function createAudit(int $userId, string $note, array $detailRows, bool $syncInventory = false): array
    {
        $auditCode = 'KK-' . date('Ymd-His');
        $totalItems = count($detailRows);
        $totalDiffItems = 0;
        foreach ($detailRows as $r) {
            if (abs((float)$r['difference_quantity']) > 0.001) {
                $totalDiffItems++;
            }
        }

        $this->db->execute(
            "INSERT INTO inventory_audits (audit_code, user_id, total_items, total_difference_items, status, note, created_at)
             VALUES (?, ?, ?, ?, 'completed', ?, NOW())",
            [$auditCode, $userId, $totalItems, $totalDiffItems, $note !== '' ? $note : null]
        );
        $auditId = (int)$this->db->insertId();

        foreach ($detailRows as $row) {
            $this->db->execute(
                "INSERT INTO inventory_audit_details (audit_id, inventory_id, system_quantity, actual_quantity, difference_quantity, reason, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [
                    $auditId,
                    $row['inventory_id'],
                    $row['system_quantity'],
                    $row['actual_quantity'],
                    $row['difference_quantity'],
                    $row['reason'],
                ]
            );

            if ($syncInventory && abs((float)$row['difference_quantity']) > 0.001) {
                $this->db->execute(
                    "UPDATE inventory SET quantity = ? WHERE id = ?",
                    [$row['actual_quantity'], $row['inventory_id']]
                );
                $transNote = "Điều chỉnh theo phiếu kiểm kê {$auditCode}" . ($row['reason'] ? " ({$row['reason']})" : '');
                $this->db->execute(
                    "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note)
                     VALUES (?, ?, 'ADJUSTMENT', ?, ?, ?)",
                    [$row['inventory_id'], $userId, $row['difference_quantity'], $row['price'], $transNote]
                );
            }
        }

        return ['id' => $auditId, 'audit_code' => $auditCode];
    }

    public function getByIdWithUser(int $id): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT a.*, COALESCE(u.full_name, 'Hệ thống') as user_name
             FROM inventory_audits a
             LEFT JOIN users u ON a.user_id = u.id
             WHERE a.id = ?",
            [$id]
        );
        return $row ?: null;
    }

    public function getAuditDetails(int $auditId): array
    {
        return $this->db->fetchAll(
            "SELECT d.*, i.ingredient_name, i.unit
             FROM inventory_audit_details d
             JOIN inventory i ON d.inventory_id = i.id
             WHERE d.audit_id = ?
             ORDER BY ABS(d.difference_quantity) DESC, i.ingredient_name ASC",
            [$auditId]
        );
    }

    public function getAllWithUser(): array
    {
        return $this->db->fetchAll(
            "SELECT a.*, COALESCE(u.full_name, 'Hệ thống') as user_name
             FROM inventory_audits a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.created_at DESC"
        );
    }
}
