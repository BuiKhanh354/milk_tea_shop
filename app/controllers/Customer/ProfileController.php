<?php
require_once __DIR__ . '/../../models/Customer.php';

class ProfileController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new Customer();
    }
    
    public function index() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $customer_id = (int)$_SESSION['customer_id'];
        $success_msg = "";
        $error_msg = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($name)) {
                $error_msg = "Vui lòng nhập họ và tên.";
            } else {
                if ($this->customerModel->updateProfile($customer_id, $name, $phone, $address)) {
                    $success_msg = "Cập nhật thông tin thành công!";
                    $_SESSION['customer_name'] = $name;
                } else {
                    $error_msg = "Lỗi khi cập nhật thông tin.";
                }
            }
        }

        $customer = $this->customerModel->getById($customer_id) ?? [];

        require_once __DIR__ . '/../../views/customer/profile/index.php';
    }

    public function password() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }
        
        $customer_id = (int)$_SESSION['customer_id'];
        $success_msg = "";
        $error_msg = "";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
        
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error_msg = "Vui lòng nhập đầy đủ các trường.";
            } elseif ($new_password !== $confirm_password) {
                $error_msg = "Mật khẩu mới không khớp.";
            } elseif (strlen($new_password) < 6) {
                $error_msg = "Mật khẩu mới phải có ít nhất 6 ký tự.";
            } else {
                if ($this->customerModel->verifyPassword($customer_id, $current_password)) {
                    if ($this->customerModel->updatePassword($customer_id, $new_password)) {
                        $success_msg = "Đổi mật khẩu thành công!";
                    } else {
                        $error_msg = "Có lỗi xảy ra khi cập nhật mật khẩu.";
                    }
                } else {
                    $error_msg = "Mật khẩu hiện tại không đúng.";
                }
            }
        }
        
        $customer = $this->customerModel->getById($customer_id) ?? [];

        require_once __DIR__ . '/../../views/customer/profile/password.php';
    }
}
