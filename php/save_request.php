<?php
include ('config.php');

$fullName = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$nobat = $_POST['nobat'];

if (empty($fullName) || empty($email) || empty($phone) ||empty($nobat)) {
    $message = "لطفاً تمام فیلدها را پر کنید.";
    echo "<script type='text/javascript'>alert('$message');</script>";

    exit;
}

$stmt = $conn->prepare("INSERT INTO consultation_requests (full_name, email, phone, nobat) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullName, $email, $phone, $nobat);

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
