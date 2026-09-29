<?php

class CartController {
    public function index() {
        // Lấy giỏ hàng từ session (mock for now)
        $cart_items = [
            [
                'id' => 1,
                'name' => 'Oolong Sữa Hạnh Nhân',
                'price' => 55000,
                'quantity' => 2,
                'image' => 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?auto=format&fit=crop&q=80&w=150'
            ],
            [
                'id' => 2,
                'name' => 'Hồng Trà Kem Phô Mai',
                'price' => 55000,
                'quantity' => 1,
                'image' => 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=150'
            ]
        ];

        $subtotal = 0;
        foreach($cart_items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping = 15000;
        $total = $subtotal + $shipping;

        require_once __DIR__ . '/../../views/customer/cart/index.php';
    }
}
