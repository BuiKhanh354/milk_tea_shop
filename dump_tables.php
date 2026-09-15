<?php
require_once __DIR__ . '/app/config/database.php';

$tables = ['stores'];

foreach ($tables as $table) {
    echo "TABLE: $table\n";
    $res = $conn->query("DESCRIBE $table");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo "  {$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "  Not found or error\n";
    }
    echo "--------------------------\n";
}
