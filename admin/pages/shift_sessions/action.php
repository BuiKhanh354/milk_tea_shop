<?php
// admin/pages/shift_sessions/action.php — Phiên ca làm việc
$action = get_param('action', '');
$userId = (int)($_SESSION['user_id'] ?? 1);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Xử lý POST Mở ca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'storeOpen') {
    $shiftId = (int)post_param('shift_id');
    $initialCash = (float)str_replace(['.', ',', ' '], '', post_param('initial_cash', '0'));
    $note = post_param('note');
    $employeeShiftId = null;

    if ($shiftId > 0) {
        $today = date('Y-m-d');
        $validAssignment = db()->fetchOne("SELECT id FROM employee_shifts WHERE employee_id = ? AND shift_id = ? AND work_date = ? LIMIT 1", [$userId, $shiftId, $today]);
        if ($validAssignment) {
            $employeeShiftId = (int)$validAssignment['id'];
        }
    }

    $sessionCode = 'CA-' . date('Ymd-His');
    try {
        db()->execute("INSERT INTO shift_sessions (session_code, user_id, shift_id, employee_shift_id, opened_at, initial_cash, expected_cash, status, note) 
                       VALUES (?, ?, ?, ?, NOW(), ?, ?, 'open', ?)", 
                       [$sessionCode, $userId, $shiftId ?: null, $employeeShiftId, $initialCash, $initialCash, $note]);
        $sessionId = db()->insertId();

        // Lưu nguyên liệu đầu ca
        $invData = $_POST['inventory'] ?? [];
        if (!empty($invData)) {
            foreach ($invData as $invId => $qty) {
                $startQty = (float)$qty;
                db()->execute("INSERT INTO shift_session_inventory (session_id, inventory_id, start_quantity, expected_ending_qty) VALUES (?, ?, ?, ?)", 
                              [$sessionId, (int)$invId, $startQty, $startQty]);
            }
        } else {
            $allInv = db()->fetchAll("SELECT id, quantity FROM inventory");
            foreach ($allInv as $item) {
                $startQty = (float)$item['quantity'];
                db()->execute("INSERT INTO shift_session_inventory (session_id, inventory_id, start_quantity, expected_ending_qty) VALUES (?, ?, ?, ?)", 
                              [$sessionId, (int)$item['id'], $startQty, $startQty]);
            }
        }

        if ($employeeShiftId) {
            db()->execute("UPDATE employee_shifts SET status = 'in_progress' WHERE id = ?", [$employeeShiftId]);
        }

        set_flash('success', "Mở ca làm việc thành công! Tiền két: " . money($initialCash));
        redirect(admin_url('shift_sessions'));
    } catch (Exception $e) {
        set_flash('error', "Có lỗi xảy ra khi mở ca: " . $e->getMessage());
        redirect(admin_url('shift_sessions', ['action' => 'open']));
    }
}

// Xử lý POST Đóng ca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'storeClose') {
    $sessionId = (int)post_param('session_id');
    $actualCash = (float)str_replace(['.', ',', ' '], '', post_param('actual_cash', '0'));
    $cashReason = post_param('cash_difference_reason');
    $note = post_param('note');
    $inventoryEndings = $_POST['inventory_endings'] ?? [];

    if ($sessionId) {
        $session = db()->fetchOne("SELECT * FROM shift_sessions WHERE id = ?", [$sessionId]);
        if ($session) {
            $initialCash = (float)$session['initial_cash'];
            
            // Tính doanh số tiền mặt trong ca
            $cashOrders = db()->fetchOne("SELECT COALESCE(SUM(o.final_amount), 0) as cash_total 
                FROM orders o 
                LEFT JOIN payments p ON o.id = p.order_id 
                WHERE o.shift_session_id = ? AND (LOWER(p.method) IN ('cash', 'tiền mặt') OR p.method IS NULL)", [$sessionId]);
            $cashSales = (float)($cashOrders['cash_total'] ?? 0);
            $expectedCash = $initialCash + $cashSales;
            $cashDiff = $actualCash - $expectedCash;

            $totalSalesRow = db()->fetchOne("SELECT COALESCE(SUM(final_amount), 0) as total FROM orders WHERE shift_session_id = ?", [$sessionId]);
            $totalSales = (float)($totalSalesRow['total'] ?? 0);

            db()->execute("UPDATE shift_sessions SET closed_at = NOW(), actual_cash = ?, expected_cash = ?, total_sales = ?, cash_difference = ?, cash_difference_reason = ?, status = 'closed', note = CONCAT(COALESCE(note, ''), ' | ', ?) WHERE id = ?", 
                          [$actualCash, $expectedCash, $totalSales, $cashDiff, $cashReason, $note, $sessionId]);

            // Cập nhật nguyên liệu cuối ca
            foreach ($inventoryEndings as $invId => $endData) {
                $actEnd = isset($endData['actual_ending_qty']) && $endData['actual_ending_qty'] !== '' ? (float)$endData['actual_ending_qty'] : null;
                $reason = $endData['reason'] ?? '';
                if ($actEnd !== null) {
                    db()->execute("UPDATE shift_session_inventory SET actual_ending_qty = ?, difference_qty = actual_ending_qty - expected_ending_qty, difference_reason = ? WHERE session_id = ? AND inventory_id = ?", 
                                  [$actEnd, $reason, $sessionId, (int)$invId]);
                    db()->execute("UPDATE inventory SET quantity = ? WHERE id = ?", [$actEnd, (int)$invId]);
                }
            }

            if (!empty($session['employee_shift_id'])) {
                db()->execute("UPDATE employee_shifts SET status = 'completed' WHERE id = ?", [$session['employee_shift_id']]);
            }

            set_flash('success', "Kết ca thành công! Tiền két chốt: " . money($actualCash));
            redirect(admin_url('shift_sessions', ['action' => 'show', 'id' => $sessionId]));
        }
    }
    redirect(admin_url('shift_sessions'));
}

// Trạng thái ca hiện tại
$currentOpenSession = db()->fetchOne("SELECT s.*, u.full_name as user_name, sh.name as shift_name, sh.start_time, sh.end_time 
    FROM shift_sessions s 
    JOIN users u ON s.user_id = u.id 
    LEFT JOIN shifts sh ON s.shift_id = sh.id 
    WHERE s.status = 'open' 
    ORDER BY s.opened_at DESC LIMIT 1");

if ($action === 'open') {
    if ($currentOpenSession) {
        redirect(admin_url('shift_sessions', ['action' => 'close']));
    }
    $shifts = db()->fetchAll("SELECT * FROM shifts WHERE status = 1 ORDER BY start_time ASC");
    $ingredients = db()->fetchAll("SELECT * FROM inventory ORDER BY id ASC");
    $pageTitle = 'Mở ca làm việc mới';
} elseif ($action === 'close') {
    if (!$currentOpenSession) {
        set_flash('error', "Không có ca làm việc nào đang mở.");
        redirect(admin_url('shift_sessions', ['action' => 'open']));
    }
    $sessionId = (int)$currentOpenSession['id'];
    $summaryCash = db()->fetchOne("SELECT 
        COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('cash', 'tiền mặt') OR p.method IS NULL THEN o.final_amount ELSE 0 END), 0) as cash_sales,
        COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('bank_transfer', 'chuyển khoản', 'bank') THEN o.final_amount ELSE 0 END), 0) as bank_sales,
        COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('momo', 'ví momo') THEN o.final_amount ELSE 0 END), 0) as momo_sales,
        COALESCE(SUM(o.final_amount), 0) as total_sales,
        COUNT(o.id) as total_orders,
        COUNT(CASE WHEN o.status = 'completed' THEN 1 END) as completed_orders
        FROM orders o 
        LEFT JOIN payments p ON o.id = p.order_id 
        WHERE o.shift_session_id = ?", [$sessionId]);
    
    $summary = array_merge($currentOpenSession, $summaryCash ?: []);
    $summary['expected_cash'] = (float)$currentOpenSession['initial_cash'] + (float)($summary['cash_sales'] ?? 0);

    $endingInventory = db()->fetchAll("SELECT ssi.*, i.ingredient_name, i.unit 
        FROM shift_session_inventory ssi 
        JOIN inventory i ON ssi.inventory_id = i.id 
        WHERE ssi.session_id = ? 
        ORDER BY i.id ASC", [$sessionId]);

    $currentOpen = $currentOpenSession;
    $pageTitle = 'Kết ca & Bàn giao #' . $currentOpenSession['session_code'];
} elseif ($action === 'show') {
    $id = (int)get_param('id');
    $session = db()->fetchOne("SELECT s.*, u.full_name as user_name, u.phone as user_phone, sh.name as shift_name 
        FROM shift_sessions s 
        JOIN users u ON s.user_id = u.id 
        LEFT JOIN shifts sh ON s.shift_id = sh.id 
        WHERE s.id = ?", [$id]);
    if (!$session) {
        redirect(admin_url('shift_sessions'));
    }
    $inventoryDetails = db()->fetchAll("SELECT ssi.*, i.ingredient_name, i.unit 
        FROM shift_session_inventory ssi 
        JOIN inventory i ON ssi.inventory_id = i.id 
        WHERE ssi.session_id = ? 
        ORDER BY i.id ASC", [$id]);
    $orders = db()->fetchAll("SELECT o.*, COALESCE(p.method, 'Tiền mặt') as payment_method 
        FROM orders o 
        LEFT JOIN payments p ON o.id = p.order_id 
        WHERE o.shift_session_id = ? 
        ORDER BY o.created_at DESC", [$id]);
    $pageTitle = 'Biên bản bàn giao ca #' . $session['session_code'];
} else {
    $sessions = db()->fetchAll("SELECT s.*, u.full_name as user_name, sh.name as shift_name,
        (SELECT COUNT(*) FROM orders o WHERE o.shift_session_id = s.id) as order_count 
        FROM shift_sessions s 
        JOIN users u ON s.user_id = u.id 
        LEFT JOIN shifts sh ON s.shift_id = sh.id 
        ORDER BY s.opened_at DESC LIMIT 50");
    $pageTitle = 'Quản lý Ca làm & Bàn giao';
}
