<?php
$to      = 'amhsoleimannejad@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: reyhanghodrati@gmail.com'       . "\r\n" .
             'Reply-To: reyhanghodrati@gmail.com' . "\r\n" .
             'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>
<?php 
$host = "localhost";
$username = "root";
$password = "";
$database = "dental_health";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['message'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
}
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    $message = "لطفاً تمام فیلدها را پر کنید.";
    echo "<script type='text/javascript'>alert('$message ');</script>";
    exit;
}

$stmt = $conn->prepare("INSERT INTO consultation_requests_moshavereh (name, email, phone, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $message);

if ($stmt->execute()) {
    $message = "درخواست شما با موفقیت ثبت شد.";
    echo "<script type='text/javascript'>alert('$message ');</script>";
    
    // ارسال ایمیل به ادمین
    $adminEmail = "reyhanghodrati@gmail.com";
    $subjectAdmin = "درخواست مشاوره جدید از $name";
    $messageAdmin = "نام: $name\nایمیل: $email\nشماره تماس: $phone\nپیام: $message";
    $adminHeaders = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    
    // ارسال ایمیل به کاربر
    $subjectUser = "درخواست شما دریافت شد";
    $messageUser = "سلام $name ،\n\nدرخواست شما دریافت شد. تیم مشاوره در اسرع وقت با شما تماس خواهد گرفت.\n\nبا تشکر";
    $userHeaders = "From: no-reply@yourwebsite.com\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    
    mail($adminEmail, $subjectAdmin, $messageAdmin, $adminHeaders);
    mail($email, $subjectUser, $messageUser, $userHeaders);
} else {
    $message = "خطا در ثبت درخواست: " . $stmt->error;
    echo "<script type='text/javascript'>alert('$message ');</script>";
}


$stmt->close();
$conn->close();
?>