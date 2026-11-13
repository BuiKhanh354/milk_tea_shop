<?php
// helpers/database.php — hàm db(): trả về MỘT đối tượng Database dùng chung
function db()
{
    static $database = null;

    if ($database === null) {
        $config = require __DIR__ . '/../config/database.php';
        $database = new Database($config);
    }

    return $database;
}

