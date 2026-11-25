<?php
// admin/pages/payments/action.php — Quản lý thanh toán (OOP)
$action = get_param('action', '');
$paymentModel = new Payment();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') {
    $id = (int)post_param('id');
    $status = post_param('status');
    $transactionCode = post_param('transaction_code');

    if ($id && $status) {
        $paymentModel->updateStatus($id, $status, $transactionCode);
        set_flash('success', "Cập nhật trạng thái thanh toán thành công!");
    }
    redirect(admin_url('payments'));
}

$search = get_param('search', '');
$method = get_param('method', '');
$status = get_param('status', '');

$payments = $paymentModel->getFiltered($search, $method, $status, 50);

$stats = [
    'total_paid_amount'  => 0,
    'total_transactions' => count($payments),
    'cash_paid_amount'   => 0,
    'online_paid_amount' => 0,
    'pending_amount'     => 0,
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
