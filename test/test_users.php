<?php
require 'app/config/database.php';
$res = $conn->query('SELECT id, username, full_name, role FROM users');
while($r = $res->fetch_assoc()) {
    print_r($r);
}
