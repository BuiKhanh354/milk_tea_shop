<?php
require_once __DIR__ . '/../../models/ShiftSession.php';
require_once __DIR__ . '/../../models/Shift.php';
require_once __DIR__ . '/../../models/Inventory.php';

class ShiftSessionController {
    private $sessionModel;
    private $shiftModel;
    private $inventoryModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->sessionModel = new ShiftSession();
        $this->shiftModel = new Shift();
        $this->inventoryModel = new Inventory();
    }

    private function getUserId() {
        return isset($_SESSION['user_id']) 
            ? (is_array($_SESSION['user_id']) ? (int)current($_SESSION['user_id']) : (int)$_SESSION['user_id']) 
            : 0;
    }

    /**
     * Danh sách phiên ca làm việc
     */
    public function index() {
        $userId = $this->getUserId();
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

        $filters = [];
        if (!$isAdmin) {
            $filters['user_id'] = $userId;
        } else {
            if (!empty($_GET['user_id'])) $filters['user_id'] = (int)$_GET['user_id'];
        }
        if (!empty($_GET['date'])) $filters['date'] = $_GET['date'];
        if (!empty($_GET['status'])) $filters['status'] = $_GET['status'];

        $sessions = $this->sessionModel->getAllSessions(50, 0, $filters);
        $currentOpenSession = $this->sessionModel->getCurrentOpenSession($userId);

        // Danh sách nhân viên để lọc (cho admin)
        $users = [];
        if ($isAdmin) {
            global $conn;
            $res = $conn->query("SELECT id, full_name, username FROM users WHERE status = 1 ORDER BY full_name ASC");
            if ($res) $users = $res->fetch_all(MYSQLI_ASSOC);
        }

        $pageTitle = 'Quản lý Ca làm việc (Mở & Kết ca)';
        ob_start();
        require_once __DIR__ . '/../../views/admin/shift_sessions/index.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    /**
     * Giao diện Mở ca làm việc
     */
    public function open() {
        $userId = $this->getUserId();
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

        $currentOpen = $this->sessionModel->getCurrentOpenSession($userId);
        if ($currentOpen) {
            header('Location: admin.php?route=shift_sessions&action=close');
            exit;
        }

        $assignedShifts = [];
        $activeAssignedShift = null;
        $shifts = [];

        if (!$isAdmin) {
            // Nhân viên: CHỈ lấy các ca được phân công cho ngày hôm nay (không lấy ca đã qua ngày)
            $assignedShifts = $this->shiftModel->getAssignedShiftsForStaffToday($userId);
            $activeAssignedShift = $this->shiftModel->getActiveAssignedShiftForStaffNow($userId);
        } else {
            // Admin: lấy toàn bộ danh mục ca hoặc phân công hôm nay nếu có
            $shifts = $this->shiftModel->getAllShifts();
            $assignedShifts = $this->shiftModel->getAssignedShiftsForStaffToday($userId);
            $activeAssignedShift = $this->shiftModel->getActiveAssignedShiftForStaffNow($userId);
        }

        $ingredients = $this->inventoryModel->getAll();

        $pageTitle = 'Mở ca làm việc mới';
        ob_start();
        require_once __DIR__ . '/../../views/admin/shift_sessions/open.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    /**
     * Xử lý Mở ca
     */
    public function storeOpen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->getUserId();
            $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
            $shiftId = (int)($_POST['shift_id'] ?? 0);
            $initialCash = (float)str_replace(['.', ',', ' '], '', $_POST['initial_cash'] ?? '0');
            $note = trim($_POST['note'] ?? '');
            $employeeShiftId = null;

            // Kiểm tra phân quyền và ca làm việc:
            if (!$isAdmin) {
                // Nhân viên: BẮT BUỘC ca phải được phân công trong ngày HÔM NAY (không được mở ca qua ngày hoặc ca không được giao)
                if ($shiftId <= 0) {
                    $_SESSION['flash_error'] = "Vui lòng chọn ca làm việc được phân công cho bạn vào ngày hôm nay.";
                    header('Location: admin.php?route=shift_sessions&action=open');
                    exit;
                }

                $validAssignment = $this->shiftModel->getValidTodayAssignment($userId, $shiftId, date('Y-m-d'));
                if (!$validAssignment) {
                    $_SESSION['flash_error'] = "Ca làm việc này không được phân công cho bạn vào ngày hôm nay hoặc ca đã qua ngày! Bạn không được phép mở ca để bán hàng.";
                    header('Location: admin.php?route=shift_sessions&action=open');
                    exit;
                }

                $employeeShiftId = (int)$validAssignment['id'];
            } else {
                // Admin mở ca: nếu có phân công hôm nay thì liên kết, nếu không thì mở ca theo ca chọn
                if ($shiftId > 0) {
                    $validAssignment = $this->shiftModel->getValidTodayAssignment($userId, $shiftId, date('Y-m-d'));
                    if ($validAssignment) {
                        $employeeShiftId = (int)$validAssignment['id'];
                    }
                }
            }

            // Thu thập nguyên liệu đầu ca
            $invData = $_POST['inventory'] ?? [];
            $inventoryItems = [];
            foreach ($invData as $invId => $qty) {
                $inventoryItems[] = [
                    'inventory_id' => (int)$invId,
                    'start_quantity' => (float)$qty
                ];
            }

            // Nếu không gửi mảng thì lấy từ CSDL tồn kho hiện tại
            if (empty($inventoryItems)) {
                $all = $this->inventoryModel->getAll();
                foreach ($all as $item) {
                    $inventoryItems[] = [
                        'inventory_id' => (int)$item['id'],
                        'start_quantity' => (float)$item['quantity']
                    ];
                }
            }

            $sessionId = $this->sessionModel->openSession($userId, $shiftId, $initialCash, $inventoryItems, $note, $employeeShiftId);
            if ($sessionId) {
                $_SESSION['flash_success'] = "Mở ca làm việc thành công! Tiền két ban đầu: " . number_format($initialCash, 0, ',', '.') . "đ.";
                header('Location: admin.php?route=orders&action=create');
                exit;
            } else {
                $_SESSION['flash_error'] = "Có lỗi xảy ra khi mở ca làm việc.";
            }
        }
        header('Location: admin.php?route=shift_sessions&action=open');
        exit;
    }

    /**
     * Giao diện Kết ca làm việc
     */
    public function close() {
        $userId = $this->getUserId();
        $currentOpen = $this->sessionModel->getCurrentOpenSession($userId);
        if (!$currentOpen) {
            $_SESSION['flash_error'] = "Bạn hiện không có ca làm việc nào đang mở để kết ca.";
            header('Location: admin.php?route=shift_sessions&action=open');
            exit;
        }

        $sessionId = (int)$currentOpen['id'];
        $summary = $this->sessionModel->getSessionSummary($sessionId);
        $endingInventory = $this->sessionModel->getEndingInventoryCalculations($sessionId);

        $pageTitle = 'Kết ca & Bàn giao (Ca #' . $currentOpen['session_code'] . ')';
        ob_start();
        require_once __DIR__ . '/../../views/admin/shift_sessions/close.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    /**
     * Xử lý Kết ca
     */
    public function storeClose() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionId = (int)($_POST['session_id'] ?? 0);
            $actualCash = (float)str_replace(['.', ',', ' '], '', $_POST['actual_cash'] ?? '0');
            $cashReason = trim($_POST['cash_difference_reason'] ?? '');
            $note = trim($_POST['note'] ?? '');
            $inventoryEndings = $_POST['inventory_endings'] ?? [];

            $success = $this->sessionModel->closeSession($sessionId, $actualCash, $cashReason, $inventoryEndings, $note);
            if ($success) {
                $_SESSION['flash_success'] = "Kết ca và bàn giao thành công! Đã chốt tiền két và tồn kho.";
                header('Location: admin.php?route=shift_sessions&action=show&id=' . $sessionId);
                exit;
            } else {
                $_SESSION['flash_error'] = "Có lỗi xảy ra khi đóng ca.";
            }
        }
        header('Location: admin.php?route=shift_sessions&action=close');
        exit;
    }

    /**
     * Xem chi tiết / In biên bản bàn giao ca
     */
    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: admin.php?route=shift_sessions');
            exit;
        }

        $session = $this->sessionModel->getById($id);
        if (!$session) {
            header('Location: admin.php?route=shift_sessions');
            exit;
        }

        $summary = $this->sessionModel->getSessionSummary($id);
        $inventoryDetails = $this->sessionModel->getSessionInventoryDetails($id);
        $orders = $this->sessionModel->getOrdersInSession($id);

        $pageTitle = 'Biên bản bàn giao ca #' . $session['session_code'];
        ob_start();
        require_once __DIR__ . '/../../views/admin/shift_sessions/show.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
