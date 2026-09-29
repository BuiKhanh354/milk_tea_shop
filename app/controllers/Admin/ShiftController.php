<?php
require_once __DIR__ . '/../../models/Shift.php';
require_once __DIR__ . '/../../models/User.php';

class ShiftController {
    
    public function index() {
        $pageTitle = 'Quản lý ca làm việc';
        
        $shiftModel = new Shift();
        $userModel = new User();

        // Check if Admin or Staff
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
        
        if ($isAdmin) {
            // Lọc theo tuần (mặc định lấy 7 ngày gần đây)
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d', strtotime('+7 days'));

            $shifts = $shiftModel->getAllShifts();
            $assignments = $shiftModel->getEmployeeShifts($startDate, $endDate);
            $staffs = $userModel->getAllStaff();

            ob_start();
            require_once __DIR__ . '/../../views/admin/shifts/index.php';
            $content = ob_get_clean();
        } else {
            // Staff chỉ xem ca của mình
            $assignments = $shiftModel->getStaffShifts($_SESSION['user_id']);
            
            ob_start();
            require_once __DIR__ . '/../../views/admin/shifts/staff_index.php';
            $content = ob_get_clean();
        }

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- CÁC HÀM DÀNH CHO ADMIN ---

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $name = $_POST['name'] ?? '';
            $startTime = $_POST['start_time'] ?? '';
            $endTime = $_POST['end_time'] ?? '';
            
            if ($name && $startTime && $endTime) {
                $shiftModel = new Shift();
                if ($shiftModel->createShift($name, $startTime, $endTime)) {
                    header('Location: admin.php?route=shifts&msg=created');
                    exit;
                }
            }
            header('Location: admin.php?route=shifts&error=failed');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $startTime = $_POST['start_time'] ?? '';
            $endTime = $_POST['end_time'] ?? '';
            $status = $_POST['status'] ?? 1;
            
            if ($id && $name && $startTime && $endTime) {
                $shiftModel = new Shift();
                if ($shiftModel->updateShift($id, $name, $startTime, $endTime, $status)) {
                    header('Location: admin.php?route=shifts&msg=updated');
                    exit;
                }
            }
            header('Location: admin.php?route=shifts&error=failed');
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $id = $_GET['id'];
            $shiftModel = new Shift();
            try {
                if ($shiftModel->deleteShift($id)) {
                    header('Location: admin.php?route=shifts&msg=deleted');
                    exit;
                }
            } catch (Exception $e) {
                header('Location: admin.php?route=shifts&error=fk_constraint');
                exit;
            }
        }
        header('Location: admin.php?route=shifts');
        exit;
    }

    public function assign() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $employeeId = $_POST['employee_id'] ?? 0;
            $shiftId = $_POST['shift_id'] ?? 0;
            $workDate = $_POST['work_date'] ?? '';
            
            if ($employeeId && $shiftId && $workDate) {
                $shiftModel = new Shift();
                if ($shiftModel->assignEmployee($employeeId, $shiftId, $workDate)) {
                    header('Location: admin.php?route=shifts&msg=assigned');
                    exit;
                } else {
                    header('Location: admin.php?route=shifts&error=duplicate');
                    exit;
                }
            }
            header('Location: admin.php?route=shifts&error=failed');
            exit;
        }
    }

    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $id = $_POST['assignment_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if ($id && $status) {
                $shiftModel = new Shift();
                if ($shiftModel->updateAssignmentStatus($id, $status)) {
                    header('Location: admin.php?route=shifts&msg=status_updated');
                    exit;
                }
            }
            header('Location: admin.php?route=shifts&error=failed');
            exit;
        }
    }
}
