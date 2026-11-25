<?php
/**
 * Ingredient — Quản lý nguyên liệu tồn kho & lịch sử xuất/nhập kho (bảng inventory, inventory_transactions).
 */
class Ingredient extends BaseModel
{
    protected string $table = 'inventory';

    public function store(string $name, string $unit, float $qty, float $minQty, float $price, int $userId): int
    {
        $this->db->execute(
            "INSERT INTO inventory (ingredient_name, unit, quantity, min_quantity, price, status) VALUES (?, ?, ?, ?, ?, 1)",
            [$name, $unit, $qty, $minQty, $price]
        );
        $invId = (int)$this->db->insertId();
        if ($qty > 0 && $invId > 0) {
            $this->db->execute(
                "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'IMPORT', ?, ?, 'Khởi tạo tồn ban đầu')",
                [$invId, $userId, $qty, $price]
            );
        }
        return $invId;
    }

    public function update(int $id, string $name, string $unit, float $minQty, float $price): bool
    {
        return (bool)$this->db->execute(
            "UPDATE inventory SET ingredient_name = ?, unit = ?, min_quantity = ?, price = ? WHERE id = ?",
            [$name, $unit, $minQty, $price, $id]
        );
    }

    public function importStock(int $inventoryId, float $qty, int $userId, string $note = ''): bool
    {
        $item = $this->find($inventoryId);
        if (!$item || $qty <= 0) {
            return false;
        }
        $price = (float)($item['price'] ?? 0);
        $this->db->execute("UPDATE inventory SET quantity = quantity + ? WHERE id = ?", [$qty, $inventoryId]);
        $this->db->execute(
            "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'IMPORT', ?, ?, ?)",
            [$inventoryId, $userId, $qty, $price, $note]
        );
        return true;
    }

    public function exportStock(int $inventoryId, float $qty, int $userId, string $fullNote = ''): array
    {
        $item = $this->find($inventoryId);
        if (!$item || $qty <= 0) {
            return ['ok' => false, 'message' => 'Nguyên liệu hoặc số lượng không hợp lệ.'];
        }
        $currQty = (float)($item['quantity'] ?? 0);
        if ($qty > $currQty) {
            return ['ok' => false, 'message' => "Số lượng xuất không được vượt quá số lượng tồn ({$currQty})!"];
        }
        $price = (float)($item['price'] ?? 0);
        $this->db->execute("UPDATE inventory SET quantity = quantity - ? WHERE id = ?", [$qty, $inventoryId]);
        $this->db->execute(
            "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'EXPORT', ?, ?, ?)",
            [$inventoryId, $userId, $qty, $price, $fullNote]
        );
        return ['ok' => true];
    }

    public function getTransactions(string $type = '', string $search = '', int $limit = 100): array
    {
        $tWhere = ["1=1"];
        $tParams = [];

        if ($type !== '' && in_array($type, ['IMPORT', 'EXPORT', 'USAGE', 'ADJUSTMENT'], true)) {
            $tWhere[] = "t.type = ?";
            $tParams[] = $type;
        }
        if ($search !== '') {
            $tWhere[] = "(i.ingredient_name LIKE ? OR t.note LIKE ?)";
            $like = '%' . $search . '%';
            $tParams[] = $like;
            $tParams[] = $like;
        }

        $tClause = implode(" AND ", $tWhere);
        return $this->db->fetchAll(
            "SELECT t.*, i.ingredient_name, i.unit, COALESCE(u.full_name, 'Hệ thống') as user_name
             FROM inventory_transactions t
             JOIN inventory i ON t.inventory_id = i.id
             LEFT JOIN users u ON t.user_id = u.id
             WHERE $tClause
             ORDER BY t.created_at DESC
             LIMIT ?",
            array_merge($tParams, [$limit])
        );
    }

    public function getInventoryReportRows(): array
    {
        return $this->db->fetchAll(
            "SELECT i.*,
                COALESCE(SUM(CASE WHEN t.type = 'IMPORT' THEN t.quantity ELSE 0 END), 0) as total_import,
                COALESCE(SUM(CASE WHEN t.type IN ('EXPORT', 'USAGE') THEN t.quantity ELSE 0 END), 0) as total_out,
                COALESCE(SUM(CASE WHEN t.type = 'ADJUSTMENT' THEN t.quantity ELSE 0 END), 0) as total_adjust
             FROM inventory i
             LEFT JOIN inventory_transactions t ON i.id = t.inventory_id
             GROUP BY i.id
             ORDER BY (i.quantity * i.price) DESC, i.ingredient_name ASC"
        );
    }
}
