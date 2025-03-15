<?php
session_start();
include ('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['d-name']) && !empty($_POST['d-takhasos']) && !empty($_POST['d-phone'])) {

        $name = $conn->real_escape_string($_POST['d-name']);
        $takhasos = $conn->real_escape_string($_POST['d-takhasos']);
        $phone = $conn->real_escape_string($_POST['d-phone']);
        $sql = "INSERT INTO dbo_add_doctors (name, takhasos, phone) VALUES ('$name', '$takhasos', '$phone')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "<span style='color: green;'>ثبت پزشک با موفقیت انجام شد!</span>";
        } else {
            $_SESSION['message'] = "<span style='color: red;'>خطا در ثبت: " . $conn->error . "</span>";
        }
    } else {
        $_SESSION['message'] = "<span style='color: orange;'>لطفاً تمام فیلدها را پر کنید.</span>";
    }

    header("Location: admin_dashboard.php");
    exit();
}
$conn->close();
?>