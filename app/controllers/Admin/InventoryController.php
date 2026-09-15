<?php
require_once __DIR__ . '/../../models/Inventory.php';

class InventoryController {
    public function index() {
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();
        $stats = $inventoryModel->getStats();

        $pageTitle = 'Quản lý Nguyên liệu';
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }
        $pageTitle = 'Thêm Nguyên liệu';
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/create.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $unit = trim($_POST['unit'] ?? '');
            $min_quantity = floatval($_POST['min_quantity'] ?? 0);
            $price = floatval($_POST['price'] ?? 0);
            $note = trim($_POST['note'] ?? '');

            if (!empty($name) && !empty($unit) && $min_quantity >= 0 && $price >= 0) {
                $inventoryModel = new Inventory();
                if ($inventoryModel->create($name, $unit, $min_quantity, $price, $note)) {
                    $_SESSION['flash_success'] = "Thêm nguyên liệu thành công!";
                } else {
                    $_SESSION['flash_error'] = "Có lỗi xảy ra khi thêm nguyên liệu.";
                }
            } else {
                $_SESSION['flash_error'] = "Dữ liệu không hợp lệ.";
            }
        }
        header('Location: admin.php?route=inventory');
        exit;
    }

    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: admin.php?route=inventory');
            exit;
        }
        $inventoryModel = new Inventory();
        $item = $inventoryModel->findById($id);
        if (!$item) {
            header('Location: admin.php?route=inventory');
            exit;
        }
        
        $pageTitle = 'Sửa Nguyên liệu';
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/edit.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $unit = trim($_POST['unit'] ?? '');
            $min_quantity = floatval($_POST['min_quantity'] ?? 0);
            $price = floatval($_POST['price'] ?? 0);

            if ($id && !empty($name) && !empty($unit) && $min_quantity >= 0 && $price >= 0) {
                $inventoryModel = new Inventory();
                if ($inventoryModel->update($id, $name, $unit, $min_quantity, $price)) {
                    $_SESSION['flash_success'] = "Cập nhật nguyên liệu thành công!";
                } else {
                    $_SESSION['flash_error'] = "Có lỗi xảy ra.";
                }
            }
        }
        header('Location: admin.php?route=inventory');
        exit;
    }

    public function delete() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $inventoryModel = new Inventory();
                if ($inventoryModel->delete($id)) {
                    $_SESSION['flash_success'] = "Đã xóa nguyên liệu!";
                } else {
                    $_SESSION['flash_error'] = "Không thể xóa nguyên liệu này vì đang được sử dụng trong hệ thống.";
                }
            }
        }
        header('Location: admin.php?route=inventory');
        exit;
    }

    public function import() {
        $pageTitle = 'Nhập kho';
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/import.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function storeImport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../services/InventoryService.php';
            $service = new InventoryService();
            
            $id = $_POST['inventory_id'] ?? null;
            $quantity = floatval($_POST['quantity'] ?? 0);
            $note = trim($_POST['note'] ?? '');
            $user_id = $_SESSION['user_id'] ?? null;

            if ($id && $quantity > 0) {
                $result = $service->importStock($id, $quantity, $note, $user_id);
                if ($result['success']) {
                    $_SESSION['flash_success'] = $result['message'];
                } else {
                    $_SESSION['flash_error'] = $result['message'];
                }
            } else {
                $_SESSION['flash_error'] = "Dữ liệu không hợp lệ.";
            }
        }
        header('Location: admin.php?route=inventory');
        exit;
    }

    public function export() {
        $pageTitle = 'Xuất kho';
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/export.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function storeExport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../services/InventoryService.php';
            $service = new InventoryService();
            
            $id = $_POST['inventory_id'] ?? null;
            $quantity = floatval($_POST['quantity'] ?? 0);
            $reason = trim($_POST['reason'] ?? '');
            $note = trim($_POST['note'] ?? '');
            $user_id = $_SESSION['user_id'] ?? null;

            if ($id && $quantity > 0 && !empty($reason)) {
                $result = $service->exportStock($id, $quantity, $reason, $note, $user_id);
                if ($result['success']) {
                    $_SESSION['flash_success'] = $result['message'];
                } else {
                    $_SESSION['flash_error'] = $result['message'];
                }
            } else {
                $_SESSION['flash_error'] = "Vui lòng điền đủ thông tin.";
            }
        }
        header('Location: admin.php?route=inventory');
        exit;
    }

    public function history() {
        $pageTitle = 'Lịch sử Kho';
        require_once __DIR__ . '/../../models/InventoryTransaction.php';
        $transactionModel = new InventoryTransaction();
        $transactions = $transactionModel->getAll();
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/history.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function alerts() {
        $pageTitle = 'Cảnh báo Tồn kho';
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();
        
        // Lọc ra các nguyên liệu sắp hết hoặc hết hàng
        $alerts = array_filter($ingredients, function($item) {
            return floatval($item['quantity']) <= floatval($item['min_quantity']);
        });
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/alerts.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
