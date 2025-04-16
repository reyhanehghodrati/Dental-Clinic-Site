<?php
session_start();
include ('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['day']) && !empty($_POST['time'])) {

        $day = $conn->real_escape_string($_POST['day']);
        $time = $conn->real_escape_string($_POST['time']);
        $check_sql = "SELECT * FROM reservation_schedule_slots WHERE day_of_week = '$day' AND time_slot = '$time'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "<span style='color: red;'>این بازه زمانی قبلاً ثبت شده است!</span>";
        } else {
            $sql = "INSERT INTO reservation_schedule_slots (day_of_week, time_slot) VALUES ('$day', '$time')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "<span style='color: green;'>ثبت بازه با موفقیت انجام شد!</span>";
            } else {
                $_SESSION['message'] = "<span style='color: red;'>خطا در ثبت: " . $conn->error . "</span>";
            }
        }
    } else {
        $_SESSION['message'] = "<span style='color: orange;'>لطفاً تمام فیلدها را پر کنید.</span>";
    }

    header("Location: admin_dashboard.php");
    exit();
}
$conn->close();
?>