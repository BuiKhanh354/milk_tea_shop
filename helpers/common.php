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
    $defaultPlaceholder = 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=600';

    if (empty($image)) {
        return $defaultPlaceholder;
    }
    
    // Nếu là URL online (http:// hoặc https://)
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
        return $image;
    }

    // Nếu đường dẫn bắt đầu bằng public/
    if (strpos($image, 'public/') === 0) {
        return base_url($image);
    }

    // Nếu là file nằm trong public/assets/images/products/
    $cleanName = basename($image);
    $diskPath = __DIR__ . '/../public/assets/images/products/' . $cleanName;
    if (is_file($diskPath)) {
        return asset('assets/images/products/' . $cleanName);
    }

    // Nếu là ảnh có tên tra-sua-truyen-thong, tra-dao, tra-vai, tra-sua-socola
    $onlineFallbacks = array(
        'tra-sua-truyen-thong.jpg' => 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=600',
        'tra-sua-socola.jpg'      => 'https://images.unsplash.com/photo-1589396575653-c09c794f6d74?auto=format&fit=crop&q=80&w=600',
        'tra-dao.jpg'              => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&q=80&w=600',
        'tra-vai.jpg'              => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?auto=format&fit=crop&q=80&w=600',
    );

    if (isset($onlineFallbacks[$cleanName])) {
        return $onlineFallbacks[$cleanName];
    }

    return asset('assets/images/products/' . $cleanName);
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
    if (isset($_SESSION['flash_' . $type])) {
        $msg = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $msg;
    }
    return null;
}

function post_param($name, $default = '')
{
    if (!isset($_POST[$name])) {
        return $default;
    }
    if (is_array($_POST[$name])) {
        return $_POST[$name];
    }
    return trim((string)$_POST[$name]);
}

function csrf_field()
{
    return '';
}

function format_payment_method($method)
{
    $map = array(
        'cash'          => 'Tiền mặt',
        'cod'           => 'COD (Tiền mặt)',
        'bank_transfer' => 'Chuyển khoản',
        'bank'          => 'Chuyển khoản',
        'momo'          => 'Ví MoMo',
    );
    $key = strtolower(trim((string)$method));
    return isset($map[$key]) ? $map[$key] : (ucfirst($method ?: 'Tiền mặt'));
}
