<?php
/**
 * Favorite — Quản lý sản phẩm yêu thích của khách hàng (bảng favorites).
 */
class Favorite extends BaseModel
{
    protected string $table = 'favorites';

    public function isFavorited(?int $customerId, int $productId): bool
    {
        if (!$customerId) {
            return false;
        }
        $row = $this->db->fetchOne(
            "SELECT product_id FROM favorites WHERE customer_id = ? AND product_id = ?",
            [$customerId, $productId]
        );
        return !empty($row);
    }

    public function toggle(int $customerId, int $productId): string
    {
        if ($this->isFavorited($customerId, $productId)) {
            $this->db->execute(
                "DELETE FROM favorites WHERE customer_id = ? AND product_id = ?",
                [$customerId, $productId]
            );
            return 'removed';
        }

        $this->db->execute(
            "INSERT INTO favorites (customer_id, product_id, created_at) VALUES (?, ?, NOW())",
            [$customerId, $productId]
        );
        return 'added';
    }

    public function getByCustomer(int $customerId): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name
             FROM favorites f
             JOIN products p ON f.product_id = p.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE f.customer_id = ? AND p.status = 1
             ORDER BY f.created_at DESC",
            [$customerId]
        );
    }
}
