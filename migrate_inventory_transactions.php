<?php
require 'app/config/database.php';

$sql = "CREATE TABLE IF NOT EXISTS inventory_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inventory_id INT NOT NULL,
    user_id INT NULL,
    type ENUM('IMPORT', 'EXPORT', 'USAGE', 'ADJUSTMENT') NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    price DECIMAL(15,2) DEFAULT 0,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table inventory_transactions created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
