<?php
require_once __DIR__ . '/../../models/User.php';

class StaffController {
    
    public function index() {
        $pageTitle = 'Quản lý Nhân viên';
        
        $userModel = new User();
        $staffs = $userModel->getAllStaff();

        ob_start();
        require_once __DIR__ . '/../../views/admin/staff/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';

            if (!empty($username) && !empty($password) && !empty($full_name)) {
                $userModel = new User();
                $userModel->createStaff($username, $password, $full_name, $email, $phone);
            }
        }
        header('Location: admin.php?route=staff');
        exit;
    }

    public function toggle_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $userModel = new User();
            $userModel->toggleStatus($_POST['id']);
        }
        header('Location: admin.php?route=staff');
        exit;
    }
}
