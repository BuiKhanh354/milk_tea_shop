<?php
// admin/pages/tables/action.php — Quản lý bàn & Theo dõi đơn phục vụ tại bàn
$action = get_param('action', '');
$tableModel = new DiningTable();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($action === 'store') {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') {
            set_flash('error', 'Nhân viên không có quyền thêm bàn mới!');
            redirect(admin_url('tables'));
        }

        $tableNumber = trim(post_param('table_number'));
        $capacity = (int)post_param('capacity', 4);

        if (!empty($tableNumber) && $capacity > 0) {
            if ($tableModel->findByNumber($tableNumber)) {
                set_flash('error', "Bàn {$tableNumber} đã tồn tại! Vui lòng chọn số bàn khác.");
            } else {
                $tableModel->store($tableNumber, $capacity);
                set_flash('success', "Thêm bàn mới thành công!");
            }
        }
        redirect(admin_url('tables'));
    }

    if ($action === 'update_status') {
        $id = (int)post_param('id');
        $status = post_param('status');
        if ($id && $tableModel->updateStatus($id, $status)) {
            set_flash('success', "Đã cập nhật trạng thái bàn!");
        }
        redirect(admin_url('tables', ['action' => 'show', 'id' => $id]));
    }
}

if ($action === 'show') {
    $id = (int)get_param('id');
    $table = $tableModel->find($id);
    if (!$table) {
        redirect(admin_url('tables'));
    }

    $activeOrders = $tableModel->getActiveOrders($id);

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
