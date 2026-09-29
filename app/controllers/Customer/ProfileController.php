<?php

class ProfileController {
    
    public function index() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';
        global $conn;

        $customer_id = $_SESSION['customer_id'];
        $success_msg = "";
        $error_msg = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($name)) {
                $error_msg = "Vui lòng nhập họ và tên.";
            } else {
                $sql = "UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sssi", $name, $phone, $address, $customer_id);
                    if ($stmt->execute()) {
                        $success_msg = "Cập nhật thông tin thành công!";
                        $_SESSION['customer_name'] = $name;
                    } else {
                        $error_msg = "Lỗi khi cập nhật: " . $conn->error;
                    }
                    $stmt->close();
                }
            }
        }

        $customer = [];
        $sql = "SELECT full_name, email, phone, address, created_at FROM customers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $customer = $row;
            }
            $stmt->close();
        }

        require_once __DIR__ . '/../../views/customer/profile/index.php';
    }

    public function password() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';
        global $conn;
        
        $customer_id = $_SESSION['customer_id'];
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
                $sql = "SELECT password FROM customers WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("i", $customer_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        if (password_verify($current_password, $row['password'])) {
                            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $update_sql = "UPDATE customers SET password = ? WHERE id = ?";
                            $update_stmt = $conn->prepare($update_sql);
                            if ($update_stmt) {
                                $update_stmt->bind_param("si", $hashed_new_password, $customer_id);
                                if ($update_stmt->execute()) {
                                    $success_msg = "Đổi mật khẩu thành công!";
                                } else {
                                    $error_msg = "Có lỗi xảy ra: " . $conn->error;
                                }
                                $update_stmt->close();
                            }
                        } else {
                            $error_msg = "Mật khẩu hiện tại không đúng.";
                        }
                    }
                    $stmt->close();
                }
            }
        }
        
        $customer = [];
        $sql = "SELECT full_name, email FROM customers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $customer = $row;
            }
            $stmt->close();
        }

        require_once __DIR__ . '/../../views/customer/profile/password.php';
    }

    public function favorites() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        require_once __DIR__ . '/../../views/customer/favorites/index.php';
    }
}
