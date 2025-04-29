<?php
require_once 'config.php';
require_once 'otp_sendSms.php';
session_start();

$input = $_POST['otp_input'];
$request_id=$_SESSION['request_id'];

$now = new DateTime('now',new DateTimeZone('Asia/Tehran'));
$sql="select * from reservation_phone_numbers  request_id='$request_id' and expire_time< '$now'";
$stmt = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($stmt);

if ($row) {
    $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">کد ارسال شده است</div>';
    $_SESSION['old_values']=[
        'otp_input'=>$input,
    ];
}else{
    $phone=$row['phone'];
    $STATUS0=0;
    $otp = rand(1000, 9999);
    $expires_at = $now->add(new DateInterval('PT20S'))->format('Y-m-d H:i:s');
    $sql = "INSERT INTO reservation_phone_numbers (phone_number, code,status,request_id, expire_time, resend_code)
        VALUES (?, ? , ? , ? , ?, 1)
      ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([ $phone, $otp, $STATUS0,$request_id,$expires_at]);
    $sms = new SendSms();
    $sms->sendMsgToUser($_POST['phone'], $otp);
    $_SESSION['request_id']=$request_id;
    $stmt->close();
}
?>