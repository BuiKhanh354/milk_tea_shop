<?php
// pages/orders/action.php — Lịch sử đơn hàng của khách
if (!isset($_SESSION['customer_id'])) {
    redirect(url('login'));
}

$customerId = (int)$_SESSION['customer_id'];
$title = 'Đơn hàng của tôi';

$orders = db()->fetchAll("SELECT o.*, p.payment_method, p.status as payment_status 
                          FROM orders o 
                          LEFT JOIN payments p ON p.order_id = o.id 
                          WHERE o.customer_id = ? 
                          ORDER BY o.id DESC", array($customerId));

