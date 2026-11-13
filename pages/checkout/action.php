<?php
// pages/checkout/action.php — Xử lý logic đặt hàng thanh toán
$cartItems = $_SESSION['cart'] ?? array();
if (empty($cartItems)) {
    redirect(url('products'));
}

$totalCartAmount = 0;
foreach ($cartItems as $item) {
    $totalCartAmount += $item['unit_price'] * $item['quantity'];
}

$title = 'Thanh toán đơn hàng';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $orderType = in_array($_POST['order_type'] ?? '', ['delivery', 'takeaway']) ? $_POST['order_type'] : 'takeaway';
    $paymentMethod = in_array($_POST['payment_method'] ?? '', ['cod', 'momo']) ? $_POST['payment_method'] : 'cod';
    $note = trim($_POST['note'] ?? '');

    if ($fullName === '') {
        $errors[] = 'Vui lòng nhập họ và tên người nhận.';
    }
    if ($phone === '') {
        $errors[] = 'Vui lòng nhập số điện thoại liên hệ.';
    }
    if ($orderType === 'delivery' && $address === '') {
        $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';
    }

    if (empty($errors)) {
        // Tìm hoặc tạo thông tin customer
        $customerId = $_SESSION['customer_id'] ?? null;
        if (!$customerId) {
            $existingCustomer = db()->fetchOne("SELECT id FROM customers WHERE phone = ?", array($phone));
            if ($existingCustomer) {
                $customerId = $existingCustomer['id'];
            } else {
                db()->execute("INSERT INTO customers (name, phone, address, created_at) VALUES (?, ?, ?, NOW())", array($fullName, $phone, $address));
                $customerId = db()->insertId();
            }
        }

        // Tạo order
        $orderNote = ($orderType === 'delivery' ? "Giao hàng tới: $address | " : "Lấy tại quán | ") . $note;
        db()->execute("INSERT INTO orders (customer_id, total_amount, discount, final_amount, order_type, status, note, created_at) VALUES (?, ?, 0, ?, ?, 'pending', ?, NOW())", 
            array($customerId, $totalCartAmount, $totalCartAmount, $orderType, $orderNote)
        );
        $orderId = db()->insertId();

        // Thêm chi tiết order_details và toppings
        foreach ($cartItems as $item) {
            $subtotal = $item['unit_price'] * $item['quantity'];
            db()->execute("INSERT INTO order_details (order_id, product_id, size_id, quantity, unit_price, subtotal, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                array($orderId, (int)$item['product_id'], !empty($item['size_id']) ? (int)$item['size_id'] : null, (int)$item['quantity'], (float)$item['unit_price'], (float)$subtotal)
            );
            $orderDetailId = db()->insertId();

            if (!empty($item['toppings'])) {
                foreach ($item['toppings'] as $top) {
                    db()->execute("INSERT INTO order_detail_toppings (order_detail_id, topping_id, price) VALUES (?, ?, ?)",
                        array($orderDetailId, (int)$top['id'], (float)$top['price'])
                    );
                }
            }
        }

        // Tạo bản ghi payments
        db()->execute("INSERT INTO payments (order_id, amount, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())",
            array($orderId, $totalCartAmount, $paymentMethod)
        );

        // Xóa giỏ hàng
        unset($_SESSION['cart']);

        // Chuyển tới trang thành công
        $_SESSION['order_success_id'] = $orderId;
        redirect(url('checkout', ['status' => 'success']));
    }
}

$isSuccess = (get_param('status') === 'success');
$successOrderId = $_SESSION['order_success_id'] ?? null;

