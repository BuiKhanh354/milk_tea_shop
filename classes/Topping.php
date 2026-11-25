<?php
/**
 * Topping — Quản lý topping (bảng toppings).
 */
class Topping extends BaseModel
{
    protected string $table = 'toppings';

    public function getAvailable(?int $productId = null): array
    {
        if ($productId !== null) {
            return $this->db->fetchAll(
                "SELECT t.* FROM toppings t
                 JOIN product_toppings pt ON t.id = pt.topping_id
                 WHERE pt.product_id = ? AND (t.status = 'available' OR t.status = 1)
                 ORDER BY t.price ASC",
                [$productId]
            );
        }

        return $this->db->fetchAll(
            "SELECT * FROM toppings WHERE status = 'available' OR status = 1 ORDER BY price ASC"
        );
    }

    public function store(string $name, float $price, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO toppings (name, price, status) VALUES (?, ?, ?)",
            [$name, $price, $status]
        );
    }

    public function update(int $id, string $name, float $price, int $status = 1): bool
    {
        return (bool)$this->db->execute(
            "UPDATE toppings SET name = ?, price = ?, status = ? WHERE id = ?",
            [$name, $price, $status, $id]
        );
    }
}
