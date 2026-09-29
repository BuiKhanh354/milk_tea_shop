<?php

class AuthController {
    
    public function login() {
        // If already logged in
        if (isset($_SESSION['user_id']) || isset($_SESSION['customer_id'])) {
            header('Location: index.php');
            exit;
        }

        $error = '';
        $email_val = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../config/database.php';
            global $conn;
            
            $emailOrUsername = trim($_POST['email']);
            $password = $_POST['password'];
            $email_val = htmlspecialchars($emailOrUsername);

            require_once __DIR__ . '/../../models/User.php';
            $userModel = new User();
            $adminUser = $userModel->authenticate($emailOrUsername, $password);

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
                $sql = "SELECT * FROM customers WHERE email = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("s", $emailOrUsername);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $customer = $result->fetch_assoc();

                    if ($customer && password_verify($password, $customer['password'])) {
                        $_SESSION['customer_id'] = $customer['id'];
                        $_SESSION['customer_name'] = $customer['full_name'];
                        $_SESSION['customer_email'] = $customer['email'];

                        header("Location: index.php");
                        exit;
                    } else {
                        $error = "Tài khoản hoặc mật khẩu không đúng!";
                    }
                } else {
                    $error = "Lỗi kết nối CSDL!";
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
            require_once __DIR__ . '/../../config/database.php';
            global $conn;
            
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
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
                $sql = "SELECT id FROM customers WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $customer = $result->fetch_assoc();

                if ($customer) {
                    $error = "Email này đã được đăng ký!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $insert_sql = "INSERT INTO customers (full_name, email, password) VALUES (?, ?, ?)";
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("sss", $name, $email, $hashed_password);
                    
                    if ($insert_stmt->execute()) {
                        $success = "Đăng ký thành công! Đang chuyển hướng đến đăng nhập...";
                    } else {
                        $error = "Có lỗi xảy ra: " . $conn->error;
                    }
                }
            }
        }

        require_once __DIR__ . '/../../views/customer/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
