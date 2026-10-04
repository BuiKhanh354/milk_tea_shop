<?php

class OrderController {
    public function index() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        require_once __DIR__ . '/../../models/Order.php';
        $orderModel = new Order();
        $orders = $orderModel->getCustomerOrders($_SESSION['customer_id']);

        require_once __DIR__ . '/../../views/customer/orders/index.php';
    }

    public function detail() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        if (!isset($_GET['id'])) {
            header("Location: index.php?route=orders");
            exit;
        }

        $id = (int)$_GET['id'];
        require_once __DIR__ . '/../../models/Order.php';
        $orderModel = new Order();
        $rawOrder = $orderModel->findByIdWithDetails($id);

        if (!$rawOrder || $rawOrder['customer_id'] != $_SESSION['customer_id']) {
            header("Location: index.php?route=orders");
            exit;
        }

        // Format order for view
        $order = [
            'id' => 'VT' . str_pad($rawOrder['id'], 3, '0', STR_PAD_LEFT),
            'status' => $rawOrder['status'],
            'date' => date('d/m/Y H:i', strtotime($rawOrder['created_at'])),
            'payment_status' => $rawOrder['payment_method'] === 'cash' ? 'Thanh toán khi nhận hàng' : 'Đã thanh toán (' . strtoupper($rawOrder['payment_method']) . ')',
            'subtotal' => number_format($rawOrder['total_amount'] - 15000, 0, ',', '.') . 'đ',
            'discount' => '0đ',
            'shipping' => '15.000đ',
            'total' => number_format($rawOrder['final_amount'], 0, ',', '.') . 'đ',
            'items' => []
        ];

        foreach ($rawOrder['items'] as $item) {
            $order['items'][] = [
                'name' => $item['product_name'],
                'size' => $item['size_name'] ?: 'M',
                'sugar' => '100%',
                'ice' => '100%',
                'toppings' => empty($item['toppings']) ? 'Không' : $item['toppings'],
                'quantity' => $item['quantity'],
                'price' => number_format($item['unit_price'], 0, ',', '.') . 'đ',
                'image' => $item['product_image'] ?: 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?auto=format&fit=crop&q=80&w=150'
            ];
        }

        require_once __DIR__ . '/../../views/customer/orders/detail.php';
    }
}
