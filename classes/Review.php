<?php
/**
 * Review — Quản lý đánh giá sản phẩm (bảng reviews).
 */
class Review extends BaseModel
{
    protected string $table = 'reviews';

    public function getVisibleByProduct(int $productId): array
    {
        return $this->db->fetchAll(
            "SELECT r.rating, r.comment, DATE_FORMAT(r.created_at, '%d/%m/%Y') as date, c.full_name as name
             FROM reviews r
             JOIN customers c ON r.customer_id = c.id
             WHERE r.product_id = ? AND (r.status = 1 OR r.status IS NULL)
             ORDER BY r.created_at DESC",
            [$productId]
        );
    }

    public function getPurchasedOrderId(int $customerId, int $productId): ?int
    {
        $row = $this->db->fetchOne(
            "SELECT od.order_id
             FROM order_details od
             JOIN orders o ON od.order_id = o.id
             WHERE o.customer_id = ? AND od.product_id = ? AND o.status IN ('completed', 'Hoàn thành')
             ORDER BY o.id DESC LIMIT 1",
            [$customerId, $productId]
        );
        return $row ? (int)$row['order_id'] : null;
    }

    public function findByCustomerAndProduct(int $customerId, int $productId): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT * FROM reviews WHERE customer_id = ? AND product_id = ? LIMIT 1",
            [$customerId, $productId]
        );
        return $row ?: null;
    }

    public function submitReview(int $customerId, int $productId, int $rating, string $comment, ?int $orderId = null): bool
    {
        $existing = $this->findByCustomerAndProduct($customerId, $productId);
        if ($existing) {
            $this->db->execute(
                "UPDATE reviews SET rating = ?, comment = ?, order_id = ?, status = 1, created_at = NOW() WHERE id = ?",
                [$rating, $comment, $orderId, (int)$existing['id']]
            );
            return false; // updated
        }

        $this->db->execute(
            "INSERT INTO reviews (customer_id, product_id, order_id, rating, comment, status, created_at)
             VALUES (?, ?, ?, ?, ?, 1, NOW())",
            [$customerId, $productId, $orderId, $rating, $comment]
        );
        return true; // created
    }

    public function toggleStatus(int $id): ?int
    {
        $review = $this->db->fetchOne("SELECT id, status FROM reviews WHERE id = ?", [$id]);
        if (!$review) {
            return null;
        }
        $newStatus = ((int)$review['status'] === 1) ? 0 : 1;
        $this->db->execute("UPDATE reviews SET status = ? WHERE id = ?", [$newStatus, $id]);
        return $newStatus;
    }

    public function getStats(): ?array
    {
        return $this->db->fetchOne(
            "SELECT
                COUNT(*) as total_reviews,
                ROUND(AVG(rating), 1) as avg_rating,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as visible_count,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as hidden_count
             FROM reviews"
        );
    }
}
