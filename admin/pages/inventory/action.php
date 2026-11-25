<?php
// admin/pages/inventory/action.php — Quản lý kho nguyên liệu
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$ingredientModel = new Ingredient();

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
                $ingredientModel->store($name, $unit, $qty, $minQty, $price, $userId);
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
                $ingredientModel->update($id, $name, $unit, $minQty, $price);
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
                $ingredientModel->deleteById($id);
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

        if ($ingredientModel->importStock($invId, $qty, $userId, $note)) {
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
            $res = $ingredientModel->exportStock($invId, $qty, $userId, $fullNote);
            if (!$res['ok']) {
                set_flash('error', $res['message']);
                redirect(admin_url('inventory_export'));
            }
            set_flash('success', "Xuất kho thành công (-{$qty})!");
        }
        redirect(admin_url('inventory'));
    }
}

// Lấy danh sách cho các view
$ingredients = $ingredientModel->all('id ASC');

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
    $transactions = $ingredientModel->getTransactions($transType, $transSearch, 100);
    $pageTitle = 'Lịch sử giao dịch kho';
} elseif ($currentSubPage === 'inventory_report') {
    $inventoryReportRows = $ingredientModel->getInventoryReportRows();
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

