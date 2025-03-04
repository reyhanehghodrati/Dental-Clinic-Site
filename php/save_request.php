<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "dental_health";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
}

$fullName = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$appointmentTime = $_POST['appointment_time'];

if (empty($fullName) || empty($email) || empty($phone) || empty($appointmentTime)) {
    $message = "لطفاً تمام فیلدها را پر کنید.";
    echo "<script type='text/javascript'>alert('$message');</script>";

    exit;
}

$stmt = $conn->prepare("INSERT INTO consultation_requests (full_name, email, phone, appointment_time) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullName, $email, $phone, $appointmentTime);

if ($stmt->execute()) {
    $message = "درخواست شما با موفقیت ثبت شد.";
    echo "<script type='text/javascript'>alert('$message');</script>";
} else {
    $message = "خطا در ثبت درخواست: " . $stmt->error;
    echo "<script type='text/javascript'>alert('$message');</script>";
}

$stmt->close();
$conn->close();
?>
