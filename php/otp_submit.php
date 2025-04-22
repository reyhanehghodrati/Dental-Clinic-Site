<?php
session_start();
require_once 'otp_sendSms.php';
require_once 'config.php';

    // هدایت به صفحه وارد کردن OTP
//    header("Location: otp_check_index.php");
//    exit;
//}
        $name = $_POST['full_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
        $doctor_id = intval($_POST['doctor_id'] ?? 0);
        $time_id = intval($_POST['time_id'] ?? 0);
        $tarikh = $_POST['tarikh'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$name || !$phone || !$doctor_id || !$time_id || !$tarikh) {
    echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است'], JSON_UNESCAPED_UNICODE);
    exit;
}

    $_SESSION['reservation_data'] = [
        'full_name' => $_POST['full_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'doctor_id' => $_POST['doctor_id'],
        'time_id' => $_POST['time_id'],
        'tarikh' => $_POST['tarikh'],
//        'captcha_input'=>$_POST['captcha_input']
    ];

    $_SESSION['otp'] = rand(1000, 9999);
    $sms = new SendSms();
    $sms->sendMsgToUser($_POST['phone'], $_SESSION['otp']);

    header("Location: otp_check_index.php");
    exit;
}
?>