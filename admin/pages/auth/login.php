<?php
// admin/pages/auth/login.php — Chuyển hướng sang trang đăng nhập chung của hệ thống
$redirectTarget = get_param('redirect', admin_url('dashboard'));
redirect(url('login', ['redirect' => $redirectTarget]));
