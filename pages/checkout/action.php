<?php
// pages/checkout/action.php — Xử lý logic đặt hàng thanh toán chuẩn VAA THÉ
$action = get_param('action', 'view');
$status = get_param('status', '');
$orderId = (int)get_param('order_id', $_SESSION['order_success_id'] ?? 0);

// Nếu đang xem trang đặt hàng thành công
if ($status === 'success' || $action === 'success') {
    $title = 'Đặt hàng thành công - VAA THÉ';
    $isSuccess = true;
    $successOrderId = $orderId;
    return;
}

$isSuccess = false;
$cart_items = $_SESSION['cart'] ?? [];
$cartItems = $cart_items;

if (empty($cart_items)) {
    redirect(url('cart'));
}

$subtotal = 0;
foreach ($cart_items as $item) {
    $price = (float)($item['total_price'] ?? ($item['unit_price'] ?? 0));
    $qty = (int)($item['quantity'] ?? 1);
    $subtotal += $price * $qty;
}

$shipping = $subtotal > 0 ? 15000 : 0;
$total = $subtotal > 0 ? ($subtotal + $shipping) : 0;
$totalCartAmount = $total;

$customer = null;
if (isset($_SESSION['customer_id'])) {
    $customer = db()->fetchOne("SELECT * FROM customers WHERE id = ?", [(int)$_SESSION['customer_id']]);
}

$title = 'Thanh toán - VAA THÉ';
$errors = [];

// Xử lý gửi đơn hàng (Process Checkout)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' || $action === 'process') {
    $name = trim($_POST['name'] ?? ($_POST['full_name'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? 'cash');

    if ($name === '') {
        $errors[] = 'Vui lòng nhập họ và tên của bạn.';
    }
    if ($phone === '') {
        $errors[] = 'Vui lòng nhập số điện thoại nhận hàng.';
    }
    if ($address === '') {
        $errors[] = 'Vui lòng nhập địa chỉ giao hàng chi tiết.';
    }

    if (empty($errors)) {
        try {
            // 1. Xác định hoặc tạo khách hàng
            $customerId = $_SESSION['customer_id'] ?? null;
            if ($customerId) {
                // Cập nhật địa chỉ hoặc SĐT nếu khách có thay đổi
                db()->execute("UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?", [$name, $phone, $address, $customerId]);
            } else {
                $existing = db()->fetchOne("SELECT id FROM customers WHERE phone = ?", [$phone]);
                if ($existing) {
                    $customerId = (int)$existing['id'];
                    db()->execute("UPDATE customers SET full_name = ?, address = ? WHERE id = ?", [$name, $address, $customerId]);
                } else {
                    db()->execute("INSERT INTO customers (full_name, phone, address, created_at) VALUES (?, ?, ?, NOW())", [$name, $phone, $address]);
                    $customerId = db()->insertId();
                }
            }

            // 2. Tự động liên kết ca làm việc đang mở nếu có
            $activeSession = db()->fetchOne("SELECT id FROM shift_sessions WHERE status = 'open' ORDER BY id DESC LIMIT 1");
            $shiftSessionId = $activeSession ? (int)$activeSession['id'] : null;

            // 3. Tạo đơn hàng (Orders)
            $orderType = 'delivery';
            $sqlOrder = "INSERT INTO orders (customer_id, status, total_amount, discount, final_amount, order_type, shift_session_id, note, created_at) 
                         VALUES (?, 'pending', ?, 0, ?, ?, ?, ?, NOW())";
            db()->execute($sqlOrder, [$customerId, $total, $total, $orderType, $shiftSessionId, $note]);
            $newOrderId = db()->insertId();

            // 4. Tạo thanh toán (Payments)
            $sqlPayment = "INSERT INTO payments (order_id, method, amount, status, created_at) VALUES (?, ?, ?, 'pending', NOW())";
            db()->execute($sqlPayment, [$newOrderId, $paymentMethod, $total]);

            // 5. Chuẩn bị bảng ánh xạ Size
            $sizesList = db()->fetchAll("SELECT id, name FROM sizes");
            $sizeMap = [];
            foreach ($sizesList as $s) {
                $sizeMap[strtoupper(trim($s['name']))] = (int)$s['id'];
            }

            // 6. Thêm chi tiết đơn hàng (Order Details & Toppings)
            foreach ($cart_items as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                if ($productId <= 0) continue;

                $sizeId = null;
                if (!empty($item['size_id']) && is_numeric($item['size_id'])) {
                    $sizeId = (int)$item['size_id'];
                } elseif (!empty($item['size'])) {
                    if (is_numeric($item['size'])) {
                        $sizeId = (int)$item['size'];
                    } else {
                        $sizeKey = strtoupper(trim($item['size']));
                        $sizeId = $sizeMap[$sizeKey] ?? null;
                    }
                }

                $quantity = max(1, (int)($item['quantity'] ?? 1));
                $unitPrice = (float)($item['total_price'] ?? ($item['unit_price'] ?? 0));
                $lineSubtotal = $unitPrice * $quantity;

                // Lấy lượng đường / đá dạng số
                $sugarLevel = null;
                if (isset($item['sugar']) && is_numeric($item['sugar'])) {
                    $sugarLevel = (int)$item['sugar'];
                }

                $iceLevel = null;
                if (isset($item['ice']) && is_numeric($item['ice'])) {
                    $iceLevel = (int)$item['ice'];
                }

                $itemNote = '';
                if (!empty($item['temperature']) && $item['temperature'] === 'hot') {
                    $itemNote = 'Uống nóng';
                }

                $sqlDetail = "INSERT INTO order_details (order_id, product_id, size_id, quantity, unit_price, subtotal, sugar_level, ice_level, note, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                db()->execute($sqlDetail, [$newOrderId, $productId, $sizeId, $quantity, $unitPrice, $lineSubtotal, $sugarLevel, $iceLevel, $itemNote]);
                $detailId = db()->insertId();

                // Toppings
                if (!empty($item['toppings']) && is_array($item['toppings'])) {
                    foreach ($item['toppings'] as $t) {
                        $tId = is_array($t) ? (int)($t['id'] ?? 0) : (int)$t;
                        $tPrice = is_array($t) ? (float)($t['price'] ?? 0) : 0;
                        if ($tId > 0) {
                            if ($tPrice <= 0) {
                                $topRow = db()->fetchOne("SELECT price FROM toppings WHERE id = ?", [$tId]);
                                $tPrice = $topRow ? (float)$topRow['price'] : 0;
                            }
                            db()->execute("INSERT INTO order_detail_toppings (order_detail_id, topping_id, quantity, price) VALUES (?, ?, 1, ?)", [$detailId, $tId, $tPrice]);
                        }
                    }
                }
            }

            // 7. Xoá giỏ hàng sau khi đặt thành công
            unset($_SESSION['cart']);
            $_SESSION['order_success_id'] = $newOrderId;

            // 8. Chuyển hướng tới trang thông báo thành công
            redirect(url('checkout', ['status' => 'success', 'order_id' => $newOrderId]));
        } catch (Exception $e) {
            $errors[] = 'Đã xảy ra lỗi khi tạo đơn hàng: ' . $e->getMessage();
        }
    }
}
