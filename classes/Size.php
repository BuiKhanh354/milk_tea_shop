<?php
/**
 * Size — Quản lý kích cỡ sản phẩm (bảng sizes).
 */
class Size extends BaseModel
{
    protected string $table = 'sizes';

    public function getActive(bool $includeHidden = false): array
    {
        $where = $includeHidden ? "" : "WHERE status = 1";
        return $this->db->fetchAll(
            "SELECT id, name, extra_price, extra_price as price_modifier, status FROM sizes $where ORDER BY extra_price ASC"
        );
    }

    public function store(string $name, float $extraPrice, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO sizes (name, extra_price, status) VALUES (?, ?, ?)",
            [$name, $extraPrice, $status]
        );
    }

    public function update(int $id, string $name, float $extraPrice, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "UPDATE sizes SET name = ?, extra_price = ?, status = ? WHERE id = ?",
            [$name, $extraPrice, $status, $id]
        );
    }
}
