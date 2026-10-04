<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
session_start();
$_SESSION['user_id'] = 2;
$_SESSION['role'] = 'staff';
$_GET['route'] = 'my-shifts';
ob_start();
require 'public/admin.php';
$out = ob_get_clean();
echo substr($out, 0, 1000);
