<?php
require 'app/config/database.php';
$res = $conn->query("DESCRIBE favorites");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
