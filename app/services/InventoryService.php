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
            
            $recipe = $piModel->getByProductId($product_id);
            foreach ($recipe as $ing) {
                $inv_id = $ing['inventory_id'];
                $total_qty = (float)$ing['quantity'] * $qty_ordered;
                
                if (!isset($ingredients_needed[$inv_id])) {
                    $ingredients_needed[$inv_id] = 0;
                }
                $ingredients_needed[$inv_id] += $total_qty;
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
}
