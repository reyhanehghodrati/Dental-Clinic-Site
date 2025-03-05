<?php
include('../php/config.php');

$sql='select * from dbo_user_comments';

$fullName = $_POST['name'];
$phone = $_POST['phone'];
$comments=$_POST['comment'];

if (empty($fullName) || empty($phone) || empty($comments)) {
    $message = "لطفاً تمام فیلدها را پر کنید.";
    echo "<script type='text/javascript'>alert('$message');</script>";
    exit;
}

$stmt = $conn->prepare("INSERT INTO dbo_user_comments (name,phone, comment) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $fullName, $phone, $comments);

if ($stmt->execute()) {
    $message = "نظر شما با موفقیت ثبت شد.";
    echo "<script type='text/javascript'>alert('$message');</script>";
} else {
    $message = "خطا در ثبت نظر: " . $stmt->error;
    echo "<script type='text/javascript'>alert('$message');</script>";
}

$stmt->close();
$conn->close();
header("Location: ../html/comments.php");
exit();
?>
