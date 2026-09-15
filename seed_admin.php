<?php
require_once __DIR__ . '/app/config/database.php';

$password = password_hash('123456', PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ? WHERE username IN ('admin', 'staff', 'staff1')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $password);

if ($stmt->execute()) {
    echo "Passwords updated for admin/staff to 123456\n";
} else {
    echo "Failed to update: " . $conn->error . "\n";
}
