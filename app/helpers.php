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
 * Xác minh CSRF Token gửi lên từ form có hợp lệ hay không
 *
 * @param string|null $token Token gửi lên (nếu null sẽ tự lấy từ $_POST['csrf_token'])
 * @return bool True nếu hợp lệ, False nếu không hợp lệ
 */
if (!function_exists('verify_csrf')) {
    function verify_csrf($token = null) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        $submittedToken = $token ?? ($_POST['csrf_token'] ?? '');
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return !empty($submittedToken) && !empty($sessionToken) && hash_equals($sessionToken, $submittedToken);
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
