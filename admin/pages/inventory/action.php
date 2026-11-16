<?php
// admin/pages/inventory/action.php — Quản lý kho nguyên liệu
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

$pageTitle = 'Quản lý Nguyên liệu';
