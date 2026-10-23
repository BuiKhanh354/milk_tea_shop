<?php
require_once __DIR__ . '/../config/database.php';

class ShiftSession {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Lấy phiên ca làm việc đang mở (status = 'open')
     * Có thể tìm theo userId hoặc lấy ca đang mở mới nhất của cửa hàng
     */
    public function getCurrentOpenSession($userId = null) {
        if ($userId) {
            $sql = "SELECT s.*, u.full_name as user_name, sh.name as shift_name, sh.start_time, sh.end_time 
                    FROM shift_sessions s 
                    JOIN users u ON s.user_id = u.id 
                    LEFT JOIN shifts sh ON s.shift_id = sh.id 
                    WHERE s.status = 'open' AND s.user_id = ? 
                    ORDER BY s.opened_at DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        // Lấy phiên mở gần nhất của quầy
        $sql = "SELECT s.*, u.full_name as user_name, sh.name as shift_name, sh.start_time, sh.end_time 
                FROM shift_sessions s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN shifts sh ON s.shift_id = sh.id 
                WHERE s.status = 'open' 
                ORDER BY s.opened_at DESC LIMIT 1";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_assoc() : null;
    }

    /**
     * Mở ca làm việc mới
     */
    public function openSession($userId, $shiftId, $initialCash, array $inventoryItems, $note = '', $employeeShiftId = null) {
        $this->conn->begin_transaction();
        try {
            $sessionCode = 'CA-' . date('Ymd-His');
            $sId = ($shiftId > 0) ? (int)$shiftId : null;
            $empShiftId = ($employeeShiftId > 0) ? (int)$employeeShiftId : null;
            $initCash = (float)$initialCash;

            $sql = "INSERT INTO shift_sessions (session_code, user_id, shift_id, employee_shift_id, opened_at, initial_cash, expected_cash, status, note) 
                    VALUES (?, ?, ?, ?, NOW(), ?, ?, 'open', ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("siiidds", $sessionCode, $userId, $sId, $empShiftId, $initCash, $initCash, $note);
            $stmt->execute();
            $sessionId = $stmt->insert_id;

            // Lưu số lượng nguyên liệu đầu ca
            $sqlInv = "INSERT INTO shift_session_inventory (session_id, inventory_id, start_quantity, expected_ending_qty) 
                       VALUES (?, ?, ?, ?)";
            $stmtInv = $this->conn->prepare($sqlInv);

            foreach ($inventoryItems as $item) {
                $invId = (int)$item['inventory_id'];
                $startQty = (float)$item['start_quantity'];
                $stmtInv->bind_param("iidd", $sessionId, $invId, $startQty, $startQty);
                $stmtInv->execute();
            }

            $this->conn->commit();
            return $sessionId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Lấy tóm tắt doanh số và các đơn hàng trong ca hiện tại
     */
    public function getSessionSummary($sessionId) {
        $session = $this->getById($sessionId);
        if (!$session) return null;

        // Doanh thu theo phương thức thanh toán
        $sqlRev = "SELECT 
                    COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('cash', 'tiền mặt') THEN o.final_amount ELSE 0 END), 0) as cash_sales,
                    COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('bank_transfer', 'chuyển khoản', 'bank') THEN o.final_amount ELSE 0 END), 0) as bank_sales,
                    COALESCE(SUM(CASE WHEN LOWER(p.method) IN ('momo', 'ví momo') THEN o.final_amount ELSE 0 END), 0) as momo_sales,
                    COALESCE(SUM(o.final_amount), 0) as total_sales,
                    COUNT(o.id) as total_orders,
                    COALESCE(SUM(CASE WHEN o.status = 'completed' THEN 1 ELSE 0 END), 0) as completed_orders
                   FROM orders o
                   LEFT JOIN payments p ON o.id = p.order_id
                   WHERE o.shift_session_id = ? AND o.status != 'cancelled'";
        $stmtRev = $this->conn->prepare($sqlRev);
        $stmtRev->bind_param("i", $sessionId);
        $stmtRev->execute();
        $revData = $stmtRev->get_result()->fetch_assoc();

        $initialCash = (float)$session['initial_cash'];
        $cashSales = (float)$revData['cash_sales'];
        $bankSales = (float)$revData['bank_sales'];
        $momoSales = (float)$revData['momo_sales'];
        $totalSales = (float)$revData['total_sales'];
        $expectedCashInDrawer = $initialCash + $cashSales;

        return [
            'session' => $session,
            'initial_cash' => $initialCash,
            'cash_sales' => $cashSales,
            'bank_sales' => $bankSales,
            'momo_sales' => $momoSales,
            'total_sales' => $totalSales,
            'expected_cash' => $expectedCashInDrawer,
            'total_orders' => (int)$revData['total_orders'],
            'completed_orders' => (int)$revData['completed_orders']
        ];
    }

    /**
     * Tính toán lượng tiêu hao dự kiến & tồn lý thuyết cuối ca của nguyên liệu
     */
    public function getEndingInventoryCalculations($sessionId) {
        require_once __DIR__ . '/ProductIngredient.php';
        $piModel = new ProductIngredient();

        // 1. Lấy danh sách nguyên liệu đã ghi nhận đầu ca
        $sql = "SELECT ssi.*, i.ingredient_name, i.unit, i.price 
                FROM shift_session_inventory ssi 
                JOIN inventory i ON ssi.inventory_id = i.id 
                WHERE ssi.session_id = ? 
                ORDER BY i.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $invRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 2. Tính lượng tiêu hao dự kiến từ các đơn hoàn thành trong ca
        $sqlItems = "SELECT od.product_id, od.size_id, od.quantity, od.sugar_level 
                     FROM order_details od
                     JOIN orders o ON od.order_id = o.id
                     WHERE o.shift_session_id = ? AND o.status = 'completed'";
        $stmtItems = $this->conn->prepare($sqlItems);
        $stmtItems->bind_param("i", $sessionId);
        $stmtItems->execute();
        $orderItems = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

        $expectedUsageMap = [];
        foreach ($orderItems as $item) {
            $pid = (int)$item['product_id'];
            $sid = !empty($item['size_id']) ? (int)$item['size_id'] : 0;
            $qty = (int)$item['quantity'];

            // Nguyên liệu công thức
            $recipe = $piModel->getByProductIdAndSize($pid, $sid);
            foreach ($recipe as $ing) {
                $invId = (int)$ing['inventory_id'];
                $expectedUsageMap[$invId] = ($expectedUsageMap[$invId] ?? 0) + ((float)$ing['quantity'] * $qty);
            }

            // Mức đường
            if (isset($item['sugar_level'])) {
                $sugarDeduction = $piModel->getSugarDeduction($pid, $sid, (int)$item['sugar_level']);
                if ($sugarDeduction && !empty($sugarDeduction['inventory_id']) && (float)$sugarDeduction['quantity'] > 0) {
                    $sInvId = (int)$sugarDeduction['inventory_id'];
                    $expectedUsageMap[$sInvId] = ($expectedUsageMap[$sInvId] ?? 0) + ((float)$sugarDeduction['quantity'] * $qty);
                }
            }
        }

        // 3. Kết hợp tính tồn lý thuyết cuối ca
        foreach ($invRows as &$row) {
            $invId = (int)$row['inventory_id'];
            $expUsage = $expectedUsageMap[$invId] ?? 0.0;
            $row['calculated_expected_usage'] = $expUsage;
            $row['calculated_ending_qty'] = max(0, (float)$row['start_quantity'] - $expUsage);
        }

        return $invRows;
    }

    /**
     * Kết ca làm việc (Chốt tiền & Chốt nguyên liệu)
     */
    public function closeSession($sessionId, $actualCash, $cashReason, array $inventoryEndings, $note = '') {
        $summary = $this->getSessionSummary($sessionId);
        if (!$summary) return false;

        $this->conn->begin_transaction();
        try {
            $actCash = (float)$actualCash;
            $expCash = (float)$summary['expected_cash'];
            $cashDiff = $actCash - $expCash;
            $cashSales = (float)$summary['cash_sales'];
            $bankSales = (float)$summary['bank_sales'];
            $momoSales = (float)$summary['momo_sales'];
            $totalSales = (float)$summary['total_sales'];

            // 1. Cập nhật bảng shift_sessions
            $sqlUpd = "UPDATE shift_sessions SET 
                        closed_at = NOW(),
                        cash_sales = ?,
                        bank_sales = ?,
                        momo_sales = ?,
                        total_sales = ?,
                        expected_cash = ?,
                        actual_cash = ?,
                        cash_difference = ?,
                        cash_difference_reason = ?,
                        status = 'closed',
                        note = ?
                       WHERE id = ?";
            $stmtUpd = $this->conn->prepare($sqlUpd);
            $stmtUpd->bind_param("dddddddssi", 
                $cashSales, $bankSales, $momoSales, $totalSales, 
                $expCash, $actCash, $cashDiff, 
                $cashReason, $note, $sessionId
            );
            $stmtUpd->execute();

            // 2. Cập nhật chi tiết nguyên liệu cuối ca
            $calcInv = $this->getEndingInventoryCalculations($sessionId);
            $calcMap = [];
            foreach ($calcInv as $ci) {
                $calcMap[(int)$ci['inventory_id']] = $ci;
            }

            $sqlInvUpd = "UPDATE shift_session_inventory SET 
                            expected_usage = ?,
                            expected_ending_qty = ?,
                            actual_ending_qty = ?,
                            difference_qty = ?,
                            reason = ?
                          WHERE session_id = ? AND inventory_id = ?";
            $stmtInvUpd = $this->conn->prepare($sqlInvUpd);

            // Cập nhật cả tồn kho thực tế nếu có
            $sqlInvActual = "UPDATE inventory SET quantity = ? WHERE id = ?";
            $stmtInvActual = $this->conn->prepare($sqlInvActual);

            foreach ($inventoryEndings as $invId => $endData) {
                $invId = (int)$invId;
                $ci = $calcMap[$invId] ?? null;
                $expUsage = $ci ? (float)$ci['calculated_expected_usage'] : 0.0;
                $expEnd = $ci ? (float)$ci['calculated_ending_qty'] : 0.0;

                $actEnd = isset($endData['actual_ending_qty']) && $endData['actual_ending_qty'] !== '' 
                            ? (float)$endData['actual_ending_qty'] 
                            : $expEnd;
                $diffQty = $actEnd - $expEnd;
                $reason = trim($endData['reason'] ?? '');

                $stmtInvUpd->bind_param("ddddsii", $expUsage, $expEnd, $actEnd, $diffQty, $reason, $sessionId, $invId);
                $stmtInvUpd->execute();

                // Cân bằng kho cho ca tiếp theo dựa trên số lượng đếm cuối ca
                if (isset($endData['actual_ending_qty']) && $endData['actual_ending_qty'] !== '') {
                    $stmtInvActual->bind_param("di", $actEnd, $invId);
                    $stmtInvActual->execute();
                }
            }

            // 3. Đánh dấu ca làm việc trong employee_shifts đã hoàn thành (completed)
            if (!empty($session['employee_shift_id'])) {
                $sqlEmp = "UPDATE employee_shifts SET status = 'completed' WHERE id = ?";
                $stmtEmp = $this->conn->prepare($sqlEmp);
                $stmtEmp->bind_param("i", $session['employee_shift_id']);
                $stmtEmp->execute();
            } elseif (!empty($session['user_id']) && !empty($session['shift_id'])) {
                $workDate = date('Y-m-d', strtotime($session['opened_at']));
                $sqlEmp = "UPDATE employee_shifts SET status = 'completed' WHERE employee_id = ? AND shift_id = ? AND work_date = ?";
                $stmtEmp = $this->conn->prepare($sqlEmp);
                $stmtEmp->bind_param("iis", $session['user_id'], $session['shift_id'], $workDate);
                $stmtEmp->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Lấy 1 phiên ca theo ID
     */
    public function getById($id) {
        $sql = "SELECT s.*, u.full_name as user_name, u.phone as user_phone, sh.name as shift_name, sh.start_time, sh.end_time 
                FROM shift_sessions s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN shifts sh ON s.shift_id = sh.id 
                WHERE s.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Lấy chi tiết nguyên liệu của một ca
     */
    public function getSessionInventoryDetails($sessionId) {
        $sql = "SELECT ssi.*, i.ingredient_name, i.unit, i.price 
                FROM shift_session_inventory ssi 
                JOIN inventory i ON ssi.inventory_id = i.id 
                WHERE ssi.session_id = ? 
                ORDER BY i.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy danh sách các đơn hàng trong ca
     */
    public function getOrdersInSession($sessionId) {
        $sql = "SELECT o.*, 
                       COALESCE(p.method, 'Tiền mặt') as payment_method 
                FROM orders o 
                LEFT JOIN payments p ON o.id = p.order_id 
                WHERE o.shift_session_id = ? 
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy tất cả các ca (dành cho Admin / Quản lý)
     */
    public function getAllSessions($limit = 30, $offset = 0, $filters = []) {
        $sql = "SELECT s.*, u.full_name as user_name, sh.name as shift_name,
                       (SELECT COUNT(*) FROM orders o WHERE o.shift_session_id = s.id) as order_count 
                FROM shift_sessions s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN shifts sh ON s.shift_id = sh.id 
                WHERE 1=1";
        
        $params = [];
        $types = "";

        if (!empty($filters['user_id'])) {
            $sql .= " AND s.user_id = ?";
            $types .= "i";
            $params[] = (int)$filters['user_id'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(s.opened_at) = ?";
            $types .= "s";
            $params[] = $filters['date'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $types .= "s";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY s.opened_at DESC LIMIT ? OFFSET ?";
        $types .= "ii";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
