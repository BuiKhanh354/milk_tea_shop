<?php
require_once __DIR__ . '/../../models/Order.php';

class DashboardController {
    
    public function index() {
        $pageTitle = 'Staff Dashboard - Xử lý đơn hàng';
        
        $orderModel = new Order();
        $allOrdersRaw = $orderModel->getAll(); // In production, we'd have a specific method for this
        
        $orders = [];
        $stats = [
            'pending' => 0,
            'preparing' => 0,
            'ready' => 0,
            'completed' => 0
        ];

        foreach($allOrdersRaw as $o) {
            $status = $o['status'];
            
            // Count stats for today
            if (date('Y-m-d', strtotime($o['created_at'])) === date('Y-m-d')) {
                if (isset($stats[$status])) {
                    $stats[$status]++;
                }
            }

            // Only show active orders in the main list
            if (in_array($status, ['pending', 'confirmed', 'preparing', 'ready'])) {
                $orders[] = [
                    'id' => $o['id'],
                    'code' => '#ORD' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
                    'customer' => $o['customer_name'],
                    'type' => ucfirst(str_replace('_', '-', $o['order_type'])),
                    'total' => number_format($o['final_amount'], 0, ',', '.') . ' ₫',
                    'status' => $status,
                    'time' => date('H:i', strtotime($o['created_at']))
                ];
            }
        }

        ob_start();
        require_once __DIR__ . '/../../views/staff/dashboard/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
