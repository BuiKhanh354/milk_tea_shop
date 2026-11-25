<?php
// admin/pages/inventory_audits/action.php — Kiểm kê kho nguyên liệu (Inventory Audits)
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);
$auditModel = new InventoryAudit();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $action === 'store') {
    validate_csrf();

    $actualQuantities = isset($_POST['actual_quantities']) && is_array($_POST['actual_quantities']) ? $_POST['actual_quantities'] : [];
    $reasons = isset($_POST['reasons']) && is_array($_POST['reasons']) ? $_POST['reasons'] : [];
    $note = trim((string)post_param('note', ''));
    $syncInventory = isset($_POST['sync_inventory']) && $_POST['sync_inventory'] == '1';

    if (empty($actualQuantities)) {
        set_flash('error', 'Không có dữ liệu nguyên liệu để kiểm kê.');
        redirect(admin_url('inventory_audits', ['action' => 'create']));
    }

    $ingredients = db()->fetchAll("SELECT id, ingredient_name, quantity, price FROM inventory WHERE status = 1");
    $ingredientMap = [];
    foreach ($ingredients as $ing) {
        $ingredientMap[(int)$ing['id']] = $ing;
    }

    $totalItems = 0;
    $totalDiffItems = 0;
    $detailRows = [];

    foreach ($actualQuantities as $invIdRaw => $actualRaw) {
        $invId = (int)$invIdRaw;
        if (!isset($ingredientMap[$invId])) {
            continue;
        }
        $systemQty = (float)$ingredientMap[$invId]['quantity'];
        $actualQty = max(0.0, (float)$actualRaw);
        $diffQty = round($actualQty - $systemQty, 2);
        $reason = trim((string)($reasons[$invIdRaw] ?? ''));

        $totalItems++;
        if (abs($diffQty) > 0.001) {
            $totalDiffItems++;
        }

        $detailRows[] = [
            'inventory_id'        => $invId,
            'system_quantity'     => $systemQty,
            'actual_quantity'     => $actualQty,
            'difference_quantity' => $diffQty,
            'reason'              => $reason !== '' ? $reason : null,
            'price'               => (float)($ingredientMap[$invId]['price'] ?? 0),
        ];
    }

    if ($totalItems === 0) {
        set_flash('error', 'Danh sách nguyên liệu kiểm kê không hợp lệ.');
        redirect(admin_url('inventory_audits', ['action' => 'create']));
    }

    try {
        $created = $auditModel->createAudit($userId, $note, $detailRows, $syncInventory);
        set_flash('success', "Đã lưu phiếu kiểm kê {$created['audit_code']} thành công!");
        redirect(admin_url('inventory_audits', ['action' => 'show', 'id' => $created['id']]));
    } catch (Exception $e) {
        set_flash('error', 'Có lỗi xảy ra khi lưu phiếu kiểm kê: ' . $e->getMessage());
        redirect(admin_url('inventory_audits', ['action' => 'create']));
    }
}

if ($action === 'create') {
    $ingredients = db()->fetchAll("SELECT * FROM inventory WHERE status = 1 ORDER BY ingredient_name ASC");
    $pageTitle = 'Tạo phiếu kiểm kê kho';
    return;
}

if ($action === 'show') {
    $id = (int)get_param('id', 0);
    $audit = $auditModel->getByIdWithUser($id);
    if (!$audit) {
        set_flash('error', 'Không tìm thấy phiếu kiểm kê.');
        redirect(admin_url('inventory_audits'));
    }

    $auditDetails = $auditModel->getAuditDetails($id);
    $pageTitle = 'Chi tiết kiểm kê ' . $audit['audit_code'];
    return;
}

// Danh sách phiếu kiểm kê
$audits = $auditModel->getAllWithUser();
$pageTitle = 'Kiểm kê kho (Inventory Audit)';
