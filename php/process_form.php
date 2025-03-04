<?php
$servername = "localhost"; 
$username = "root";    
$password = "";          
$dbname = "dental_health"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("اتصال به پایگاه داده ناموفق بود: " . $conn->connect_error);
}

// دریافت داده‌ها از فرم
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$question1 = $_POST['question1'];
$question2 = $_POST['question2'];
$question3 = $_POST['question3'];
$question4 = $_POST['question4'];
$question5 = $_POST['question5'];
$question6 = $_POST['question6'];
$question7 = $_POST['question7'];
$question8 = $_POST['question8'];
$question9 = $_POST['question9'];
$question10 = $_POST['question10'];

$sql = "INSERT INTO dental_health_responses (fullname, phone, email, question1, question2, question3, question4, question5, question6, question7, question8, question9, question10)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssss", 
    $fullname, 
    $phone, 
    $email, 
    $question1, 
    $question2, 
    $question3, 
    $question4, 
    $question5, 
    $question6, 
    $question7, 
    $question8, 
    $question9, 
    $question10
);

if ($stmt->execute()) {
    $message = "درخواست شما با موفقیت ثبت شد.";
    echo "<script type='text/javascript'>alert('$message ');</script>";
} else {
    $message = "خطا در ثبت درخواست: " . $stmt->error;
    echo "<script type='text/javascript'>alert('$message ');</script>";
}
// بستن اتصال
$stmt->close();
$conn->close();
?>
