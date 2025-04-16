<?php
function loadenv($file) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '#') === 0)
            continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

loadenv(__DIR__ . '/../.env');

$servername = $_ENV['servername'];
$username = $_ENV['username'];
$password = $_ENV['password'];
$dbname = $_ENV['dbname'];

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("اتصال به پایگاه داده امکان‌پذیر نیست: " . mysqli_connect_error());
}
?>