<?php
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/User.php';

class DashboardController {
    
    public function index() {
        $pageTitle = 'Dashboard';
        $useChart = true; // Bật Chart.js ở trang này
        
        $orderModel = new Order();
        $productModel = new Product();
        $userModel = new User();

        $orderStats = $orderModel->getStats();
        $products = $productModel->getAll();
        
        $totalProducts = count($products);
        $totalCustomers = $userModel->countCustomers();
        
        // Format Currency
        $formattedRevenue = number_format($orderStats['total_revenue'], 0, ',', '.') . ' ₫';

        $stats = [
            'revenue' => [
                'value' => $formattedRevenue,
                'growth' => '+0%'
            ],
            'orders' => [
                'value' => number_format($orderStats['today_orders']),
                'growth' => '+0%'
            ],
            'customers' => [
                'value' => number_format($totalCustomers),
                'growth' => '+0%'
            ],
            'products' => [
                'value' => number_format($totalProducts),
                'growth' => '+0'
            ]
        ];

        // Format recent orders for view
        $recentOrdersRaw = $orderModel->getRecent(6);
        $recentOrders = [];
        foreach($recentOrdersRaw as $o) {
            $recentOrders[] = [
                'code' => '#ORD' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
                'customer' => $o['customer_name'],
                'type' => ucfirst(str_replace('_', '-', $o['order_type'])),
                'total' => number_format($o['final_amount'], 0, ',', '.') . ' ₫',
                'payment' => $o['payment_method'],
                'status' => ucfirst($o['status']),
                'time' => date('H:i d/m', strtotime($o['created_at']))
            ];
        }

        $inventoryAlerts = [
            ['name' => 'Trà đen', 'stock' => '1.2 kg', 'min' => '2 kg', 'status' => 'Sắp hết'],
            ['name' => 'Sữa tươi', 'stock' => '0 L', 'min' => '5 L', 'status' => 'Hết hàng']
        ];

        ob_start();
        require_once __DIR__ . '/../../views/admin/dashboard/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
