<?php
require 'app/config/database.php';
$pw = password_hash('123456', PASSWORD_DEFAULT);
$conn->query("UPDATE users SET password = '$pw' WHERE username = 'staff01'");
echo "Password reset to 123456 for staff01\n";
