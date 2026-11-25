<?php
/**
 * ProductOption — Quản lý tùy chọn đường / đá (bảng item_options).
 */
class ProductOption extends BaseModel
{
    protected string $table = 'item_options';

    public function getByType(string $type, bool $onlyActive = false): array
    {
        $statusClause = $onlyActive ? "AND (status = 1 OR status IS NULL)" : "";
        return $this->db->fetchAll(
            "SELECT * FROM item_options WHERE type = ? $statusClause ORDER BY value ASC",
            [$type]
        );
    }

    public function store(string $type, string $name, int $value, int $isDefault = 0, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO item_options (type, name, value, is_default, status) VALUES (?, ?, ?, ?, ?)",
            [$type, $name, $value, $isDefault, $status]
        );
    }

    public function update(int $id, string $type, string $name, int $value, int $isDefault = 0, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "UPDATE item_options SET name = ?, value = ?, is_default = ?, status = ? WHERE id = ? AND type = ?",
            [$name, $value, $isDefault, $status, $id, $type]
        );
    }
}
