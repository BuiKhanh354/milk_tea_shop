<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../models/Cart.php';

class AuthController {
    private $userModel;
    private $customerModel;

    public function __construct() {
        $this->userModel = new User();
        $this->customerModel = new Customer();
    }
    
    public function login() {
        // If already logged in
        if (isset($_SESSION['user_id']) || isset($_SESSION['customer_id'])) {
            header('Location: index.php');
            exit;
        }

        $error = '';
        $email_val = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emailOrUsername = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $email_val = htmlspecialchars($emailOrUsername);

            $adminUser = $this->userModel->authenticate($emailOrUsername, $password);

            if ($adminUser) {
                $_SESSION['user_id'] = $adminUser['id'];
                $_SESSION['role'] = $adminUser['role'];
                $_SESSION['full_name'] = $adminUser['full_name'];

                if ($adminUser['role'] === 'admin') {
                    header('Location: admin.php?route=dashboard');
                } else {
                    header('Location: admin.php?route=staff_dashboard');
                }
                exit;
            } else {
                $customer = $this->customerModel->findByEmail($emailOrUsername);

                if ($customer && password_verify($password, $customer['password'])) {
                    $_SESSION['customer_id'] = $customer['id'];
                    $_SESSION['customer_name'] = $customer['full_name'];
                    $_SESSION['customer_email'] = $customer['email'];

                    // Đồng bộ & tải giỏ hàng của tài khoản từ CSDL
                    $cartModel = new Cart();
                    $cartModel->mergeGuestCartToCustomer($customer['id'], $_COOKIE['cart_token'] ?? '');

                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Tài khoản hoặc mật khẩu không đúng!";
                }
            }
        }

        require_once __DIR__ . '/../../views/customer/auth/login.php';
    }

    public function register() {
        if (isset($_SESSION['user_id']) || isset($_SESSION['customer_id'])) {
            header('Location: index.php');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($name)) {
                $error = "Vui lòng nhập họ tên";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email không hợp lệ";
            } elseif (strlen($password) < 6) {
                $error = "Mật khẩu phải có ít nhất 6 ký tự";
            } elseif ($confirm_password !== $password) {
                $error = "Mật khẩu xác nhận không khớp";
            } else {
                $existingCustomer = $this->customerModel->findByEmail($email);

                if ($existingCustomer) {
                    $error = "Email này đã được đăng ký!";
                } else {
                    $newId = $this->customerModel->register($name, $email, $password);
                    if ($newId) {
                        $success = "Đăng ký thành công! Đang chuyển hướng đến đăng nhập...";
                    } else {
                        $error = "Có lỗi xảy ra khi tạo tài khoản, vui lòng thử lại!";
                    }
                }
            }
        }

        require_once __DIR__ . '/../../views/customer/auth/register.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Giỏ hàng của tài khoản đã được lưu an toàn trong CSDL
        $_SESSION = [];
        session_destroy();

        // Xóa cookie cart_token cũ để nếu có khách khác dùng máy thì có giỏ mới
        if (isset($_COOKIE['cart_token'])) {
            setcookie('cart_token', '', time() - 3600, '/');
            unset($_COOKIE['cart_token']);
        }

        header('Location: index.php');
        exit;
    }
}
