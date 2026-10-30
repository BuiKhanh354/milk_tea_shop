<?php
/**
 * Các hàm Helper bảo mật CSRF dùng chung toàn bộ ứng dụng
 */

/**
 * Tạo hoặc lấy CSRF Token từ session để chống tấn công CSRF cho các form POST
 *
 * @return string Chuỗi mã token ngẫu nhiên 64 ký tự
 */
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

/**
 * Sinh nhanh thẻ input ẩn chứa CSRF Token
 * Sử dụng: <?= csrf_field() ?> bên trong <form>
 *
 * @return string Mã HTML: <input type="hidden" name="csrf_token" value="...">
 */
if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * Sinh thẻ meta chứa CSRF Token đặt trong thẻ <head>
 * Sử dụng: <?= csrf_meta() ?> bên trong <head>
 *
 * @return string Mã HTML: <meta name="csrf-token" content="...">
 */
if (!function_exists('csrf_meta')) {
    function csrf_meta() {
        return '<meta name="csrf-token" content="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * Xác minh CSRF Token gửi lên từ form hoặc request header có hợp lệ hay không
 *
 * @param string|null $token Token gửi lên (nếu null sẽ tự lấy từ $_POST['csrf_token'] hoặc headers)
 * @return bool True nếu hợp lệ, False nếu không hợp lệ
 */
if (!function_exists('verify_csrf')) {
    function verify_csrf($token = null) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (empty($sessionToken)) {
            return false;
        }

        // Lấy từ tham số truyền vào
        if ($token !== null && is_string($token) && !empty($token)) {
            return hash_equals($sessionToken, $token);
        }

        // Lấy từ $_POST
        if (!empty($_POST['csrf_token']) && is_string($_POST['csrf_token'])) {
            return hash_equals($sessionToken, $_POST['csrf_token']);
        }

        // Lấy từ HTTP Headers (dành cho fetch/AJAX)
        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_SERVER['HTTP_X_XSRF_TOKEN'] ?? '');
        if (!empty($headerToken) && is_string($headerToken)) {
            return hash_equals($sessionToken, $headerToken);
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (!empty($headers['X-CSRF-TOKEN']) && is_string($headers['X-CSRF-TOKEN'])) {
                return hash_equals($sessionToken, $headers['X-CSRF-TOKEN']);
            }
            if (!empty($headers['x-csrf-token']) && is_string($headers['x-csrf-token'])) {
                return hash_equals($sessionToken, $headers['x-csrf-token']);
            }
        }

        return false;
    }
}

/**
 * Bắt buộc kiểm tra CSRF Token cho các action xử lý POST, tự động chặn nếu không hợp lệ
 *
 * @param string|null $redirectUrl Đường dẫn chuyển hướng khi lỗi (nếu rỗng sẽ trả về lỗi HTTP 403)
 * @param string $errorMessage Thông báo lỗi bảo mật
 */
if (!function_exists('require_csrf')) {
    function require_csrf($redirectUrl = null, $errorMessage = 'Mã bảo mật CSRF không hợp lệ hoặc đã hết hạn, vui lòng tải lại trang và thử lại!') {
        if (!verify_csrf()) {
            // Kiểm tra nếu là AJAX request
            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                      || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            
            if ($isAjax) {
                http_response_code(403);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'error' => 'csrf_invalid',
                    'message' => $errorMessage
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($redirectUrl) {
                $_SESSION['flash_error'] = $errorMessage;
                header('Location: ' . $redirectUrl);
                exit;
            }

            http_response_code(403);
            die('<h3>403 Forbidden - Lỗi bảo mật CSRF</h3><p>' . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') . '</p>');
        }
    }
}

/**
 * Định dạng phương thức thanh toán sang tiếng Việt hiển thị
 *
 * @param string|null $method Mã phương thức (cash, bank_transfer, momo,...)
 * @return string Tên hiển thị tiếng Việt tương ứng
 */
if (!function_exists('format_payment_method')) {
    function format_payment_method($method) {
        $map = [
            'cash'          => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'bank'          => 'Chuyển khoản',
            'momo'          => 'Ví MoMo',
        ];
        $key = strtolower(trim($method ?? ''));
        return $map[$key] ?? ($method ?: 'Tiền mặt');
    }
}

/**
 * Lấy trạng thái ca làm việc hiện tại của tài khoản đang đăng nhập (dùng cho topbar / layout chung)
 */
if (!function_exists('get_current_user_shift_status')) {
    function get_current_user_shift_status() {
        $userId = isset($_SESSION['user_id']) ? (is_array($_SESSION['user_id']) ? (int)current($_SESSION['user_id']) : (int)$_SESSION['user_id']) : 0;
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
        
        require_once __DIR__ . '/models/ShiftSession.php';
        require_once __DIR__ . '/models/Shift.php';
        $shiftModel = new ShiftSession();
        $openSession = $shiftModel->getCurrentOpenSession($userId);
        $todayShift = null;
        if (!$openSession) {
            $shiftObj = new Shift();
            $todayShift = $shiftObj->getActiveAssignedShiftForStaffNow($userId);
        }

        return [
            'user_id' => $userId,
            'is_admin' => $isAdmin,
            'open_session' => $openSession,
            'today_shift' => $todayShift
        ];
    }
}
