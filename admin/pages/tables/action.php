<?php
// admin/pages/tables/action.php — Quản lý bàn & Theo dõi đơn phục vụ tại bàn
$action = get_param('action', '');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($action === 'store') {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') {
            set_flash('error', 'Nhân viên không có quyền thêm bàn mới!');
            redirect(admin_url('tables'));
        }

        $tableNumber = trim(post_param('table_number'));
        $capacity = (int)post_param('capacity', 4);

        if (!empty($tableNumber) && $capacity > 0) {
            $exists = db()->fetchOne("SELECT id FROM tables WHERE table_number = ?", [$tableNumber]);
            if ($exists) {
                set_flash('error', "Bàn {$tableNumber} đã tồn tại! Vui lòng chọn số bàn khác.");
            } else {
                db()->execute("INSERT INTO tables (table_number, capacity, status) VALUES (?, ?, 'available')", [$tableNumber, $capacity]);
                set_flash('success', "Thêm bàn mới thành công!");
            }
        }
        redirect(admin_url('tables'));
    }

    if ($action === 'update_status') {
        $id = (int)post_param('id');
        $status = post_param('status');
        if ($id && in_array($status, ['available', 'occupied', 'reserved'])) {
            db()->execute("UPDATE tables SET status = ? WHERE id = ?", [$status, $id]);
            set_flash('success', "Đã cập nhật trạng thái bàn!");
        }
        redirect(admin_url('tables', ['action' => 'show', 'id' => $id]));
    }
}

if ($action === 'show') {
    $id = (int)get_param('id');
    $table = db()->fetchOne("SELECT * FROM tables WHERE id = ?", [$id]);
    if (!$table) {
        redirect(admin_url('tables'));
    }

    // Lấy các đơn hàng đang phục vụ tại bàn (chưa hoàn thành / chưa hủy)
    $activeOrders = db()->fetchAll("
        SELECT o.*, 
               COALESCE(c.full_name, u.full_name, 'Khách tại bàn') as customer_name,
               u.full_name as staff_name,
               COALESCE(p.method, 'cash') as payment_method,
               COALESCE(p.status, 'pending') as payment_status
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN payments p ON o.id = p.order_id
        WHERE o.table_id = ? AND o.status NOT IN ('completed', 'cancelled')
        ORDER BY o.created_at DESC
    ", [$id]);

    foreach ($activeOrders as &$ao) {
        $items = db()->fetchAll("
            SELECT od.*, p.name as product_name, s.name as size_name
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            LEFT JOIN sizes s ON od.size_id = s.id
            WHERE od.order_id = ?
        ", [$ao['id']]);
        foreach ($items as &$item) {
            $toppingRows = db()->fetchAll("
                SELECT t.name 
                FROM order_detail_toppings odt 
                JOIN toppings t ON odt.topping_id = t.id 
                WHERE odt.order_detail_id = ?
            ", [$item['id']]);
            $item['toppings'] = implode(', ', array_column($toppingRows, 'name'));
        }
        unset($item);
        $ao['items'] = $items;
    }
    unset($ao);

    // Lấy lịch sử 5 đơn gần nhất đã xong/hủy tại bàn
    $recentTableOrders = db()->fetchAll("
        SELECT o.id, o.final_amount, o.status, o.created_at,
               COALESCE(c.full_name, u.full_name, 'Khách tại bàn') as customer_name
        FROM orders o
        LEFT JOIN customers c ON o.customer_id = c.id
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.table_id = ? AND o.status IN ('completed', 'cancelled')
        ORDER BY o.created_at DESC
        LIMIT 5
    ", [$id]);

    $pageTitle = 'Chi tiết Bàn ' . $table['table_number'];
} else {
    $tables = db()->fetchAll("
        SELECT t.*,
               COUNT(o.id) as active_orders_count,
               COALESCE(SUM(o.final_amount), 0) as active_total_amount
        FROM tables t
        LEFT JOIN orders o ON o.table_id = t.id AND o.status NOT IN ('completed', 'cancelled')
        GROUP BY t.id
        ORDER BY t.table_number ASC
    ");
    $pageTitle = 'Quản lý Bàn & Đơn tại bàn';
}
