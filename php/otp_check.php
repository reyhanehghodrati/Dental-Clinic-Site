<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['otp_input'];

    if ($input == $_SESSION['otp']) {
        echo "<h2 style='color: green; text-align:center;'>رزرو با موفقیت انجام شد</h2>";
    } else {
        echo "<h2 style='color: red; text-align:center;'>کد وارد شده اشتباه است</h2>";
    }
}
?>