<?php
require 'app/config/database.php';

$conn->query("CREATE TABLE IF NOT EXISTS shifts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS employee_shifts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id INT(11) NOT NULL,
    shift_id INT UNSIGNED NOT NULL,
    work_date DATE NOT NULL,
    status ENUM('assigned', 'completed', 'absent', 'cancelled') DEFAULT 'assigned',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (employee_id, shift_id, work_date),
    FOREIGN KEY (employee_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (shift_id) REFERENCES shifts(id) ON UPDATE CASCADE ON DELETE CASCADE
)");

echo $conn->error;
echo "Done";
