<?php
require_once __DIR__ . '/../../models/Order.php';

class OrderController {
    
    public function index() {
        $pageTitle = 'Quản lý đơn hàng';
        
        $orderModel = new Order();
        $ordersRaw = $orderModel->getAll();
        
        $orders = [];
        foreach($ordersRaw as $o) {
            $orders[] = [
                'code' => '#ORD' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
                'customer' => $o['customer_name'],
                'type' => ucfirst(str_replace('_', '-', $o['order_type'])),
                'total' => number_format($o['final_amount'], 0, ',', '.') . ' ₫',
                'payment' => $o['payment_method'],
                'status' => ucfirst($o['status']),
                'date' => date('d/m/Y H:i', strtotime($o['created_at']))
            ];
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/orders/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function show() {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: admin.php?route=orders');
            exit;
        }

        $pageTitle = 'Chi tiết đơn hàng';
        
        $orderModel = new Order();
        $order = $orderModel->findByIdWithDetails($id);

        if (!$order) {
            header('Location: admin.php?route=orders');
            exit;
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/orders/show.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if ($id && $status) {
                $orderModel = new Order();
                $oldOrder = $orderModel->findByIdWithDetails($id);
                
                if ($orderModel->updateStatus($id, $status)) {
                    if ($status === 'completed' && $oldOrder && $oldOrder['status'] !== 'completed') {
                        require_once __DIR__ . '/../../services/InventoryService.php';
                        $invService = new InventoryService();
                        $invService->deductForOrder($id);
                    }
                }
            }
            
            // Nếu có param redirect (từ staff_dashboard)
            if (isset($_POST['redirect']) && $_POST['redirect'] === 'staff') {
                header('Location: admin.php?route=staff_dashboard');
            } else {
                header('Location: admin.php?route=orders&action=show&id=' . $id);
            }
            exit;
        }
    }
}
