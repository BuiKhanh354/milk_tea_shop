<?php
$servername = "127.0.0.1";
$username = "root";
$pass = "";
$dbname = "vaa_the";

//Ket noi database

$conn = new mysqli($servername, $username, $pass, $dbname);

//Kiem tra ket noi
if($conn -> connect_error){
  die("Ket noi that bai: " . $conn -> connect_error);
}
  error_log("Ket noi thanh cong");
?>