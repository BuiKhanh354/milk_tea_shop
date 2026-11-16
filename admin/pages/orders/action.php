<?php
// admin/pages/orders/action.php — Quản lý đơn hàng chuẩn gốc
$action = get_param('action', '');
$orderId = (int)get_param('id', 0);
$userId = (int)($_SESSION['user_id'] ?? 1);

// Xử lý Cập nhật trạng thái
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($requestMethod === 'POST' && in_array($action, ['update_status', 'update-status'])) {
    $id = (int)post_param('id', $orderId);
    $status = post_param('status');

    if ($id && $status) {
        $oldOrder = db()->fetchOne("SELECT * FROM orders WHERE id = ?", [$id]);
        if ($oldOrder) {
            db()->execute("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);

            // Gán vào ca làm việc hiện tại nếu chưa có
            if (empty($oldOrder['shift_session_id'])) {
                $openSession = db()->fetchOne("SELECT id FROM shift_sessions WHERE status = 'open' ORDER BY opened_at DESC LIMIT 1");
                if ($openSession) {
                    db()->execute("UPDATE orders SET shift_session_id = ? WHERE id = ?", [$openSession['id'], $id]);
                }
            }

            // Nếu hoàn thành đơn: cập nhật thanh toán và trừ kho nguyên liệu
            if ($status === 'completed' && $oldOrder['status'] !== 'completed') {
                db()->execute("UPDATE payments SET status = 'paid' WHERE order_id = ?", [$id]);

                // Trừ tồn kho theo công thức
                $items = db()->fetchAll("SELECT * FROM order_details WHERE order_id = ?", [$id]);
                foreach ($items as $item) {
                    $pid = (int)$item['product_id'];
                    $sid = (int)($item['size_id'] ?? 0);
                    $qty = (int)$item['quantity'];

                    $recipes = db()->fetchAll("SELECT * FROM product_ingredients WHERE product_id = ? AND (size_id = ? OR size_id = 0)", [$pid, $sid]);
                    foreach ($recipes as $rc) {
                        $deductQty = (float)$rc['quantity'] * $qty;
                        db()->execute("UPDATE inventory SET quantity = GREATEST(0, quantity - ?) WHERE id = ?", [$deductQty, $rc['inventory_id']]);
                        db()->execute("INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, note) VALUES (?, ?, 'USAGE', ?, ?)", 
                                      [$rc['inventory_id'], $userId, $deductQty, "Trừ kho tự động theo đơn #ORD{$id}"]);
                    }
                }
            }
            set_flash('success', "Cập nhật trạng thái đơn #ORD{$id} thành công!");
        }
    }

    if (post_param('redirect') === 'orders') {
        redirect(admin_url('orders'));
    } else {
        redirect(admin_url('orders', ['action' => 'show', 'id' => $id]));
    }
}

// Xử lý Tạo đơn hàng mới từ POS
if ($requestMethod === 'POST' && in_array($action, ['store', 'create'])) {
    $customerName = post_param('customer_name', 'Khách lẻ');
    $orderType = post_param('order_type', 'takeaway');
    $tableId = (int)post_param('table_id', 0);
    $paymentMethod = post_param('payment_method', 'cash');
    $itemsJson = post_param('items', '[]');
    $items = json_decode($itemsJson, true);

    if (empty($items)) {
        set_flash('error', "Giỏ hàng không được để trống!");
        redirect(admin_url('orders', ['action' => 'create']));
    }

    $totalAmount = 0;
    foreach ($items as $item) {
        $totalAmount += (float)$item['price'] * (int)$item['quantity'];
    }

    $openSession = db()->fetchOne("SELECT id FROM shift_sessions WHERE status = 'open' ORDER BY opened_at DESC LIMIT 1");
    $shiftSessionId = $openSession ? (int)$openSession['id'] : null;
    $initialStatus = ($orderType === 'takeaway') ? 'completed' : 'pending';

    db()->execute("INSERT INTO orders (user_id, status, total_amount, final_amount, order_type, table_id, shift_session_id, note) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                   [$userId, $initialStatus, $totalAmount, $totalAmount, $orderType, $tableId ?: null, $shiftSessionId, "Khách: " . $customerName]);
    $newOrderId = db()->insertId();

    $payStatus = ($initialStatus === 'completed') ? 'paid' : 'pending';
    db()->execute("INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, ?)", 
                  [$newOrderId, $paymentMethod, $totalAmount, $payStatus]);

    foreach ($items as $item) {
        $pid = (int)($item['product_id'] ?? $item['id']);
        $sizeId = !empty($item['size_id']) ? (int)$item['size_id'] : null;
        $qty = (int)$item['quantity'];
        $price = (float)$item['price'];
        $subtotal = $price * $qty;
        $sugar = (int)($item['sugar_level'] ?? 100);
        $ice = (int)($item['ice_level'] ?? 100);
        $temp = $item['temperature'] ?? 'cold';
        $note = ($temp === 'hot') ? 'Dùng nóng' : 'Dùng lạnh';

        db()->execute("INSERT INTO order_details (order_id, product_id, size_id, quantity, unit_price, subtotal, sugar_level, ice_level, note) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                       [$newOrderId, $pid, $sizeId, $qty, $price, $subtotal, $sugar, $ice, $note]);
        $orderDetailId = db()->insertId();

        if (!empty($item['toppings']) && is_array($item['toppings'])) {
            foreach ($item['toppings'] as $t) {
                db()->execute("INSERT INTO order_detail_toppings (order_detail_id, topping_id, quantity, price) VALUES (?, ?, 1, ?)", 
                              [$orderDetailId, (int)$t['id'], (float)$t['price']]);
            }
        }

        // Nếu hoàn thành ngay: trừ tồn kho
        if ($initialStatus === 'completed') {
            $recipes = db()->fetchAll("SELECT * FROM product_ingredients WHERE product_id = ? AND (size_id = ? OR size_id = 0)", [$pid, $sizeId ?: 0]);
            foreach ($recipes as $rc) {
                $deductQty = (float)$rc['quantity'] * $qty;
                db()->execute("UPDATE inventory SET quantity = GREATEST(0, quantity - ?) WHERE id = ?", [$deductQty, $rc['inventory_id']]);
                db()->execute("INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, note) VALUES (?, ?, 'USAGE', ?, ?)", 
                              [$rc['inventory_id'], $userId, $deductQty, "Trừ kho tự động theo đơn POS #ORD{$newOrderId}"]);
            }
        }
    }

    if ($orderType === 'dine_in' && $tableId > 0) {
        db()->execute("UPDATE tables SET status = 'occupied' WHERE id = ?", [$tableId]);
    }

    set_flash('success', "Tạo đơn hàng #ORD{$newOrderId} thành công!");
    redirect(admin_url('orders', ['action' => 'show', 'id' => $newOrderId]));
}

// Giao diện POS / Tạo đơn hàng
if ($action === 'create' || get_param('page') === 'order-create') {
    $products = db()->fetchAll("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 1 ORDER BY p.name ASC");
    $sizes = db()->fetchAll("SELECT * FROM sizes WHERE status = 1 ORDER BY extra_price ASC");
    $toppings = db()->fetchAll("SELECT * FROM toppings WHERE status = 1 ORDER BY name ASC");
    $tables = db()->fetchAll("SELECT * FROM tables ORDER BY table_number ASC");

    $posOpenSession = db()->fetchOne("SELECT s.*, sh.name as shift_name FROM shift_sessions s LEFT JOIN shifts sh ON s.shift_id = sh.id WHERE s.status = 'open' ORDER BY s.opened_at DESC LIMIT 1");
    $posTodayShift = null;
    if (!$posOpenSession) {
        $today = date('Y-m-d');
        $posTodayShift = db()->fetchOne("SELECT es.*, s.name as shift_name FROM employee_shifts es JOIN shifts s ON es.shift_id = s.id WHERE es.employee_id = ? AND es.work_date = ? LIMIT 1", [$userId, $today]);
    }

    $pageTitle = 'Tạo đơn hàng mới (POS)';
    return;
}

// Giao diện Chi tiết đơn hàng
if ($action === 'show' || $action === 'detail' || get_param('page') === 'order-detail') {
    $id = (int)get_param('id', $orderId);
    $order = db()->fetchOne("SELECT o.*, 
        c.full_name as customer_name, c.phone as customer_phone, c.address as customer_address,
        u.full_name as user_name, u.phone as user_phone,
        p.method as payment_method,
        t.table_number
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id 
    LEFT JOIN users u ON o.user_id = u.id 
    LEFT JOIN payments p ON o.id = p.order_id 
    LEFT JOIN tables t ON o.table_id = t.id 
    WHERE o.id = ?", [$id]);

    if (!$order) {
        redirect(admin_url('orders'));
    }

    $items = db()->fetchAll("SELECT od.*, p.name as product_name, p.image as product_image, s.name as size_name
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        LEFT JOIN sizes s ON od.size_id = s.id
        WHERE od.order_id = ?", [$id]);

    foreach ($items as &$item) {
        $toppingRows = db()->fetchAll("SELECT t.name FROM order_detail_toppings odt JOIN toppings t ON odt.topping_id = t.id WHERE odt.order_detail_id = ?", [$item['id']]);
        $item['toppings'] = implode(', ', array_column($toppingRows, 'name'));
    }
    $order['items'] = $items;
    $pageTitle = 'Chi tiết đơn hàng #ORD' . str_pad($order['id'], 3, '0', STR_PAD_LEFT);
    return;
}

// Giao diện Danh sách đơn hàng
$page = 1;
if (get_param('p') !== '') {
    $page = max(1, (int)get_param('p'));
}
$limit = 10;
$offset = ($page - 1) * $limit;

$search = get_param('search', '');
$status = get_param('status', '');

$conditions = [];
$params = [];

if ($search !== '') {
    $cleanId = ltrim($search, '#ORDord0');
    $conditions[] = "(o.id = ? OR c.full_name LIKE ? OR u.full_name LIKE ?)";
    $params[] = is_numeric($cleanId) ? (int)$cleanId : -1;
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if ($status !== '') {
    $conditions[] = "o.status = ?";
    $params[] = $status;
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

$countRow = db()->fetchOne("SELECT COUNT(*) as total FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id
    LEFT JOIN users u ON o.user_id = u.id 
    $whereClause", $params);
$total = (int)($countRow['total'] ?? 0);
$totalPages = ceil($total / $limit);

$listParams = array_merge($params, [$limit, $offset]);
$orderPage = $page;
$ordersRaw = db()->fetchAll("SELECT o.*, 
       COALESCE(c.full_name, u.full_name, 'Khách vãng lai') as customer_name,
       COALESCE(p.method, 'Tiền mặt') as payment_method
FROM orders o 
LEFT JOIN customers c ON o.customer_id = c.id
LEFT JOIN users u ON o.user_id = u.id
LEFT JOIN payments p ON o.id = p.order_id
$whereClause
ORDER BY o.created_at DESC
LIMIT ? OFFSET ?", $listParams);

$orders = [];
foreach ($ordersRaw as $o) {
    $orders[] = [
        'id'       => $o['id'],
        'code'     => '#ORD' . str_pad($o['id'], 3, '0', STR_PAD_LEFT),
        'customer' => $o['customer_name'],
        'type'     => ucfirst(str_replace('_', '-', $o['order_type'])),
        'total'    => money($o['final_amount']),
        'payment'  => format_payment_method($o['payment_method']),
        'status'   => ucfirst($o['status']),
        'date'     => date('d/m/Y H:i', strtotime($o['created_at']))
    ];
}

$currentPage = $page;
$pageTitle = 'Quản lý đơn hàng';
