<?php
session_start();
include('config.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    $sql = "DELETE FROM reservation_doctor_profiles WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "<span style='color: green;'>پزشک با موفقیت حذف شد!</span>";
    } else {
        $_SESSION['message'] = "<span style='color: red;'>خطا در حذف: " . $conn->error . "</span>";
    }
} else {
    $_SESSION['message'] = "<span style='color: orange;'>شناسه پزشک نامعتبر است.</span>";
}

// هدایت به صفحه داشبورد بعد از عملیات
header("Location: admin_dashboard.php");
exit();

$conn->close();
?>