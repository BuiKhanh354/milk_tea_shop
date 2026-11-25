<?php
/**
 * BaseModel — Lớp cơ sở cho các Model thao tác với CSDL qua Database.
 */
abstract class BaseModel
{
    protected Database $db;
    protected string $table = '';

    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? db();
    }

    /**
     * Lấy một bản ghi theo khóa chính id.
     */
    public function find(int $id): ?array
    {
        $row = $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $row ?: null;
    }

    /**
     * Lấy toàn bộ bản ghi trong bảng.
     */
    public function all(string $orderBy = 'id ASC'): array
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
    }

    /**
     * Xóa bản ghi theo id.
     */
    public function deleteById(int $id): bool
    {
        return (bool)$this->db->execute("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}
