<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $doctor_id = $_POST['doctor_id'];
    $schedule_id = $_POST['schedule_id'];
    if (!empty($doctor_id) && !empty($schedule_id)) {
        $check_sql = "SELECT * FROM doctor_schedule WHERE doctor_id = '$doctor_id' AND schedule_id = '$schedule_id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $_SESSION['message'] = "<span style='color: red;'>این پزشک قبلاً این زمان‌بندی را ثبت کرده است.</span>";
        } else {

            $insert_sql = "INSERT INTO doctor_schedule (doctor_id, schedule_id) VALUES ('$doctor_id', '$schedule_id')";
            if ($conn->query($insert_sql) === TRUE) {
                $_SESSION['message'] = "<span style='color: green;'>ثبت نوبت کاری با موفقیت انجام شد!</span>";
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
?>