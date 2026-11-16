<?php
// admin/pages/dashboard/action.php — Dashboard chuẩn gốc
$pageTitle = 'Dashboard';
$title = 'Tổng quan';

// Thống kê doanh thu hôm nay
$revRow = db()->fetchOne("SELECT SUM(final_amount) as total FROM orders WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
$todayRevenue = (float)($revRow['total'] ?? 0);

// Đơn hàng hôm nay
$ordRow = db()->fetchOne("SELECT COUNT(id) as total FROM orders WHERE DATE(created_at) = CURDATE()");
$todayOrders = (int)($ordRow['total'] ?? 0);

$totalCustomers = (int)(db()->fetchOne("SELECT COUNT(id) as total FROM customers")['total'] ?? 0);
$totalProducts = (int)(db()->fetchOne("SELECT COUNT(id) as total FROM products WHERE status = 1")['total'] ?? 0);

$stats = [
    'revenue' => [
        'value'  => money($todayRevenue),
        'growth' => '+0%'
    ],
    'orders' => [
        'value'  => number_format($todayOrders),
        'growth' => '+0%'
    ],
    'customers' => [
        'value'  => number_format($totalCustomers),
        'growth' => '+0%'
    ],
    'products' => [
        'value'  => number_format($totalProducts),
        'growth' => '+0'
    ]
];

// 6 đơn hàng gần đây
$recentOrdersRaw = db()->fetchAll("SELECT o.*, 
       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
       COALESCE(p.method, 'Tiền mặt') as payment_method
FROM orders o 
LEFT JOIN customers c ON o.customer_id = c.id
LEFT JOIN users u ON o.user_id = u.id
LEFT JOIN payments p ON o.id = p.order_id
ORDER BY o.created_at DESC LIMIT 6");

$recentOrders = [];
foreach ($recentOrdersRaw as $o) {
    $recentOrders[] = [
        'id'       => $o['id'],
        'code'     => '#ORD' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
        'customer' => $o['customer_name'],
        'type'     => ucfirst(str_replace('_', '-', $o['order_type'])),
        'total'    => money($o['final_amount']),
        'payment'  => format_payment_method($o['payment_method']),
        'status'   => ucfirst($o['status']),
        'time'     => date('H:i d/m', strtotime($o['created_at']))
    ];
}

// Cảnh báo tồn kho thực tế từ DB
$invAlertsRaw = db()->fetchAll("SELECT * FROM inventory WHERE quantity <= min_quantity ORDER BY quantity ASC LIMIT 5");
$inventoryAlerts = [];
if (!empty($invAlertsRaw)) {
    foreach ($invAlertsRaw as $ia) {
        $q = (float)$ia['quantity'];
        $m = (float)$ia['min_quantity'];
        $inventoryAlerts[] = [
            'name'   => $ia['ingredient_name'],
            'stock'  => number_format($q, 1) . ' ' . $ia['unit'],
            'min'    => number_format($m, 1) . ' ' . $ia['unit'],
            'status' => ($q <= 0) ? 'Hết hàng' : 'Sắp hết'
        ];
    }
} else {
    $inventoryAlerts = [
        ['name' => 'Trà đen', 'stock' => '1.2 kg', 'min' => '2.0 kg', 'status' => 'Sắp hết'],
        ['name' => 'Sữa tươi', 'stock' => '0 L', 'min' => '5.0 L', 'status' => 'Hết hàng']
    ];
}
