<?php
// pages/cart/action.php — Xử lý logic giỏ hàng (Thêm, Sửa số lượng, Xóa)
$action = get_param('action', 'view');

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $sizeId = isset($_POST['size_id']) ? (int)$_POST['size_id'] : null;
    $sugarId = isset($_POST['sugar_id']) ? (int)$_POST['sugar_id'] : null;
    $iceId = isset($_POST['ice_id']) ? (int)$_POST['ice_id'] : null;
    $toppings = isset($_POST['toppings']) && is_array($_POST['toppings']) ? array_map('intval', $_POST['toppings']) : array();

    if ($quantity < 1) $quantity = 1;

    $product = db()->fetchOne("SELECT * FROM products WHERE id = ? AND status = 1", array($productId));
    if ($product) {
        $size = null;
        if ($sizeId) {
            $size = db()->fetchOne("SELECT * FROM sizes WHERE id = ?", array($sizeId));
        }

        $sugar = null;
        if ($sugarId) {
            $sugar = db()->fetchOne("SELECT * FROM item_options WHERE id = ?", array($sugarId));
        }

        $ice = null;
        if ($iceId) {
            $ice = db()->fetchOne("SELECT * FROM item_options WHERE id = ?", array($iceId));
        }

        $selectedToppings = array();
        $toppingsPrice = 0;
        if (!empty($toppings)) {
            $inClause = implode(',', array_fill(0, count($toppings), '?'));
            $topRows = db()->fetchAll("SELECT * FROM toppings WHERE id IN ($inClause)", $toppings);
            foreach ($topRows as $tr) {
                $selectedToppings[] = array('id' => $tr['id'], 'name' => $tr['name'], 'price' => (float)$tr['price']);
                $toppingsPrice += (float)$tr['price'];
            }
        }

        $unitPrice = (float)$product['price'] + (float)($size['extra_price'] ?? 0) + $toppingsPrice;

        // Tạo itemKey duy nhất dựa theo các lựa chọn
        sort($toppings);
        $itemKey = md5($productId . '_' . ($sizeId ?? '') . '_' . ($sugarId ?? '') . '_' . ($iceId ?? '') . '_' . implode('-', $toppings));

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = array(
                'item_key'     => $itemKey,
                'product_id'   => $product['id'],
                'product_name' => $product['name'],
                'image'        => $product['image'],
                'base_price'   => (float)$product['price'],
                'size_name'    => $size['name'] ?? 'Tiêu chuẩn',
                'size_id'      => $sizeId,
                'sugar_name'   => $sugar['name'] ?? 'Tiêu chuẩn',
                'sugar_id'     => $sugarId,
                'ice_name'     => $ice['name'] ?? 'Tiêu chuẩn',
                'ice_id'       => $iceId,
                'toppings'     => $selectedToppings,
                'unit_price'   => $unitPrice,
                'quantity'     => $quantity,
            );
        }
    }

    redirect(url('cart'));
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemKey = get_param('item_key');
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (isset($_SESSION['cart'][$itemKey])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$itemKey]);
        } else {
            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
        }
    }
    redirect(url('cart'));
}

if ($action === 'remove') {
    $itemKey = get_param('item_key');
    if ($itemKey && isset($_SESSION['cart'][$itemKey])) {
        unset($_SESSION['cart'][$itemKey]);
    }
    redirect(url('cart'));
}

if ($action === 'clear') {
    unset($_SESSION['cart']);
    redirect(url('cart'));
}

$title = 'Giỏ hàng';
$cartItems = $_SESSION['cart'] ?? array();
$totalCartAmount = 0;
foreach ($cartItems as $item) {
    $totalCartAmount += $item['unit_price'] * $item['quantity'];
}

