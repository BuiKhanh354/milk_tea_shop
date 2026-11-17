<?php
// pages/stores/action.php — Dữ liệu hệ thống cửa hàng VAA THÉ

$stores = [
    [
        'id' => 1,
        'name' => 'VAA THÉ - Premium Flagship',
        'address' => '123 Đường Sách, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
        'phone' => '0901 234 567',
        'open_time' => '07:30',
        'close_time' => '22:30',
        'status' => 'open',
        'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=800',
        'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi', 'Thanh toán QR', 'Chỗ đậu xe ô tô'],
        'map_url' => 'https://maps.google.com/?q=123+Đường+Sách,+Bến+Nghé,+Quận+1,+Hồ+Chí+Minh'
    ],
    [
        'id' => 2,
        'name' => 'VAA THÉ - Cơ sở 02',
        'address' => '45 Nguyễn Văn Cừ, Phường 4, Quận 5, TP. Hồ Chí Minh',
        'phone' => '0901 234 568',
        'open_time' => '08:00',
        'close_time' => '22:00',
        'status' => 'open',
        'image' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?auto=format&fit=crop&q=80&w=800',
        'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi', 'Thanh toán QR'],
        'map_url' => 'https://maps.google.com/?q=45+Nguyễn+Văn+Cừ,+Phường+4,+Quận+5,+Hồ+Chí+Minh'
    ],
    [
        'id' => 3,
        'name' => 'VAA THÉ - Hồ Tây',
        'address' => '88 Trích Sài, Quận Tây Hồ, Hà Nội',
        'phone' => '0901 234 569',
        'open_time' => '07:00',
        'close_time' => '23:00',
        'status' => 'closed',
        'image' => 'https://media-cdn-v2.laodong.vn/storage/newsportal/2025/3/8/1474031/Ho-Hoan-Kiem-3.jpeg',
        'amenities' => ['Wi-Fi', 'Điều hòa', 'Chỗ ngồi view hồ', 'Thanh toán QR'],
        'map_url' => 'https://maps.google.com/?q=88+Trích+Sài,+Tây+Hồ,+Hà+Nội'
    ]
];

$storeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$store = null;
if ($storeId > 0) {
    foreach ($stores as $s) {
        if ($s['id'] === $storeId) {
            $store = $s;
            break;
        }
    }
}

if ($store) {
    $title = $store['name'] . ' - VAA THÉ';
} else {
    $title = 'Hệ thống Cửa hàng - VAA THÉ';
}
