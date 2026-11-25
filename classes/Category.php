<?php
/**
 * Category — Quản lý danh mục sản phẩm (bảng categories).
 */
class Category extends BaseModel
{
    protected string $table = 'categories';

    public function getAllWithProductCount(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(p.id) as products_count
             FROM categories c
             LEFT JOIN products p ON c.id = p.category_id
             GROUP BY c.id
             ORDER BY c.id ASC"
        );
    }

    public function store(string $name, string $description = ''): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO categories (name, description) VALUES (?, ?)",
            [$name, $description]
        );
    }

    public function update(int $id, string $name, string $description = ''): bool
    {
        return (bool)$this->db->execute(
            "UPDATE categories SET name = ?, description = ? WHERE id = ?",
            [$name, $description, $id]
        );
    }

    public function countProducts(int $id): int
    {
        $row = $this->db->fetchOne("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$id]);
        return (int)($row['count'] ?? 0);
    }

    public function delete(int $id): bool
    {
        if ($this->countProducts($id) > 0) {
            return false;
        }
        return $this->deleteById($id);
    }
}
