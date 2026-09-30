<?php
require_once __DIR__ . '/../../models/User.php';

class ProfileController {
    public function index() {
        $pageTitle = 'Hồ sơ cá nhân';
        
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        // User model doesn't have a direct findById, let's use checkLogin logic or we can write a method getById
        // Wait, User model in app/models/User.php - let's check it.
        $user = $userModel->getById($userId);
        
        ob_start();
        require_once __DIR__ . '/../../views/admin/profile/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $fullName = $_POST['full_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $userModel = new User();
            if ($userModel->updateProfile($userId, $username, $password, $fullName, $phone, $email)) {
                // Update session
                $_SESSION['full_name'] = $fullName;
                $_SESSION['username'] = $username;
                header('Location: admin.php?route=profile&msg=success');
            } else {
                header('Location: admin.php?route=profile&error=failed');
            }
            exit;
        }
    }
}
