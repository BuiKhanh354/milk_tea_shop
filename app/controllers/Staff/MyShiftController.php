<?php
class MyShiftController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $pageTitle = 'Ca làm việc của tôi';

        require_once __DIR__ . '/../../models/Shift.php';
        $shiftModel = new Shift();
        $shifts = $shiftModel->getStaffShifts($userId);

        ob_start();
        require_once __DIR__ . '/../../views/admin/shifts/my_shifts.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
