<?php
// pages/orders/action.php — Lịch sử & Chi tiết đơn hàng của khách
if (!isset($_SESSION['customer_id'])) {
    redirect(url('login'));
}

$customerId = (int)$_SESSION['customer_id'];
$action = get_param('action', '');

if ($action === 'detail') {
    $id = (int)get_param('id', 0);
    $rawOrder = db()->fetchOne("SELECT o.*, p.method as payment_method, p.status as payment_status 
        FROM orders o 
        LEFT JOIN payments p ON p.order_id = o.id 
        WHERE o.id = ? AND o.customer_id = ?", [$id, $customerId]);

    if (!$rawOrder) {
        redirect(url('orders'));
    }

    $rawItems = db()->fetchAll("SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        LEFT JOIN sizes s ON od.size_id = s.id
        WHERE od.order_id = ?", [$id]);

    $items = [];
    foreach ($rawItems as $item) {
        $toppings = db()->fetchAll("SELECT t.name FROM order_detail_toppings odt JOIN toppings t ON odt.topping_id = t.id WHERE odt.order_detail_id = ?", [$item['id']]);
        $items[] = [
            'name'     => $item['product_name'],
            'size'     => $item['size_name'] ?: 'M',
            'sugar'    => ($item['sugar_level'] ?? 100) . '%',
            'ice'      => ($item['ice_level'] ?? 100) . '%',
            'toppings' => empty($toppings) ? 'Không' : implode(', ', array_column($toppings, 'name')),
            'quantity' => (int)$item['quantity'],
            'price'    => money($item['unit_price']),
            'image'    => product_image($item['product_image'])
        ];
    }

    $order = [
        'id'             => 'VT' . str_pad($rawOrder['id'], 3, '0', STR_PAD_LEFT),
        'raw_id'         => $rawOrder['id'],
        'status'         => $rawOrder['status'],
        'date'           => date('d/m/Y H:i', strtotime($rawOrder['created_at'])),
        'payment_status' => ($rawOrder['payment_method'] === 'cash' || empty($rawOrder['payment_method'])) ? 'Thanh toán khi nhận hàng' : 'Đã thanh toán (' . strtoupper($rawOrder['payment_method']) . ')',
        'subtotal'       => money(max(0, (float)$rawOrder['total_amount'] - 15000)),
        'discount'       => '0đ',
        'shipping'       => '15.000đ',
        'total'          => money($rawOrder['final_amount']),
        'items'          => $items
    ];

    $title = 'Chi tiết đơn hàng #' . $order['id'];
    return;
}

$ordersRaw = db()->fetchAll("SELECT o.id, o.created_at as date, o.final_amount as total, o.status 
    FROM orders o 
    WHERE o.customer_id = ? 
    ORDER BY o.created_at DESC", [$customerId]);

$orders = [];
foreach ($ordersRaw as $o) {
    $items = db()->fetchAll("SELECT p.name, od.quantity 
        FROM order_details od 
        JOIN products p ON od.product_id = p.id 
        WHERE od.order_id = ?", [$o['id']]);

    $itemsSummary = [];
    foreach ($items as $it) {
        $itemsSummary[] = $it['name'] . ' (x' . $it['quantity'] . ')';
    }

    $statusLabel = 'Đang xử lý';
    if ($o['status'] === 'completed') $statusLabel = 'Hoàn thành';
    elseif ($o['status'] === 'cancelled') $statusLabel = 'Đã hủy';
    elseif ($o['status'] === 'preparing') $statusLabel = 'Đang pha chế';
    elseif ($o['status'] === 'confirmed') $statusLabel = 'Đã xác nhận';
    elseif ($o['status'] === 'ready') $statusLabel = 'Sẵn sàng giao';

    $orders[] = [
        'id'     => 'VT' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
        'raw_id' => $o['id'],
        'date'   => date('d/m/Y H:i', strtotime($o['date'])),
        'total'  => money($o['total']),
        'status' => $statusLabel,
        'items'  => implode(', ', $itemsSummary) ?: 'Sản phẩm trà sữa'
    ];
}

$title = 'Đơn đã mua';
