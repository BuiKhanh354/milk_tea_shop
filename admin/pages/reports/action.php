<?php
// admin/pages/reports/action.php — Báo cáo & Thống kê kinh doanh chuẩn lọc theo kỳ

$period = get_param('period', 'this_month');
$fromDate = get_param('from_date', '');
$toDate = get_param('to_date', '');
$statusFilter = get_param('status', '');

$whereOrder = ["1=1"];
$paramsOrder = [];

if ($period === 'today') {
    $whereOrder[] = "DATE(o.created_at) = CURDATE()";
    $periodLabel = "Hôm nay (" . date('d/m/Y') . ")";
} elseif ($period === 'yesterday') {
    $whereOrder[] = "DATE(o.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    $periodLabel = "Hôm qua (" . date('d/m/Y', strtotime('-1 day')) . ")";
} elseif ($period === 'this_week') {
    $whereOrder[] = "YEARWEEK(o.created_at, 1) = YEARWEEK(CURDATE(), 1)";
    $periodLabel = "Tuần này";
} elseif ($period === 'this_month') {
    $whereOrder[] = "YEAR(o.created_at) = YEAR(CURDATE()) AND MONTH(o.created_at) = MONTH(CURDATE())";
    $periodLabel = "Tháng này (" . date('m/Y') . ")";
} elseif ($period === 'last_month') {
    $whereOrder[] = "o.created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH) AND o.created_at < DATE_FORMAT(CURDATE(), '%Y-%m-01')";
    $periodLabel = "Tháng trước (" . date('m/Y', strtotime('first day of last month')) . ")";
} elseif ($period === 'this_year') {
    $whereOrder[] = "YEAR(o.created_at) = YEAR(CURDATE())";
    $periodLabel = "Năm nay (" . date('Y') . ")";
} elseif ($period === 'custom' && !empty($fromDate) && !empty($toDate)) {
    $whereOrder[] = "DATE(o.created_at) >= ? AND DATE(o.created_at) <= ?";
    $paramsOrder[] = $fromDate;
    $paramsOrder[] = $toDate;
    $periodLabel = "Từ " . date('d/m/Y', strtotime($fromDate)) . " đến " . date('d/m/Y', strtotime($toDate));
} else {
    $period = 'all';
    $periodLabel = "Toàn bộ thời gian";
}

$orderWhereClause = implode(" AND ", $whereOrder);

// 1. Doanh thu hoàn thành, đơn hoàn thành và Giá trị trung bình đơn (AOV)
$revSql = "SELECT 
            COALESCE(SUM(o.final_amount), 0) as total_revenue, 
            COUNT(o.id) as total_completed_orders,
            COALESCE(AVG(o.final_amount), 0) as avg_order_value
          FROM orders o 
          WHERE o.status = 'completed' AND $orderWhereClause";
$revenueRow = db()->fetchOne($revSql, $paramsOrder);
$totalRevenue = (float)($revenueRow['total_revenue'] ?? 0);
$totalCompletedOrders = (int)($revenueRow['total_completed_orders'] ?? 0);
$avgOrderValue = (float)($revenueRow['avg_order_value'] ?? 0);

// 2. Tổng số ly / sản phẩm đã bán ra trong kỳ
$soldSql = "SELECT COALESCE(SUM(od.quantity), 0) as total_sold 
            FROM order_details od 
            JOIN orders o ON od.order_id = o.id 
            WHERE o.status = 'completed' AND $orderWhereClause";
$soldRow = db()->fetchOne($soldSql, $paramsOrder);
$totalSold = (int)($soldRow['total_sold'] ?? 0);

// 3. Khách hàng mới đăng ký trong kỳ
$custWhere = ["1=1"];
$custParams = [];
if ($period === 'today') {
    $custWhere[] = "DATE(created_at) = CURDATE()";
} elseif ($period === 'yesterday') {
    $custWhere[] = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
} elseif ($period === 'this_week') {
    $custWhere[] = "YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($period === 'this_month') {
    $custWhere[] = "YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())";
} elseif ($period === 'last_month') {
    $custWhere[] = "created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH) AND created_at < DATE_FORMAT(CURDATE(), '%Y-%m-01')";
} elseif ($period === 'this_year') {
    $custWhere[] = "YEAR(created_at) = YEAR(CURDATE())";
} elseif ($period === 'custom' && !empty($fromDate) && !empty($toDate)) {
    $custWhere[] = "DATE(created_at) >= ? AND DATE(created_at) <= ?";
    $custParams[] = $fromDate;
    $custParams[] = $toDate;
}
$custClause = implode(" AND ", $custWhere);
$newCustRow = db()->fetchOne("SELECT COUNT(id) as new_cust FROM customers WHERE $custClause", $custParams);
$newCustomers = (int)($newCustRow['new_cust'] ?? 0);
$totalCustomersAll = (int)(db()->fetchOne("SELECT COUNT(id) as total FROM customers")['total'] ?? 0);

// 4. Thống kê đơn hàng THEO TẤT CẢ TRẠNG THÁI trong kỳ
$statusRows = db()->fetchAll("SELECT o.status, COUNT(o.id) as count, COALESCE(SUM(o.final_amount), 0) as total_amount 
                              FROM orders o 
                              WHERE $orderWhereClause 
                              GROUP BY o.status", $paramsOrder);
$statusMap = [
    'pending'    => ['label' => 'Chờ xác nhận', 'badge' => 'bg-secondary', 'icon' => 'fa-clock'],
    'confirmed'  => ['label' => 'Đã xác nhận',  'badge' => 'bg-info text-dark', 'icon' => 'fa-check'],
    'preparing'  => ['label' => 'Đang pha chế', 'badge' => 'bg-warning text-dark', 'icon' => 'fa-blender'],
    'ready'      => ['label' => 'Sẵn sàng',     'badge' => 'bg-primary', 'icon' => 'fa-box'],
    'completed'  => ['label' => 'Hoàn thành',   'badge' => 'bg-success', 'icon' => 'fa-circle-check'],
    'cancelled'  => ['label' => 'Đã hủy',       'badge' => 'bg-danger',  'icon' => 'fa-ban'],
];
$ordersByStatus = [];
$totalOrdersInPeriod = 0;
foreach ($statusRows as $sr) {
    $st = $sr['status'];
    $cnt = (int)$sr['count'];
    $totalOrdersInPeriod += $cnt;
    $meta = $statusMap[$st] ?? ['label' => $st, 'badge' => 'bg-secondary', 'icon' => 'fa-circle'];
    $ordersByStatus[$st] = [
        'status' => $st,
        'label' => $meta['label'],
        'badge' => $meta['badge'],
        'icon' => $meta['icon'],
        'count' => $cnt,
        'amount' => (float)$sr['total_amount']
    ];
}

// 5. Thống kê doanh thu & đơn hàng THEO NGÀY trong kỳ
$dailySql = "SELECT 
                DATE(o.created_at) as report_date,
                COUNT(o.id) as total_orders,
                SUM(CASE WHEN o.status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END) as daily_revenue
             FROM orders o
             WHERE $orderWhereClause
             GROUP BY DATE(o.created_at)
             ORDER BY report_date DESC";
$dailyReports = db()->fetchAll($dailySql, $paramsOrder);

// 6. Top sản phẩm bán chạy nhất trong kỳ
$topSql = "SELECT p.id, p.name, p.image, c.name as category_name,
                  SUM(od.quantity) as sold_count, 
                  SUM(od.subtotal) as total_sales
           FROM order_details od
           JOIN orders o ON od.order_id = o.id
           JOIN products p ON od.product_id = p.id
           LEFT JOIN categories c ON p.category_id = c.id
           WHERE o.status = 'completed' AND $orderWhereClause
           GROUP BY p.id
           ORDER BY sold_count DESC
           LIMIT 10";
$topProducts = db()->fetchAll($topSql, $paramsOrder);

// 7. Thống kê theo Phương thức thanh toán
$paymentSql = "SELECT 
                CASE 
                    WHEN pay.method = 'cash' THEN 'Tiền mặt'
                    WHEN pay.method = 'bank_transfer' THEN 'Chuyển khoản'
                    WHEN pay.method = 'vnpay' THEN 'VNPAY'
                    WHEN pay.method = 'momo' THEN 'MoMo'
                    WHEN pay.method = 'cod' THEN 'Thanh toán khi nhận hàng (COD)'
                    ELSE COALESCE(pay.method, 'Khác / Tiền mặt')
                END as method_name,
                COUNT(DISTINCT o.id) as order_count,
                SUM(o.final_amount) as total_revenue
               FROM orders o
               LEFT JOIN payments pay ON o.id = pay.order_id
               WHERE o.status = 'completed' AND $orderWhereClause
               GROUP BY method_name
               ORDER BY total_revenue DESC";
$paymentReports = db()->fetchAll($paymentSql, $paramsOrder);

// 8. Thống kê theo Hình thức mua hàng (Tại quán, Mang đi, Giao hàng)
$typeSql = "SELECT 
                o.order_type,
                COUNT(o.id) as order_count,
                SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END) as total_revenue
            FROM orders o
            WHERE $orderWhereClause
            GROUP BY o.order_type";
$typeRows = db()->fetchAll($typeSql, $paramsOrder);
$typeMap = [
    'dine_in'  => 'Dùng tại quán',
    'takeaway' => 'Mang về',
    'delivery' => 'Giao hàng tận nơi'
];

$pageTitle = 'Báo cáo & Thống kê - ' . $periodLabel;
