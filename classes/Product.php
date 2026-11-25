<?php
/**
 * Product — Quản lý sản phẩm & topping gắn kèm (bảng products, product_toppings).
 */
class Product extends BaseModel
{
    protected string $table = 'products';

    public function getPaginated(int $page = 1, int $limit = 8, array $filters = [], bool $onlyActive = true): array
    {
        $offset = max(0, ($page - 1) * $limit);
        $where = $onlyActive ? ["p.status = 1"] : ["1=1"];
        $params = [];

        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id = ?";
            $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $where[] = "p.name LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $where[] = "p.status = ?";
            $params[] = (int)$filters['status'];
        }

        $whereSql = implode(' AND ', $where);
        $countRow = $this->db->fetchOne("SELECT COUNT(*) as total FROM products p WHERE $whereSql", $params);
        $total = (int)($countRow['total'] ?? 0);

        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE $whereSql
                ORDER BY p.id DESC
                LIMIT ? OFFSET ?";
        $data = $this->db->fetchAll($sql, array_merge($params, [$limit, $offset]));

        return [
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $total > 0 ? (int)ceil($total / $limit) : 1,
        ];
    }

    public function findWithCategory(int $id, bool $includeHidden = false): ?array
    {
        $statusClause = $includeHidden ? "" : "AND p.status = 1";
        $row = $this->db->fetchOne(
            "SELECT p.*, c.name as category_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? $statusClause",
            [$id]
        );
        return $row ?: null;
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM products
             WHERE category_id = ? AND id != ? AND status = 1
             ORDER BY id DESC LIMIT ?",
            [$categoryId, $excludeId, $limit]
        );
    }

    public function create(array $data, array $toppingIds = []): int
    {
        $this->db->execute(
            "INSERT INTO products (name, category_id, price, description, image, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [
                $data['name'],
                (int)$data['category_id'],
                (float)$data['price'],
                $data['description'] ?? '',
                $data['image'] ?? null,
                (int)($data['status'] ?? 1),
            ]
        );
        $productId = (int)$this->db->insertId();
        if ($productId > 0) {
            $this->syncToppings($productId, $toppingIds);
        }
        return $productId;
    }

    public function update(int $id, array $data, ?array $toppingIds = null): bool
    {
        $ok = (bool)$this->db->execute(
            "UPDATE products
             SET name = ?, category_id = ?, price = ?, description = ?, image = ?, status = ?, updated_at = NOW()
             WHERE id = ?",
            [
                $data['name'],
                (int)$data['category_id'],
                (float)$data['price'],
                $data['description'] ?? '',
                $data['image'] ?? null,
                (int)($data['status'] ?? 1),
                $id,
            ]
        );
        if ($toppingIds !== null) {
            $this->syncToppings($id, $toppingIds);
        }
        return $ok;
    }

    public function toggleStatus(int $id): ?int
    {
        $current = $this->find($id);
        if (!$current) {
            return null;
        }
        $newStatus = ((int)$current['status'] === 1) ? 0 : 1;
        $this->db->execute("UPDATE products SET status = ? WHERE id = ?", [$newStatus, $id]);
        return $newStatus;
    }

    public function delete(int $id): bool
    {
        $this->db->execute("DELETE FROM product_toppings WHERE product_id = ?", [$id]);
        return $this->deleteById($id);
    }

    public function getAssignedToppingIds(int $productId): array
    {
        $rows = $this->db->fetchAll("SELECT topping_id FROM product_toppings WHERE product_id = ?", [$productId]);
        return array_map(static fn($r) => (int)$r['topping_id'], $rows);
    }

    public function syncToppings(int $productId, array $toppingIds): void
    {
        $this->db->execute("DELETE FROM product_toppings WHERE product_id = ?", [$productId]);
        foreach ($toppingIds as $topId) {
            $tid = (int)$topId;
            if ($tid > 0) {
                $this->db->execute(
                    "INSERT IGNORE INTO product_toppings (product_id, topping_id) VALUES (?, ?)",
                    [$productId, $tid]
                );
            }
        }
    }
}
