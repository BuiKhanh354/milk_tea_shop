<?php
require 'app/config/database.php';
$res = $conn->query('DESCRIBE tables');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
