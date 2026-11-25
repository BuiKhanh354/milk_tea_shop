<?php
/**
 * Payment — Quản lý thanh toán (bảng payments).
 */
class Payment extends BaseModel
{
    protected string $table = 'payments';

    public function createForOrder(int $orderId, string $method, float $amount, string $status = 'pending', ?string $transactionCode = null): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO payments (order_id, method, amount, status, transaction_code, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [$orderId, $method, $amount, $status, $transactionCode]
        );
    }

    public function updateStatus(int $id, string $status, string $transactionCode = ''): bool
    {
        $ok = (bool)$this->db->execute(
            "UPDATE payments SET status = ?, transaction_code = ? WHERE id = ?",
            [$status, $transactionCode, $id]
        );
        if ($ok && $status === 'paid') {
            $payment = $this->db->fetchOne("SELECT order_id FROM payments WHERE id = ?", [$id]);
            if ($payment && !empty($payment['order_id'])) {
                $this->db->execute(
                    "UPDATE orders SET status = 'confirmed' WHERE id = ? AND status = 'pending'",
                    [$payment['order_id']]
                );
            }
        }
        return $ok;
    }

    public function getFiltered(string $search = '', string $method = '', string $status = '', int $limit = 50): array
    {
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = "(p.transaction_code LIKE ? OR CONCAT('ORD', o.id) LIKE ? OR o.id LIKE ? OR c.full_name LIKE ?)";
            $like = '%' . $search . '%';
            $params = array_merge($params, [$like, $like, $like, $like]);
        }
        if ($method !== '') {
            $conditions[] = "p.method = ?";
            $params[] = $method;
        }
        if ($status !== '') {
            $conditions[] = "p.status = ?";
            $params[] = $status;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        return $this->db->fetchAll(
            "SELECT p.*, o.id as order_id, o.status as order_status, c.full_name as customer_name
             FROM payments p
             LEFT JOIN orders o ON p.order_id = o.id
             LEFT JOIN customers c ON o.customer_id = c.id
             $whereClause
             ORDER BY p.created_at DESC LIMIT ?",
            array_merge($params, [$limit])
        );
    }
}
