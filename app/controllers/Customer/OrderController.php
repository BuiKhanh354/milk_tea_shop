<?php

class OrderController {
    public function index() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        // Dummy data for now (since it was mocked in public/orders.php)
        $orders = [
            [
                'id' => 'VT001',
                'date' => '15/09/2026',
                'items' => 'Oolong Sữa Hạnh Nhân (x2), Hồng Trà Kem Phô Mai (x1)',
                'total' => '165.000đ',
                'status' => 'Đang chuẩn bị'
            ],
            [
                'id' => 'VT002',
                'date' => '12/09/2026',
                'items' => 'Matcha Latte (x1)',
                'total' => '60.000đ',
                'status' => 'Hoàn thành'
            ]
        ];

        require_once __DIR__ . '/../../views/customer/orders/index.php';
    }
}
