<?php
// admin/pages/tables/action.php — Quản lý bàn
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $tableNumber = post_param('table_number');
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
    $pageTitle = 'Chi tiết Bàn ' . $table['table_number'];
} else {
    $tables = db()->fetchAll("SELECT * FROM tables ORDER BY table_number ASC");
    $pageTitle = 'Quản lý Bàn';
}
