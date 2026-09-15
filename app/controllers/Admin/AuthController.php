<?php
require_once __DIR__ . '/../../models/User.php';

class AuthController {
    
    public function login() {
        // Nếu đã đăng nhập, chuyển hướng về dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: admin.php?route=' . ($_SESSION['role'] === 'admin' ? 'dashboard' : 'staff_dashboard'));
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
            } else {
                $userModel = new User();
                $user = $userModel->authenticate($username, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['full_name'] = $user['full_name'];
                    
                    if ($user['role'] === 'admin') {
                        header('Location: admin.php?route=dashboard');
                    } else {
                        header('Location: admin.php?route=staff_dashboard');
                    }
                    exit;
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không chính xác hoặc tài khoản bị khóa.';
                }
            }
        }

        require_once __DIR__ . '/../../views/admin/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
