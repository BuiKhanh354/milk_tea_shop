<?php
session_start();

// Hủy toàn bộ dữ liệu session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/logout.php', '', $_SERVER['SCRIPT_NAME']);
header("Location: " . $base_url . "/login.php");
exit;
?>