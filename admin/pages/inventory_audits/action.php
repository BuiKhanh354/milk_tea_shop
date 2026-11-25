<?php
// admin/pages/inventory_audits/action.php — Kiểm kê kho nguyên liệu (Inventory Audits)
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);

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

    $auditCode = 'KK-' . date('Ymd-His');

    try {
        db()->execute(
            "INSERT INTO inventory_audits (audit_code, user_id, total_items, total_difference_items, status, note, created_at) VALUES (?, ?, ?, ?, 'completed', ?, NOW())",
            [$auditCode, $userId, $totalItems, $totalDiffItems, $note !== '' ? $note : null]
        );
        $auditId = (int)db()->insertId();

        foreach ($detailRows as $row) {
            db()->execute(
                "INSERT INTO inventory_audit_details (audit_id, inventory_id, system_quantity, actual_quantity, difference_quantity, reason, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [
                    $auditId,
                    $row['inventory_id'],
                    $row['system_quantity'],
                    $row['actual_quantity'],
                    $row['difference_quantity'],
                    $row['reason']
                ]
            );

            if ($syncInventory && abs($row['difference_quantity']) > 0.001) {
                db()->execute(
                    "UPDATE inventory SET quantity = ? WHERE id = ?",
                    [$row['actual_quantity'], $row['inventory_id']]
                );
                $transNote = "Điều chỉnh theo phiếu kiểm kê {$auditCode}" . ($row['reason'] ? " ({$row['reason']})" : '');
                db()->execute(
                    "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) VALUES (?, ?, 'ADJUSTMENT', ?, ?, ?)",
                    [$row['inventory_id'], $userId, $row['difference_quantity'], $row['price'], $transNote]
                );
            }
        }

        set_flash('success', "Đã lưu phiếu kiểm kê {$auditCode} thành công!");
        redirect(admin_url('inventory_audits', ['action' => 'show', 'id' => $auditId]));
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
    $audit = db()->fetchOne(
        "SELECT a.*, COALESCE(u.full_name, 'Hệ thống') as user_name
         FROM inventory_audits a
         LEFT JOIN users u ON a.user_id = u.id
         WHERE a.id = ?",
        [$id]
    );
    if (!$audit) {
        set_flash('error', 'Không tìm thấy phiếu kiểm kê.');
        redirect(admin_url('inventory_audits'));
    }

    $auditDetails = db()->fetchAll(
        "SELECT d.*, i.ingredient_name, i.unit
         FROM inventory_audit_details d
         JOIN inventory i ON d.inventory_id = i.id
         WHERE d.audit_id = ?
         ORDER BY ABS(d.difference_quantity) DESC, i.ingredient_name ASC",
        [$id]
    );
    $pageTitle = 'Chi tiết kiểm kê ' . $audit['audit_code'];
    return;
}

// Danh sách phiếu kiểm kê
$audits = db()->fetchAll(
    "SELECT a.*, COALESCE(u.full_name, 'Hệ thống') as user_name
     FROM inventory_audits a
     LEFT JOIN users u ON a.user_id = u.id
     ORDER BY a.created_at DESC"
);
$pageTitle = 'Kiểm kê kho (Inventory Audit)';
