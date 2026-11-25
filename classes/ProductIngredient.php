<?php
/**
 * ProductIngredient — Quản lý định lượng công thức theo size (bảng product_ingredients).
 */
class ProductIngredient extends BaseModel
{
    protected string $table = 'product_ingredients';

    public function getIngredientNames(int $productId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT DISTINCT i.ingredient_name
             FROM product_ingredients pi
             JOIN inventory i ON pi.inventory_id = i.id
             WHERE pi.product_id = ?
             ORDER BY i.ingredient_name ASC",
            [$productId]
        );
        return !empty($rows) ? array_column($rows, 'ingredient_name') : [];
    }

    public function getRecipesByProductGroupedBySize(int $productId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT pi.*, i.ingredient_name, i.unit
             FROM product_ingredients pi
             JOIN inventory i ON pi.inventory_id = i.id
             WHERE pi.product_id = ?
             ORDER BY i.ingredient_name ASC",
            [$productId]
        );
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[(int)$row['size_id']][] = $row;
        }
        return $grouped;
    }

    public function getProductsWithIngredientCount(): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name,
                (SELECT COUNT(DISTINCT inventory_id) FROM product_ingredients WHERE product_id = p.id) as ingredients_count
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             ORDER BY p.id ASC"
        );
    }

    public function saveRecipes(int $productId, array $recipesInput): void
    {
        $this->db->execute("DELETE FROM product_ingredients WHERE product_id = ?", [$productId]);
        foreach ($recipesInput as $sizeId => $data) {
            $sid = (int)$sizeId;
            $ingIds = $data['ingredient_id'] ?? [];
            $qtys = $data['quantity'] ?? [];
            for ($i = 0; $i < count($ingIds); $i++) {
                $invId = (int)($ingIds[$i] ?? 0);
                $qty = (float)($qtys[$i] ?? 0);
                if ($invId > 0 && $qty > 0) {
                    $this->db->execute(
                        "INSERT INTO product_ingredients (product_id, size_id, inventory_id, quantity) VALUES (?, ?, ?, ?)",
                        [$productId, $sid, $invId, $qty]
                    );
                }
            }
        }
    }
}
