<?php
// admin/pages/inventory/action.php — Quản lý kho nguyên liệu
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    // Chỉ Admin mới có quyền thêm/sửa/xóa hoặc nhập kho (Staff chỉ có quyền xuất kho)
    if (in_array($action, ['store', 'update', 'delete', 'storeImport']) && !$isAdmin) {
        set_flash('error', 'Nhân viên chỉ có quyền xuất kho, không có quyền nhập kho!');
        redirect(admin_url('inventory'));
    }
    // Thêm nguyên liệu mới
    if ($action === 'store') {
        $name = post_param('name');
        $unit = post_param('unit');
        $minQty = (float)post_param('min_quantity', 0);
        $price = (float)post_param('price', 0);
        $qty = (float)post_param('quantity', 0);

        if (!empty($name) && !empty($unit)) {
            try {
                db()->execute("INSERT INTO inventory (ingredient_name, unit, quantity, min_quantity, price, status) VALUES (?, ?, ?, ?, ?, 1)", 
                              [$name, $unit, $qty, $minQty, $price]);
                $invId = db()->insertId();
                if ($qty > 0) {
                    db()->execute("INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'IMPORT', ?, ?, 'Khởi tạo tồn ban đầu')", 
                                  [$invId, $userId, $qty, $price]);
                }
                set_flash('success', "Thêm nguyên liệu thành công!");
            } catch (Exception $e) {
                set_flash('error', "Có lỗi xảy ra khi thêm nguyên liệu!");
            }
        }
        redirect(admin_url('inventory'));
    }

    // Sửa nguyên liệu
    if ($action === 'update') {
        $id = (int)post_param('id');
        $name = post_param('name');
        $unit = post_param('unit');
        $minQty = (float)post_param('min_quantity', 0);
        $price = (float)post_param('price', 0);

        if ($id && !empty($name) && !empty($unit)) {
            try {
                db()->execute("UPDATE inventory SET ingredient_name = ?, unit = ?, min_quantity = ?, price = ? WHERE id = ?", 
                              [$name, $unit, $minQty, $price, $id]);
                set_flash('success', "Cập nhật nguyên liệu thành công!");
            } catch (Exception $e) {
                set_flash('error', "Có lỗi xảy ra khi cập nhật!");
            }
        }
        redirect(admin_url('inventory'));
    }

    // Xóa nguyên liệu
    if ($action === 'delete') {
        $id = (int)post_param('id');
        if ($id) {
            try {
                db()->execute("DELETE FROM inventory WHERE id = ?", [$id]);
                set_flash('success', "Đã xóa nguyên liệu thành công!");
            } catch (Exception $e) {
                set_flash('error', "Không thể xóa do nguyên liệu đang có liên kết công thức hoặc lịch sử!");
            }
        }
        redirect(admin_url('inventory'));
    }

    // Nhập kho
    if ($action === 'storeImport') {
        $invId = (int)post_param('inventory_id');
        $qty = (float)post_param('quantity', 0);
        $note = post_param('note');

        if ($invId && $qty > 0) {
            $item = db()->fetchOne("SELECT price FROM inventory WHERE id = ?", [$invId]);
            $price = (float)($item['price'] ?? 0);

            db()->execute("UPDATE inventory SET quantity = quantity + ? WHERE id = ?", [$qty, $invId]);
            db()->execute("INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'IMPORT', ?, ?, ?)", 
                          [$invId, $userId, $qty, $price, $note]);
            set_flash('success', "Nhập kho thành công (+{$qty})!");
        }
        redirect(admin_url('inventory'));
    }

    // Xuất kho
    if ($action === 'storeExport') {
        $invId = (int)post_param('inventory_id');
        $qty = (float)post_param('quantity', 0);
        $reason = post_param('reason');
        $note = post_param('note');
        $fullNote = $reason . (!empty($note) ? " - " . $note : "");

        if ($invId && $qty > 0) {
            $item = db()->fetchOne("SELECT quantity, price FROM inventory WHERE id = ?", [$invId]);
            $currQty = (float)($item['quantity'] ?? 0);
            if ($qty > $currQty) {
                set_flash('error', "Số lượng xuất không được vượt quá số lượng tồn ({$currQty})!");
                redirect(admin_url('inventory_export'));
            }

            $price = (float)($item['price'] ?? 0);
            db()->execute("UPDATE inventory SET quantity = quantity - ? WHERE id = ?", [$qty, $invId]);
            db()->execute("INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'EXPORT', ?, ?, ?)", 
                          [$invId, $userId, $qty, $price, $fullNote]);
            set_flash('success', "Xuất kho thành công (-{$qty})!");
        }
        redirect(admin_url('inventory'));
    }
}

// Lấy danh sách cho các view
$ingredients = db()->fetchAll("SELECT * FROM inventory ORDER BY id ASC");

// Thống kê cho inventory/index
$total = count($ingredients);
$inStock = 0;
$lowStock = 0;
$outOfStock = 0;
$alerts = [];

foreach ($ingredients as $item) {
    $q = (float)$item['quantity'];
    $min = (float)$item['min_quantity'];
    if ($q <= 0) {
        $outOfStock++;
        $alerts[] = $item;
    } elseif ($q <= $min) {
        $lowStock++;
        $alerts[] = $item;
    } else {
        $inStock++;
    }
}
$stats = [
    'total' => $total,
    'in_stock' => $inStock,
    'low_stock' => $lowStock,
    'out_of_stock' => $outOfStock
];

$currentSubPage = get_param('page', 'inventory');

if ($currentSubPage === 'inventory_transactions') {
    $transType = get_param('type', '');
    $transSearch = trim((string)get_param('search', ''));
    $tWhere = ["1=1"];
    $tParams = [];
    if ($transType !== '' && in_array($transType, ['IMPORT', 'EXPORT', 'USAGE', 'ADJUSTMENT'], true)) {
        $tWhere[] = "t.type = ?";
        $tParams[] = $transType;
    }
    if ($transSearch !== '') {
        $tWhere[] = "(i.ingredient_name LIKE ? OR t.note LIKE ?)";
        $tParams[] = '%' . $transSearch . '%';
        $tParams[] = '%' . $transSearch . '%';
    }
    $tClause = implode(" AND ", $tWhere);
    $transactions = db()->fetchAll("
        SELECT t.*, i.ingredient_name, i.unit, COALESCE(u.full_name, 'Hệ thống') as user_name
        FROM inventory_transactions t
        JOIN inventory i ON t.inventory_id = i.id
        LEFT JOIN users u ON t.user_id = u.id
        WHERE $tClause
        ORDER BY t.created_at DESC
        LIMIT 100
    ", $tParams);
    $pageTitle = 'Lịch sử giao dịch kho';
} elseif ($currentSubPage === 'inventory_report') {
    $inventoryReportRows = db()->fetchAll("
        SELECT i.*,
               COALESCE(SUM(CASE WHEN t.type = 'IMPORT' THEN t.quantity ELSE 0 END), 0) as total_import,
               COALESCE(SUM(CASE WHEN t.type IN ('EXPORT', 'USAGE') THEN t.quantity ELSE 0 END), 0) as total_out,
               COALESCE(SUM(CASE WHEN t.type = 'ADJUSTMENT' THEN t.quantity ELSE 0 END), 0) as total_adjust
        FROM inventory i
        LEFT JOIN inventory_transactions t ON i.id = t.inventory_id
        GROUP BY i.id
        ORDER BY (i.quantity * i.price) DESC, i.ingredient_name ASC
    ");
    $totalInventoryValue = 0;
    $totalImportAll = 0;
    $totalOutAll = 0;
    foreach ($inventoryReportRows as $r) {
        $totalInventoryValue += (float)$r['quantity'] * (float)$r['price'];
        $totalImportAll += (float)$r['total_import'];
        $totalOutAll += (float)$r['total_out'];
    }
    $pageTitle = 'Báo cáo tồn kho';
} else {
    $pageTitle = 'Quản lý Nguyên liệu';
}

