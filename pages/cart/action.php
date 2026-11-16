<?php
// pages/cart/action.php — Xử lý logic giỏ hàng (Thêm, Sửa số lượng, Xóa) chuẩn gốc hỗ trợ cả Form POST và Fetch/AJAX
$action = get_param('action', 'view');

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
          || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
          || isset($_POST['product_name'])
          || isset($_POST['base_price']);

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($quantity < 1) $quantity = 1;

    $product = db()->fetchOne("SELECT * FROM products WHERE id = ? AND status = 1", [$productId]);
    if ($product) {
        // Size
        $sizeId = isset($_POST['size_id']) ? (int)$_POST['size_id'] : null;
        $sizeName = $_POST['size'] ?? '';
        $sizePrice = isset($_POST['size_price']) ? (float)$_POST['size_price'] : 0;
        
        if ($sizeId) {
            $sizeRow = db()->fetchOne("SELECT * FROM sizes WHERE id = ?", [$sizeId]);
            if ($sizeRow) {
                $sizeName = $sizeRow['name'];
                $sizePrice = (float)$sizeRow['extra_price'];
            }
        } elseif (!empty($sizeName)) {
            $sizeRow = db()->fetchOne("SELECT * FROM sizes WHERE name = ? OR code = ?", [$sizeName, $sizeName]);
            if ($sizeRow) {
                $sizeId = (int)$sizeRow['id'];
                $sizePrice = (float)$sizeRow['extra_price'];
            }
        }

        // Sugar
        $sugarId = isset($_POST['sugar_id']) ? (int)$_POST['sugar_id'] : null;
        $sugarName = $_POST['sugar'] ?? '';
        if ($sugarId) {
            $sugarRow = db()->fetchOne("SELECT * FROM item_options WHERE id = ?", [$sugarId]);
            if ($sugarRow) $sugarName = $sugarRow['name'];
        }

        // Ice
        $iceId = isset($_POST['ice_id']) ? (int)$_POST['ice_id'] : null;
        $iceName = $_POST['ice'] ?? '';
        if ($iceId) {
            $iceRow = db()->fetchOne("SELECT * FROM item_options WHERE id = ?", [$iceId]);
            if ($iceRow) $iceName = $iceRow['name'];
        }

        // Toppings
        $selectedToppings = [];
        $toppingsPrice = 0;
        if (isset($_POST['toppings']) && is_array($_POST['toppings'])) {
            foreach ($_POST['toppings'] as $t) {
                if (is_array($t)) {
                    $tId = (int)($t['id'] ?? 0);
                    $tName = $t['name'] ?? '';
                    $tPrice = (float)($t['price'] ?? 0);
                    if ($tId > 0) {
                        $selectedToppings[] = ['id' => $tId, 'name' => $tName, 'price' => $tPrice];
                        $toppingsPrice += $tPrice;
                    }
                } elseif (is_numeric($t)) {
                    $tId = (int)$t;
                    $topRow = db()->fetchOne("SELECT * FROM toppings WHERE id = ?", [$tId]);
                    if ($topRow) {
                        $selectedToppings[] = ['id' => $topRow['id'], 'name' => $topRow['name'], 'price' => (float)$topRow['price']];
                        $toppingsPrice += (float)$topRow['price'];
                    }
                }
            }
        }

        $basePrice = (float)$product['price'];
        $unitPrice = $basePrice + $sizePrice + $toppingsPrice;

        // Unique item key
        $toppingIds = array_column($selectedToppings, 'id');
        sort($toppingIds);
        $itemKey = md5($productId . '_' . ($sizeName ?: ($sizeId ?? '')) . '_' . ($sugarName ?: ($sugarId ?? '')) . '_' . ($iceName ?: ($iceId ?? '')) . '_' . implode('-', $toppingIds));

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = [
                'item_key'     => $itemKey,
                'cart_id'      => $itemKey,
                'product_id'   => $product['id'],
                'product_name' => $product['name'],
                'name'         => $product['name'],
                'image'        => product_image($product['image']),
                'base_price'   => $basePrice,
                'size_name'    => $sizeName ?: 'Tiêu chuẩn',
                'size_id'      => $sizeId,
                'sugar_name'   => $sugarName ?: 'Tiêu chuẩn',
                'sugar_id'     => $sugarId,
                'ice_name'     => $iceName ?: 'Tiêu chuẩn',
                'ice_id'       => $iceId,
                'toppings'     => $selectedToppings,
                'unit_price'   => $unitPrice,
                'total_price'  => $unitPrice,
                'quantity'     => $quantity,
            ];
        }

        $cartCount = 0;
        foreach ($_SESSION['cart'] as $it) {
            $cartCount += $it['quantity'];
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success'    => true,
                'message'    => 'Đã thêm vào giỏ hàng',
                'cart_count' => $cartCount
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    redirect(url('cart'));
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemKey = get_param('item_key', $_POST['cart_id'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (isset($_SESSION['cart'][$itemKey])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$itemKey]);
        } else {
            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
        }
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true]);
            exit;
        }
    } else {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
            exit;
        }
    }
    redirect(url('cart'));
}

if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemKey = get_param('item_key', $_POST['cart_id'] ?? '');
    if ($itemKey && isset($_SESSION['cart'][$itemKey])) {
        unset($_SESSION['cart'][$itemKey]);
    }
    if ($isAjax) {
        $cartCount = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $it) {
                $cartCount += $it['quantity'];
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'cart_count' => $cartCount]);
        exit;
    }
    redirect(url('cart'));
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'cart_count' => 0]);
        exit;
    }
    redirect(url('cart'));
}

// Chuẩn bị dữ liệu hiển thị giỏ hàng
$title = 'Giỏ hàng của bạn';

$cartItems = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += ($item['unit_price'] ?? $item['total_price'] ?? 0) * ($item['quantity'] ?? 1);
}
$shippingFee = $subtotal > 0 ? 15000 : 0;
$total = $subtotal > 0 ? $subtotal + $shippingFee : 0;
