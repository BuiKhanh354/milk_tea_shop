<?php
require 'app/config/database.php';
$res = $conn->query("SELECT username, role, status FROM users");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
