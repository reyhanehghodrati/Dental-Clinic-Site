<?php
session_start();
require_once 'otp_sendSms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = $_POST['mobile'];


    // تولید کد OTP
    $otp = rand(1000, 9999);

    // ذخیره اطلاعات در سشن
    $_SESSION['mobile'] = $mobile;
    $_SESSION['otp'] = $otp;

    // ارسال پیامک
    $sms = new SendSms();
    $sms->sendMsgToUser($mobile, $otp);

    // هدایت به صفحه وارد کردن OTP
    header("Location: otp_check_index.php");
    exit;
}


?>