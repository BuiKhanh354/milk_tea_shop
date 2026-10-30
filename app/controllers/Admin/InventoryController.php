<?php
require_once __DIR__ . '/../../models/Inventory.php';

class InventoryController {
    public function index() {
        $inventoryModel = new Inventory();
        
        $filters = [];
        if (isset($_GET['search']) && $_GET['search'] !== '') $filters['search'] = $_GET['search'];
        if (isset($_GET['unit']) && $_GET['unit'] !== '') $filters['unit'] = $_GET['unit'];
        if (isset($_GET['status']) && $_GET['status'] !== '') $filters['status'] = $_GET['status'];
        
        $ingredients = $inventoryModel->getAll($filters);
        $stats = $inventoryModel->getStats();

        require_once __DIR__ . '/../../models/InventoryAudit.php';
        $auditModel = new InventoryAudit();
        $latestAudit = $auditModel->getLatestSummary();

        $pageTitle = 'Quản lý Nguyên liệu';
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: admin.php?route=inventory');
            exit;
        }
        
        $pageTitle = 'Chi tiết Nguyên liệu';
        $inventoryModel = new Inventory();
        $item = $inventoryModel->findById($id);
        
        if (!$item) {
            header('Location: admin.php?route=inventory');
            exit;
        }

        require_once __DIR__ . '/../../models/InventoryTransaction.php';
        $transactionModel = new InventoryTransaction();
        
        $history = $transactionModel->getRecentByInventoryId($id, 10);

        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/show.php';
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
            require_csrf('admin.php?route=inventory');
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
            require_csrf('admin.php?route=inventory');
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
            require_csrf('admin.php?route=inventory');
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

    public function report() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }

        $pageTitle = 'Báo cáo Tồn kho';
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();
        
        require_once __DIR__ . '/../../models/InventoryTransaction.php';
        $transactionModel = new InventoryTransaction();
        
        $stats = [
            'total_items' => count($ingredients),
            'low_stock' => 0,
            'out_of_stock' => 0,
            'total_value' => 0
        ];
        
        foreach ($ingredients as $ing) {
            $qty = floatval($ing['quantity']);
            $min = floatval($ing['min_quantity']);
            $price = floatval($ing['price']);
            
            if ($qty <= 0) $stats['out_of_stock']++;
            elseif ($qty <= $min) $stats['low_stock']++;
            
            if ($qty > 0) {
                $stats['total_value'] += ($qty * $price);
            }
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/report.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function storeImport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf('admin.php?route=inventory_import');
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
            require_csrf('admin.php?route=inventory_export');
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

    public function audit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }

        $pageTitle = 'Kiểm kê Kho';
        $inventoryModel = new Inventory();
        $ingredients = $inventoryModel->getAll();

        require_once __DIR__ . '/../../models/InventoryAudit.php';
        $auditModel = new InventoryAudit();
        $audits = $auditModel->getAll(20);

        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/audit.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function storeAudit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf('admin.php?route=inventory_audit');
            require_once __DIR__ . '/../../models/InventoryAudit.php';
            $auditModel = new InventoryAudit();
            $inventoryModel = new Inventory();

            $note = trim($_POST['note'] ?? '');
            $itemsData = $_POST['items'] ?? [];
            $userId = isset($_SESSION['user_id']) ? (is_array($_SESSION['user_id']) ? (int)current($_SESSION['user_id']) : (int)$_SESSION['user_id']) : null;

            $auditItems = [];
            foreach ($itemsData as $invId => $row) {
                // Chỉ xử lý nếu có nhập số lượng thực tế
                if (isset($row['actual_qty']) && $row['actual_qty'] !== '') {
                    $invItem = $inventoryModel->findById($invId);
                    if ($invItem) {
                        $sysQty = (float)$invItem['quantity'];
                        $actQty = (float)$row['actual_qty'];
                        if ($actQty < 0) $actQty = 0; // Không cho phép tồn âm vô lý khi kiểm kê
                        $diffQty = $actQty - $sysQty;
                        $reason = trim($row['reason'] ?? '');

                        $auditItems[] = [
                            'inventory_id' => $invId,
                            'system_qty' => $sysQty,
                            'actual_qty' => $actQty,
                            'difference_qty' => $diffQty,
                            'reason' => $reason
                        ];
                    }
                }
            }

            if (!empty($auditItems)) {
                $auditId = $auditModel->createAudit($userId, $note, $auditItems);
                if ($auditId) {
                    $_SESSION['flash_success'] = "Tạo phiếu kiểm kê và cân bằng kho thành công!";
                    header('Location: admin.php?route=inventory_audit&action=showAudit&id=' . $auditId);
                    exit;
                } else {
                    $_SESSION['flash_error'] = "Có lỗi xảy ra khi lưu phiếu kiểm kê.";
                }
            } else {
                $_SESSION['flash_error'] = "Vui lòng nhập số lượng kiểm kê cho ít nhất một nguyên liệu.";
            }
        }
        header('Location: admin.php?route=inventory_audit');
        exit;
    }

    public function showAudit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: admin.php?route=inventory_audit');
            exit;
        }

        require_once __DIR__ . '/../../models/InventoryAudit.php';
        $auditModel = new InventoryAudit();
        $audit = $auditModel->getById($id);

        if (!$audit) {
            header('Location: admin.php?route=inventory_audit');
            exit;
        }

        $details = $auditModel->getDetailsByAuditId($id);
        $pageTitle = 'Chi tiết Phiếu kiểm kê #' . $audit['audit_code'];

        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/audit_show.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function consumption() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: admin.php?route=inventory');
            exit;
        }

        $pageTitle = 'Theo dõi Tiêu hao & Hao hụt';
        require_once __DIR__ . '/../../services/InventoryService.php';
        $invService = new InventoryService();

        // Mặc định khoảng ngày: từ đầu tháng đến hôm nay hoặc 7 ngày gần nhất
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-d');

        $report = $invService->getConsumptionReport($fromDate, $toDate);

        ob_start();
        require_once __DIR__ . '/../../views/admin/inventory/consumption.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
