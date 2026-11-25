<?php
/**
 * DiningTable — Quản lý bàn tại quán & đơn hàng đang phục vụ (bảng tables).
 */
class DiningTable extends BaseModel
{
    protected string $table = 'tables';

    public function findByNumber(string $tableNumber): ?array
    {
        $row = $this->db->fetchOne("SELECT * FROM tables WHERE table_number = ?", [$tableNumber]);
        return $row ?: null;
    }

    public function store(string $tableNumber, int $capacity = 4): bool
    {
        return (bool)$this->db->execute(
            "INSERT INTO tables (table_number, capacity, status) VALUES (?, ?, 'available')",
            [$tableNumber, $capacity]
        );
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['available', 'occupied', 'reserved'], true)) {
            return false;
        }
        return (bool)$this->db->execute("UPDATE tables SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function getAllWithActiveOrders(): array
    {
        return $this->db->fetchAll(
            "SELECT t.*,
                (SELECT COUNT(*) FROM orders o WHERE o.table_id = t.id AND o.status NOT IN ('completed', 'cancelled')) as active_orders_count,
                (SELECT o2.id FROM orders o2 WHERE o2.table_id = t.id AND o2.status NOT IN ('completed', 'cancelled') ORDER BY o2.created_at DESC LIMIT 1) as latest_order_id,
                (SELECT o3.status FROM orders o3 WHERE o3.table_id = t.id AND o3.status NOT IN ('completed', 'cancelled') ORDER BY o3.created_at DESC LIMIT 1) as latest_order_status,
                (SELECT o4.final_amount FROM orders o4 WHERE o4.table_id = t.id AND o4.status NOT IN ('completed', 'cancelled') ORDER BY o4.created_at DESC LIMIT 1) as latest_order_amount
             FROM tables t
             ORDER BY t.table_number ASC"
        );
    }

    public function getActiveOrders(int $tableId): array
    {
        $activeOrders = $this->db->fetchAll(
            "SELECT o.*,
                COALESCE(c.full_name, u.full_name, 'Khách tại bàn') as customer_name,
                u.full_name as staff_name,
                COALESCE(p.method, 'cash') as payment_method,
                COALESCE(p.status, 'pending') as payment_status
             FROM orders o
             LEFT JOIN customers c ON o.customer_id = c.id
             LEFT JOIN users u ON o.user_id = u.id
             LEFT JOIN payments p ON o.id = p.order_id
             WHERE o.table_id = ? AND o.status NOT IN ('completed', 'cancelled')
             ORDER BY o.created_at DESC",
            [$tableId]
        );

        foreach ($activeOrders as &$ao) {
            $items = $this->db->fetchAll(
                "SELECT od.*, p.name as product_name, s.name as size_name
                 FROM order_details od
                 JOIN products p ON od.product_id = p.id
                 LEFT JOIN sizes s ON od.size_id = s.id
                 WHERE od.order_id = ?",
                [$ao['id']]
            );
            foreach ($items as &$item) {
                $toppingRows = $this->db->fetchAll(
                    "SELECT t.name FROM order_detail_toppings odt JOIN toppings t ON odt.topping_id = t.id WHERE odt.order_detail_id = ?",
                    [$item['id']]
                );
                $item['toppings'] = implode(', ', array_column($toppingRows, 'name'));
            }
            unset($item);
            $ao['items'] = $items;
        }
        unset($ao);

        return $activeOrders;
    }
}
