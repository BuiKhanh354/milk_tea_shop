<?php
// helpers/common.php — các hàm tiện ích theo chuẩn minishop

// e(): chống XSS
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// money(45000) → "45.000đ"
function money($value)
{
    return number_format((float) $value, 0, ',', '.') . 'đ';
}

// redirect(): chuyển trang
function redirect($location)
{
    header('Location: ' . $location);
    exit;
}

// Đường dẫn ảnh sản phẩm cho VAA THE
function product_image($image)
{
    if (empty($image)) {
        return asset('assets/images/placeholder.png');
    }
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
        return $image;
    }
    if (strpos($image, 'public/') === 0) {
        return base_url($image);
    }
    return asset(ltrim($image, '/'));
}

// Flash messages
function set_flash($type, $message)
{
    $_SESSION['flash'][$type] = $message;
}

function get_flash($type)
{
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

