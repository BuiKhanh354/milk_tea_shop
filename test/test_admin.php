<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_GET['route'] = 'shifts';
ob_start();
require 'public/admin.php';
$out = ob_get_clean();
echo substr($out, 0, 1000);
