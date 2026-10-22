<?php
require_once __DIR__ . '/../models/Inventory.php';

class InventoryService {
    private $inventoryModel;

    public function __construct() {
        $this->inventoryModel = new Inventory();
    }

    public function importStock($inventory_id, $quantity, $note = '', $user_id = null) {
        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Số lượng nhập phải lớn hơn 0'];
        }

        $item = $this->inventoryModel->findById($inventory_id);
        if (!$item) {
            return ['success' => false, 'message' => 'Nguyên liệu không tồn tại'];
        }

        $new_quantity = $item['quantity'] + $quantity;
        $success = $this->inventoryModel->updateQuantity($inventory_id, $new_quantity);

        if ($success) {
            require_once __DIR__ . '/../models/InventoryTransaction.php';
            $transactionModel = new InventoryTransaction();
            $transactionModel->create($inventory_id, $user_id, 'IMPORT', $quantity, $note, $item['price']);
            return ['success' => true, 'message' => 'Nhập kho thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật kho'];
    }

    public function exportStock($inventory_id, $quantity, $reason = '', $note = '', $user_id = null) {
        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Số lượng xuất phải lớn hơn 0'];
        }

        $item = $this->inventoryModel->findById($inventory_id);
        if (!$item) {
            return ['success' => false, 'message' => 'Nguyên liệu không tồn tại'];
        }

        if ($item['quantity'] < $quantity) {
            return ['success' => false, 'message' => 'Không đủ số lượng tồn kho'];
        }

        $new_quantity = $item['quantity'] - $quantity;
        $success = $this->inventoryModel->updateQuantity($inventory_id, $new_quantity);

        if ($success) {
            require_once __DIR__ . '/../models/InventoryTransaction.php';
            $transactionModel = new InventoryTransaction();
            $full_note = "Lý do: " . $reason . ($note ? " - " . $note : "");
            $transactionModel->create($inventory_id, $user_id, 'EXPORT', $quantity, $full_note, $item['price']);
            return ['success' => true, 'message' => 'Xuất kho thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật kho'];
    }

    public function deductForOrder($order_id) {
        require_once __DIR__ . '/../models/Order.php';
        require_once __DIR__ . '/../models/ProductIngredient.php';
        require_once __DIR__ . '/../models/InventoryTransaction.php';
        
        $orderModel = new Order();
        $piModel = new ProductIngredient();
        $transactionModel = new InventoryTransaction();
        
        $order = $orderModel->findByIdWithDetails($order_id);
        if (!$order || empty($order['items'])) {
            return false;
        }

        // Aggregate total ingredients needed
        $ingredients_needed = [];
        foreach ($order['items'] as $item) {
            $product_id = $item['product_id'];
            $qty_ordered = (int)$item['quantity'];
            
            $size_id = !empty($item['size_id']) ? (int)$item['size_id'] : 0;
            $recipe = $piModel->getByProductIdAndSize($product_id, $size_id);
            foreach ($recipe as $ing) {
                $inv_id = $ing['inventory_id'];
                $total_qty = (float)$ing['quantity'] * $qty_ordered;
                
                if (!isset($ingredients_needed[$inv_id])) {
                    $ingredients_needed[$inv_id] = 0;
                }
                $ingredients_needed[$inv_id] += $total_qty;
            }

            // Trừ lượng đường theo quy chuẩn % của sản phẩm
            if (isset($item['sugar_level'])) {
                $sugarDeduction = $piModel->getSugarDeduction($product_id, $size_id, (int)$item['sugar_level']);
                if ($sugarDeduction && !empty($sugarDeduction['inventory_id']) && (float)$sugarDeduction['quantity'] > 0) {
                    $sInvId = (int)$sugarDeduction['inventory_id'];
                    $sQty = (float)$sugarDeduction['quantity'] * $qty_ordered;
                    if (!isset($ingredients_needed[$sInvId])) {
                        $ingredients_needed[$sInvId] = 0;
                    }
                    $ingredients_needed[$sInvId] += $sQty;
                }
            }
        }

        // Deduct from inventory
        foreach ($ingredients_needed as $inv_id => $total_qty) {
            $invItem = $this->inventoryModel->findById($inv_id);
            if ($invItem) {
                $new_qty = $invItem['quantity'] - $total_qty;
                // Allow negative inventory for simplicity, or we can just update it
                $this->inventoryModel->updateQuantity($inv_id, $new_qty);
                
                $note = "Bán hàng - Đơn #" . $order_id;
                $transactionModel->create($inv_id, null, 'USAGE', $total_qty, $note, 0);
            }
        }
        
        return true;
    }

    /**
     * Báo cáo so sánh Tiêu hao dự kiến vs Tiêu hao thực tế & Vượt định mức / Hao hụt
     */
    public function getConsumptionReport($fromDate, $toDate) {
        global $conn;
        require_once __DIR__ . '/../models/ProductIngredient.php';
        $piModel = new ProductIngredient();

        // 1. Lấy danh sách đơn hàng đã hoàn thành trong khoảng thời gian
        $sqlOrders = "SELECT o.id 
                      FROM orders o 
                      WHERE o.status = 'completed' 
                        AND DATE(o.created_at) >= ? 
                        AND DATE(o.created_at) <= ?";
        $stmtOrders = $conn->prepare($sqlOrders);
        $stmtOrders->bind_param("ss", $fromDate, $toDate);
        $stmtOrders->execute();
        $orderRows = $stmtOrders->get_result()->fetch_all(MYSQLI_ASSOC);

        $expected = [];
        $orderCount = count($orderRows);
        $productCount = 0;

        if (!empty($orderRows)) {
            $orderIds = array_column($orderRows, 'id');
            $inClause = implode(',', array_map('intval', $orderIds));
            $sqlItems = "SELECT product_id, size_id, quantity, sugar_level 
                         FROM order_details 
                         WHERE order_id IN ($inClause)";
            $itemRows = $conn->query($sqlItems)->fetch_all(MYSQLI_ASSOC);

            foreach ($itemRows as $item) {
                $pid = (int)$item['product_id'];
                $sid = !empty($item['size_id']) ? (int)$item['size_id'] : 0;
                $qty = (int)$item['quantity'];
                $productCount += $qty;

                // Nguyên liệu cơ bản theo size
                $recipe = $piModel->getByProductIdAndSize($pid, $sid);
                foreach ($recipe as $ing) {
                    $invId = (int)$ing['inventory_id'];
                    $expected[$invId] = ($expected[$invId] ?? 0) + ((float)$ing['quantity'] * $qty);
                }

                // Đường theo quy chuẩn %
                if (isset($item['sugar_level'])) {
                    $sugarDeduction = $piModel->getSugarDeduction($pid, $sid, (int)$item['sugar_level']);
                    if ($sugarDeduction && !empty($sugarDeduction['inventory_id']) && (float)$sugarDeduction['quantity'] > 0) {
                        $sInvId = (int)$sugarDeduction['inventory_id'];
                        $expected[$sInvId] = ($expected[$sInvId] ?? 0) + ((float)$sugarDeduction['quantity'] * $qty);
                    }
                }
            }
        }

        // 2. Lấy dữ liệu thực tế xuất / sử dụng trong kho trong khoảng thời gian
        $sqlTrans = "SELECT inventory_id, type, SUM(quantity) as total_qty 
                     FROM inventory_transactions 
                     WHERE type IN ('USAGE', 'EXPORT') 
                       AND DATE(created_at) >= ? 
                       AND DATE(created_at) <= ? 
                     GROUP BY inventory_id, type";
        $stmtTrans = $conn->prepare($sqlTrans);
        $stmtTrans->bind_param("ss", $fromDate, $toDate);
        $stmtTrans->execute();
        $transRows = $stmtTrans->get_result()->fetch_all(MYSQLI_ASSOC);

        $actual = [];
        $exportLoss = [];
        foreach ($transRows as $tr) {
            $invId = (int)$tr['inventory_id'];
            $tQty = (float)$tr['total_qty'];
            if ($tr['type'] === 'USAGE') {
                $actual[$invId] = ($actual[$invId] ?? 0) + $tQty;
            } elseif ($tr['type'] === 'EXPORT') {
                $exportLoss[$invId] = ($exportLoss[$invId] ?? 0) + $tQty;
                $actual[$invId] = ($actual[$invId] ?? 0) + $tQty;
            }
        }

        // 3. Ghép với danh sách nguyên liệu
        $allIngredients = $this->inventoryModel->getAll();
        $report = [];
        $overCount = 0;
        $totalLossValue = 0;

        foreach ($allIngredients as $ing) {
            $invId = (int)$ing['id'];
            $expQty = $expected[$invId] ?? 0;
            $actQty = $actual[$invId] ?? 0;
            $lossQty = $exportLoss[$invId] ?? 0;

            $diff = $actQty - $expQty;
            $variancePercent = $expQty > 0 ? ($diff / $expQty) * 100 : ($actQty > 0 ? 100 : 0);

            if ($diff > 0.001) {
                $overCount++;
                $totalLossValue += $diff * (float)$ing['price'];
            }

            $report[] = [
                'id' => $invId,
                'ingredient_name' => $ing['ingredient_name'],
                'unit' => $ing['unit'],
                'price' => (float)$ing['price'],
                'current_stock' => (float)$ing['quantity'],
                'expected_qty' => $expQty,
                'actual_qty' => $actQty,
                'loss_export_qty' => $lossQty,
                'difference_qty' => $diff,
                'variance_percent' => $variancePercent,
                'status' => ($diff > 0.001) ? 'over' : (($diff < -0.001) ? 'under' : 'normal')
            ];
        }

        return [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'order_count' => $orderCount,
            'product_count' => $productCount,
            'over_count' => $overCount,
            'total_loss_value' => $totalLossValue,
            'items' => $report
        ];
    }
}
