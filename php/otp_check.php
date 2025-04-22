<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['otp_input'];
    $id=
    $STATUS=1;
    if ($input == $_SESSION['otp']) {
        // Prepare an update statement
        $stmt = $conn->prepare("UPDATE reservation_requests SET STATUS = ? WHERE id = ?");
    }
        $stmt->bind_param("ii", $STATUS, $id);
        if ($stmt->execute()) {
        echo "<h2 style='color: green; text-align:center;'>رزرو با موفقیت انجام شد</h2>";
    } else {
        echo "<h2 style='color: red; text-align:center;'>کد وارد شده اشتباه است</h2>";
    }
}
?>