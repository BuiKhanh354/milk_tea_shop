<?php
// config/init.php — khởi tạo chung, index.php nạp file này đầu tiên
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/../classes/Database.php';      // class Database (kết nối MySQL)
require_once __DIR__ . '/../helpers/database.php';      // hàm db(): lấy đối tượng Database
require_once __DIR__ . '/../helpers/router.php';        // hàm tạo URL: url(), asset()...
require_once __DIR__ . '/../helpers/common.php';        // hàm tiện ích: e(), money(), redirect()...

