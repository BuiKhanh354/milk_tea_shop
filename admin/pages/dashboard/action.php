<?php
// admin/pages/dashboard/action.php
$title = 'Báo cáo thống kê';

$totalProducts = (int)(db()->fetchOne("SELECT COUNT(*) as total FROM products")['total'] ?? 0);
$totalOrders = (int)(db()->fetchOne("SELECT COUNT(*) as total FROM orders")['total'] ?? 0);
$totalCustomers = (int)(db()->fetchOne("SELECT COUNT(*) as total FROM customers")['total'] ?? 0);
$totalRevenue = (float)(db()->fetchOne("SELECT SUM(final_amount) as total FROM orders WHERE status = 'completed'")['total'] ?? 0);

$recentOrders = db()->fetchAll("SELECT o.*, c.name as customer_name 
                                FROM orders o 
                                LEFT JOIN customers c ON o.customer_id = c.id 
                                ORDER BY o.id DESC LIMIT 5");

