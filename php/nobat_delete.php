<?php
session_start();
include('config.php');

if (isset($_GET['id'])) {
    // گرفتن id از URL
    $id = $conn->real_escape_string($_GET['id']);

    $sql = "DELETE FROM consultation_requests WHERE id = '$id'";
   

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "<span style='color: green;'> با موفقیت حذف شد!</span>";
    } else {
        $_SESSION['message'] = "<span style='color: red;'>خطا در حذف: " . $conn->error . "</span>";
    }
} else {
    $_SESSION['message'] = "<span style='color: orange;'>شناسه نامعتبر است.</span>";
}

// هدایت به صفحه داشبورد بعد از عملیات
header("Location: admin_dashboard.php");
exit();

$conn->close();
?>