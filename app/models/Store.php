<?php

class Store {
    // Mock data based on requirements
    private $mockStores = [
        [
            'id' => 1,
            'name' => 'VAA THÉ - Premium Flagship',
            'address' => '123 Đường Sách, Quận 1, TP. Hồ Chí Minh',
            'phone' => '0901 234 567',
            'open_time' => '07:30',
            'close_time' => '22:30',
            'status' => 'open', // open, closed
            'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=800',
            'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi', 'Thanh toán QR', 'Chỗ đậu xe ô tô']
        ],
        [
            'id' => 2,
            'name' => 'VAA THÉ - Cơ sở 02',
            'address' => '45 Nguyễn Văn Cừ, Quận 5, TP. Hồ Chí Minh',
            'phone' => '0901 234 568',
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'open',
            'image' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?auto=format&fit=crop&q=80&w=800',
            'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi', 'Thanh toán QR']
        ],
        [
            'id' => 3,
            'name' => 'VAA THÉ - Hồ Tây',
            'address' => '88 Trích Sài, Quận Tây Hồ, Hà Nội',
            'phone' => '0901 234 569',
            'open_time' => '07:00',
            'close_time' => '23:00',
            'status' => 'closed',
            'image' => 'https://images.unsplash.com/photo-1559925393-8be0aaff477c?auto=format&fit=crop&q=80&w=800',
            'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi view hồ', 'Thanh toán QR']
        ]
    ];

    public function getAll() {
        return $this->mockStores;
    }

    public function findById($id) {
        foreach ($this->mockStores as $store) {
            if ($store['id'] == $id) {
                return $store;
            }
        }
        return null;
    }
}
