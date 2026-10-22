<?php
require_once __DIR__ . '/../config/database.php';

class InventoryAudit {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Tạo một phiếu kiểm kê mới và tự động điều chỉnh tồn kho
     * 
     * @param int|null $userId
     * @param string $note
     * @param array $items [ ['inventory_id' => 1, 'system_qty' => 10, 'actual_qty' => 9.5, 'difference_qty' => -0.5, 'reason' => 'Đổ vỡ'] ]
     * @return int|false ID của phiếu kiểm kê hoặc false nếu lỗi
     */
    public function createAudit($userId, $note, array $items) {
        if (empty($items)) {
            return false;
        }

        $this->conn->begin_transaction();
        try {
            $auditCode = 'KK-' . date('Ymd-His') . '-' . rand(100, 999);
            $totalItems = count($items);
            $totalDiffItems = 0;

            foreach ($items as $item) {
                if (abs((float)$item['difference_qty']) > 0.001) {
                    $totalDiffItems++;
                }
            }

            // 1. Insert header
            $sqlHeader = "INSERT INTO inventory_audits (audit_code, user_id, total_items, total_difference_items, status, note) 
                          VALUES (?, ?, ?, ?, 'completed', ?)";
            $stmtHeader = $this->conn->prepare($sqlHeader);
            $stmtHeader->bind_param("siiis", $auditCode, $userId, $totalItems, $totalDiffItems, $note);
            $stmtHeader->execute();
            $auditId = $stmtHeader->insert_id;

            // 2. Insert details & update inventory
            $sqlDetail = "INSERT INTO inventory_audit_details (audit_id, inventory_id, system_quantity, actual_quantity, difference_quantity, reason) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            $sqlUpd = "UPDATE inventory SET quantity = ? WHERE id = ?";
            $stmtUpd = $this->conn->prepare($sqlUpd);

            $sqlTrans = "INSERT INTO inventory_transactions (inventory_id, user_id, type, quantity, price, note) 
                         VALUES (?, ?, 'ADJUSTMENT', ?, 0, ?)";
            $stmtTrans = $this->conn->prepare($sqlTrans);

            foreach ($items as $item) {
                $invId = (int)$item['inventory_id'];
                $sysQty = (float)$item['system_qty'];
                $actQty = (float)$item['actual_qty'];
                $diffQty = (float)$item['difference_qty'];
                $reason = trim($item['reason'] ?? '');

                // Insert detail row
                $stmtDetail->bind_param("iiddds", $auditId, $invId, $sysQty, $actQty, $diffQty, $reason);
                $stmtDetail->execute();

                // Update inventory quantity to actual count
                $stmtUpd->bind_param("di", $actQty, $invId);
                $stmtUpd->execute();

                // Record transaction if there is a difference
                if (abs($diffQty) > 0.0001) {
                    $prefix = $diffQty > 0 ? '+' : '';
                    $tNote = "Cân bằng kiểm kê #" . $auditCode . " (" . $prefix . $diffQty . ")" . ($reason !== '' ? " - Lý do: " . $reason : "");
                    $stmtTrans->bind_param("iids", $invId, $userId, $diffQty, $tNote);
                    $stmtTrans->execute();
                }
            }

            $this->conn->commit();
            return $auditId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Lấy danh sách các phiếu kiểm kê
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT a.*, COALESCE(u.full_name, u.username, 'Quản trị viên') as user_name 
                FROM inventory_audits a 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.created_at DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy thông tin 1 phiếu kiểm kê
     */
    public function getById($id) {
        $sql = "SELECT a.*, COALESCE(u.full_name, u.username, 'Quản trị viên') as user_name 
                FROM inventory_audits a 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Lấy chi tiết các nguyên liệu trong phiếu kiểm kê
     */
    public function getDetailsByAuditId($auditId) {
        $sql = "SELECT d.*, i.ingredient_name, i.unit, i.price 
                FROM inventory_audit_details d 
                JOIN inventory i ON d.inventory_id = i.id 
                WHERE d.audit_id = ? 
                ORDER BY d.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $auditId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy thông tin đợt kiểm kê gần nhất để hiển thị dashboard
     */
    public function getLatestSummary() {
        $sql = "SELECT a.*, COALESCE(u.full_name, u.username, 'Quản trị viên') as user_name 
                FROM inventory_audits a 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.created_at DESC LIMIT 1";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_assoc() : null;
    }
}
