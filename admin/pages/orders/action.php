<?php
// admin/pages/orders/action.php
$action = get_param('action');

if ($action === 'update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)get_param('id');
    $status = $_POST['status'] ?? 'pending';
    db()->execute("UPDATE orders SET status = ? WHERE id = ?", array($status, $id));
    redirect(admin_url('orders'));
}

$title = 'Quản lý đơn hàng';

$orders = db()->fetchAll("SELECT o.*, c.name as customer_name, c.phone as customer_phone 
                          FROM orders o 
                          LEFT JOIN customers c ON o.customer_id = c.id 
                          ORDER BY o.id DESC");

