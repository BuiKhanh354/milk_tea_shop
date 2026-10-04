<?php
require 'app/config/database.php';
$res = $conn->query('SELECT * FROM employee_shifts');
while($r = $res->fetch_assoc()) {
    print_r($r);
}
