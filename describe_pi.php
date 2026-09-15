<?php
require 'app/config/database.php';
$res = $conn->query("DESCRIBE product_ingredients");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
