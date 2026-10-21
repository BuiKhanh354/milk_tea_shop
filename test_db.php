<?php require_once 'app/config/database.php'; $result = $conn->query('SHOW TABLES'); while($row = $result->fetch_array()) { echo $row[0] . PHP_EOL; } ?>
