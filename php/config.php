<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "dental_health";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("اتصال به پایگاه داده امکان‌پذیر نیست: " . mysqli_connect_error());
}
?>
