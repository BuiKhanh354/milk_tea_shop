<?php
// admin/pages/reports/action.php — Báo cáo & Thống kê
$period = get_param('period', 'today');

// Doanh thu & đơn hàng
$revenueRow = db()->fetchOne("SELECT COALESCE(SUM(final_amount), 0) as total_revenue, COUNT(id) as total_orders FROM orders WHERE status = 'completed'");
$totalRevenue = (float)($revenueRow['total_revenue'] ?? 0);
$totalOrders = (int)($revenueRow['total_orders'] ?? 0);

// Số sản phẩm bán ra
$soldRow = db()->fetchOne("SELECT COALESCE(SUM(od.quantity), 0) as total_sold FROM order_details od JOIN orders o ON od.order_id = o.id WHERE o.status = 'completed'");
$totalSold = (int)($soldRow['total_sold'] ?? 0);

// Khách hàng mới
$custRow = db()->fetchOne("SELECT COUNT(id) as total_cust FROM customers");
$totalCustomers = (int)($custRow['total_cust'] ?? 0);

// Top sản phẩm bán chạy
$topProducts = db()->fetchAll("SELECT p.name, p.image, SUM(od.quantity) as sold_count, SUM(od.total_price) as total_sales
    FROM order_details od
    JOIN orders o ON od.order_id = o.id
    JOIN products p ON od.product_id = p.id
    WHERE o.status = 'completed'
    GROUP BY p.id
    ORDER BY sold_count DESC
    LIMIT 5");

$pageTitle = 'Báo cáo & Thống kê';
