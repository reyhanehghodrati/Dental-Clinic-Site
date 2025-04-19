<?php
session_start();
require_once 'otp_sendSms.php';


    // هدایت به صفحه وارد کردن OTP
//    header("Location: otp_check_index.php");
//    exit;
//}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['reservation_data'] = [
        'full_name' => $_POST['full_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'doctor_id' => $_POST['doctor_id'],
        'time_id' => $_POST['time_id'],
        'tarikh' => $_POST['tarikh'],
//        'captcha_input'=>$_POST['captcha_input']
    ];

    $_SESSION['otp'] = rand(1000, 9999); // یا هر روشی که OTP درست می‌کنی
    $sms = new SendSms();
    $sms->sendMsgToUser($_POST['phone'], $_SESSION['otp']);

    header("Location: otp_check_index.php");
    exit;
}
?>