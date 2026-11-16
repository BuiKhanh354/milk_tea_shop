<?php
// admin/pages/payments/action.php — Quản lý thanh toán
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') {
    $id = (int)post_param('id');
    $status = post_param('status');
    $transactionCode = post_param('transaction_code');

    if ($id && $status) {
        db()->execute("UPDATE payments SET status = ?, transaction_code = ? WHERE id = ?", [$status, $transactionCode, $id]);
        if ($status === 'paid') {
            $payment = db()->fetchOne("SELECT order_id FROM payments WHERE id = ?", [$id]);
            if ($payment && !empty($payment['order_id'])) {
                db()->execute("UPDATE orders SET status = 'confirmed' WHERE id = ? AND status = 'pending'", [$payment['order_id']]);
            }
        }
        set_flash('success', "Cập nhật trạng thái thanh toán thành công!");
    }
    redirect(admin_url('payments'));
}

$search = get_param('search', '');
$method = get_param('method', '');
$status = get_param('status', '');

$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = "(p.transaction_code LIKE ? OR CONCAT('ORD', o.id) LIKE ? OR o.id LIKE ? OR c.full_name LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($method !== '') {
    $conditions[] = "p.method = ?";
    $params[] = $method;
}

if ($status !== '') {
    $conditions[] = "p.status = ?";
    $params[] = $status;
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

$payments = db()->fetchAll("SELECT p.*, o.id as order_id, o.status as order_status, c.full_name as customer_name 
    FROM payments p 
    LEFT JOIN orders o ON p.order_id = o.id 
    LEFT JOIN customers c ON o.customer_id = c.id 
    $whereClause 
    ORDER BY p.created_at DESC LIMIT 50", $params);

// Stats
$stats = [
    'total_paid_amount' => 0,
    'total_transactions' => count($payments),
    'cash_paid_amount' => 0,
    'online_paid_amount' => 0,
    'pending_amount' => 0
];

foreach ($payments as $p) {
    $amt = (float)$p['amount'];
    if ($p['status'] === 'paid') {
        $stats['total_paid_amount'] += $amt;
        if (in_array(strtolower($p['method']), ['cash', 'tiền mặt'])) {
            $stats['cash_paid_amount'] += $amt;
        } else {
            $stats['online_paid_amount'] += $amt;
        }
    } elseif ($p['status'] === 'pending') {
        $stats['pending_amount'] += $amt;
    }
}

$pageTitle = 'Quản lý thanh toán';

