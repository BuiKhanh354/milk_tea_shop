<?php
require 'app/config/database.php';

$sql = "CREATE TABLE IF NOT EXISTS item_options (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('sugar', 'ice') NOT NULL,
    name VARCHAR(50) NOT NULL,
    value INT NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1
)";

if ($conn->query($sql) === TRUE) {
    echo "Table item_options created successfully.\n";
    
    // Check if table is empty
    $res = $conn->query("SELECT COUNT(*) as count FROM item_options");
    $row = $res->fetch_assoc();
    if ($row['count'] == 0) {
        $insert = "INSERT INTO item_options (type, name, value, is_default, status) VALUES 
            ('sugar', 'Không đường', 0, 0, 1),
            ('sugar', 'Ít đường', 25, 0, 1),
            ('sugar', 'Vừa phải', 50, 1, 1),
            ('sugar', 'Ngọt', 75, 0, 1),
            ('sugar', 'Rất ngọt', 100, 0, 1),
            ('ice', 'Không đá', 0, 0, 1),
            ('ice', 'Ít đá', 25, 0, 1),
            ('ice', 'Đá vừa', 50, 1, 1),
            ('ice', 'Nhiều đá', 75, 0, 1),
            ('ice', 'Rất nhiều đá', 100, 0, 1)
        ";
        if ($conn->query($insert) === TRUE) {
            echo "Default item options inserted.\n";
        } else {
            echo "Error inserting data: " . $conn->error . "\n";
        }
    }
} else {
    echo "Error creating table: " . $conn->error . "\n";
}
