<?php
/**
 * Order — Quản lý đơn hàng, chi tiết đơn, topping kèm theo & trừ kho theo công thức.
 */
class Order extends BaseModel
{
    protected string $table = 'orders';

    public function deductStockByRecipe(int $orderId, int $userId = 1): void
    {
        $items = $this->db->fetchAll("SELECT * FROM order_details WHERE order_id = ?", [$orderId]);
        foreach ($items as $item) {
            $pid = (int)$item['product_id'];
            $sid = (int)($item['size_id'] ?? 0);
            $qty = (int)$item['quantity'];

            $recipes = $this->db->fetchAll(
                "SELECT * FROM product_ingredients WHERE product_id = ? AND (size_id = ? OR size_id = 0)",
                [$pid, $sid]
            );
            foreach ($recipes as $rc) {
                $deductQty = (float)$rc['quantity'] * $qty;
                $this->db->execute(
                    "UPDATE inventory SET quantity = GREATEST(0, quantity - ?) WHERE id = ?",
                    [$deductQty, $rc['inventory_id']]
                );
                $this->db->execute(
                    "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, note) VALUES (?, ?, 'USAGE', ?, ?)",
                    [$rc['inventory_id'], $userId, $deductQty, "Trừ kho tự động theo đơn #ORD{$orderId}"]
                );
            }
        }
    }

    public function updateStatus(int $id, string $status, int $userId = 1): bool
    {
        $oldOrder = $this->find($id);
        if (!$oldOrder) {
            return false;
        }

        $this->db->execute("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);

        if (empty($oldOrder['shift_session_id'])) {
            $openSession = $this->db->fetchOne("SELECT id FROM shift_sessions WHERE status = 'open' ORDER BY opened_at DESC LIMIT 1");
            if ($openSession) {
                $this->db->execute("UPDATE orders SET shift_session_id = ? WHERE id = ?", [$openSession['id'], $id]);
            }
        }

        if ($status === 'completed' && $oldOrder['status'] !== 'completed') {
            $this->db->execute("UPDATE payments SET status = 'paid' WHERE order_id = ?", [$id]);
            $this->deductStockByRecipe($id, $userId);
        }

        if ($oldOrder['order_type'] === 'dine_in' && !empty($oldOrder['table_id'])) {
            $tId = (int)$oldOrder['table_id'];
            if (in_array($status, ['completed', 'cancelled'], true)) {
                $otherOpen = $this->db->fetchOne(
                    "SELECT COUNT(*) as c FROM orders WHERE table_id = ? AND id != ? AND status NOT IN ('completed', 'cancelled')",
                    [$tId, $id]
                );
                if ((int)($otherOpen['c'] ?? 0) === 0) {
                    $this->db->execute("UPDATE tables SET status = 'available' WHERE id = ?", [$tId]);
                }
            } else {
                $this->db->execute("UPDATE tables SET status = 'occupied' WHERE id = ?", [$tId]);
            }
        }

        return true;
    }

    public function getDetail(int $id): ?array
    {
        $order = $this->db->fetchOne(
            "SELECT o.*,
                c.full_name as customer_name, c.phone as customer_phone, c.address as customer_address,
                u.full_name as user_name, u.phone as user_phone,
                p.method as payment_method, p.status as payment_status,
                t.table_number
             FROM orders o
             LEFT JOIN customers c ON o.customer_id = c.id
             LEFT JOIN users u ON o.user_id = u.id
             LEFT JOIN payments p ON o.id = p.order_id
             LEFT JOIN tables t ON o.table_id = t.id
             WHERE o.id = ?",
            [$id]
        );
        if (!$order) {
            return null;
        }

        $items = $this->db->fetchAll(
            "SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
             FROM order_details od
             JOIN products p ON od.product_id = p.id
             LEFT JOIN sizes s ON od.size_id = s.id
             WHERE od.order_id = ?",
            [$id]
        );

        foreach ($items as &$item) {
            $toppingRows = $this->db->fetchAll(
                "SELECT t.name FROM order_detail_toppings odt JOIN toppings t ON odt.topping_id = t.id WHERE odt.order_detail_id = ?",
                [$item['id']]
            );
            $item['toppings'] = implode(', ', array_column($toppingRows, 'name'));
        }
        unset($item);
        $order['items'] = $items;
        return $order;
    }

    public function getByCustomer(int $customerId): array
    {
        return $this->db->fetchAll(
            "SELECT o.*, p.method as payment_method, p.status as payment_status
             FROM orders o
             LEFT JOIN payments p ON o.id = p.order_id
             WHERE o.customer_id = ?
             ORDER BY o.created_at DESC",
            [$customerId]
        );
    }

    public function getCustomerOrderDetail(int $orderId, int $customerId): ?array
    {
        $order = $this->db->fetchOne(
            "SELECT o.*, p.method as payment_method, p.status as payment_status
             FROM orders o
             LEFT JOIN payments p ON o.id = p.order_id
             WHERE o.id = ? AND o.customer_id = ?",
            [$orderId, $customerId]
        );
        if (!$order) {
            return null;
        }

        $items = $this->db->fetchAll(
            "SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
             FROM order_details od
             JOIN products p ON od.product_id = p.id
             LEFT JOIN sizes s ON od.size_id = s.id
             WHERE od.order_id = ?",
            [$orderId]
        );

        foreach ($items as &$item) {
            $toppingRows = $this->db->fetchAll(
                "SELECT t.name, odt.price FROM order_detail_toppings odt JOIN toppings t ON odt.topping_id = t.id WHERE odt.order_detail_id = ?",
                [$item['id']]
            );
            $item['toppings'] = $toppingRows;
        }
        unset($item);
        $order['items'] = $items;
        return $order;
    }

    public function getPaginated(int $page = 1, int $limit = 10, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $limit);
        $conditions = [];
        $params = [];

        $search = $filters['search'] ?? '';
        $status = $filters['status'] ?? '';
        $orderType = $filters['order_type'] ?? '';

        if ($search !== '') {
            $cleanId = ltrim($search, '#ORDord0');
            $conditions[] = "(o.id = ? OR c.full_name LIKE ? OR u.full_name LIKE ?)";
            $params[] = is_numeric($cleanId) ? (int)$cleanId : -1;
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        if ($status !== '') {
            $conditions[] = "o.status = ?";
            $params[] = $status;
        }
        if ($orderType !== '' && in_array($orderType, ['dine_in', 'takeaway', 'delivery'], true)) {
            $conditions[] = "o.order_type = ?";
            $params[] = $orderType;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        $countRow = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM orders o
             LEFT JOIN customers c ON o.customer_id = c.id
             LEFT JOIN users u ON o.user_id = u.id
             $whereClause",
            $params
        );
        $total = (int)($countRow['total'] ?? 0);

        $rows = $this->db->fetchAll(
            "SELECT o.*,
                COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
                COALESCE(p.method, 'Tiền mặt') as payment_method,
                t.table_number
             FROM orders o
             LEFT JOIN customers c ON o.customer_id = c.id
             LEFT JOIN users u ON o.user_id = u.id
             LEFT JOIN payments p ON o.id = p.order_id
             LEFT JOIN tables t ON o.table_id = t.id
             $whereClause
             ORDER BY o.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$limit, $offset])
        );

        return [
            'data'        => $rows,
            'total'       => $total,
            'total_pages' => (int)ceil($total / $limit),
        ];
    }
}
