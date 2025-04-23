<?php
require_once 'config.php';
require_once 'otp_sendSms.php';
session_start();
$response = [];

function alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
//
//if(!isset($_POST['captcha_input'])&&!isset($_SESSION['captcha'])){
//$_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">کد امنیتی تنظیم نشده است</div>';
//header("Location: ../html/reserv.php");
//
////    echo json_encode(['success' => false, 'message' => ' کد امنیتی تنظیم نشده است '], JSON_UNESCAPED_UNICODE);
//    exit();
//}
//if($_POST['captcha_input']===$_SESSION['captcha']) {
//
//    if (!isset($_POST["token"]) || !isset($_SESSION["token"])) {
//        exit("token not set");
//    }
//check token:
    if ($_POST["token"] == $_SESSION["token"]) {
        if (time() >= $_SESSION["token-expire"]) {
            unset($_SESSION["token"]);
            unset($_SESSION["token-expire"]);
            exit("token expire.reload the form");
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//    echo json_encode(['post_data' => $_POST], JSON_UNESCAPED_UNICODE);
//    exit;

    $name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $doctor_id = intval($_POST['doctor_id'] ?? 0);
    $time_id = intval($_POST['time_id'] ?? 0);
    $tarikh = $_POST['tarikh'] ?? '';

    if (!$name || !$phone || !$doctor_id || !$time_id || !$tarikh) {
$_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">اطلاعات ناقص است</div>';
        header("Location: ../html/reserv.php");

//        echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است'], JSON_UNESCAPED_UNICODE);
        exit;
    }


    $quary_time_id = "SELECT * FROM reservation_schedule_slots WHERE id = $time_id";
    $checkResult = mysqli_query($conn, $quary_time_id);
    $time_row = mysqli_fetch_assoc($checkResult);

    $date_time = $time_row['time_slot'];
//            ------------time_slot check

    list($start_hour, $end_hour) = explode('-', $date_time);
    $start_hour = trim($start_hour);
    $end_hour = trim($end_hour);
    $time_zone=new DateTimeZone('Asia/Tehran');
    $now = new DateTime('now',new DateTimeZone('Asia/Tehran'));
    $two_week = (new DateTime('now',new DateTimeZone('Asia/Tehran')))->modify('+14 days');
    $input_start_time = DateTime::createFromFormat('Y-m-d H', "$tarikh $start_hour",$time_zone);
    $input_end_time = DateTime::createFromFormat('Y-m-d H', "$tarikh $end_hour",$time_zone);

    if (!$input_start_time  || !$input_end_time) {
        $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">تاریخ انتخاب شده فرمتش معتبر نیست</div>';
        header("Location: ../html/reserv.php");

//        echo json_encode([
//            'success' => false, 'message' => 'تاریخ انتخاب شده فرمتش معتبر نیست'], JSON_UNESCAPED_UNICODE);
        exit();
    }



    if ($input_start_time < $now || $input_start_time > $two_week ) {
        if($input_end_time<$now){
            $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">تاریخ انتخاب شده معتبر نیست</div>';
//            alert("تاریخ انتخاب شده معتبر نیست");
//            echo json_encode(['success' => false, 'message' => 'تاریخ انتخاب شده معتبر نیست'], JSON_UNESCAPED_UNICODE);
            header("Location: ../html/reserv.php");
            exit();
        }}


//            ---------------- بررسی اینکه ظرفیت هنوز باقی‌ست یا نه
    $STATUS=1;

    $checkQuery = "
            SELECT ds.max_capacity, COUNT(cr.id) AS reserved
            FROM reservation_doctor_schedules ds
            LEFT JOIN reservation_requests cr
                ON cr.doctor_id = ds.doctor_id
                AND cr.time_id = $time_id
                AND cr.tarikh = '$tarikh'
                AND cr.STATUS=1
            WHERE ds.doctor_id = $doctor_id AND ds.schedule_id = $time_id
            GROUP BY ds.max_capacity
        ";
    $checkResult = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($checkResult);


    $max_capacity = $row['max_capacity'] ?? 0;
    $reserved = $row['reserved'] ?? 0;

    if ($reserved >= $max_capacity) {
        $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">ظرفیت این نوبت پر شده است</div>';
        header("Location: ../html/reserv.php");
//        alert("ظرفیت این نوبت پر شده است");
//        echo json_encode(['success' => false, 'message' => 'ظرفیت این نوبت پر شده است'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $STATUS0=0;
    // ذخیره درخواست مشاوره
    $stmt = $conn->prepare("
        INSERT INTO reservation_requests (full_name, email ,phone , doctor_id, time_id, tarikh,STATUS) 
        VALUES (?, ?, ?, ?, ?,?,?)
    ");
    $stmt->bind_param("sssiisi", $name, $email, $phone, $doctor_id, $time_id, $tarikh,$STATUS0);
    if ($stmt->execute()) {
        $request_id=$conn->insert_id;
        $_SESSION['message'] = "<p style='color: #42e230;'>نوبت با موفقیت رزرو شد</p>";

//        header('Content-type:application/json');
//        echo json_encode(['success' => true, 'message' => 'نوبت با موفقیت رزرو شد'], JSON_UNESCAPED_UNICODE);
    } else {
        $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">خطا در ذخیره اطلاعات</div>';
        header("Location: ../html/reserv.php");
//        alert('خطا در ذخیره اطلاعات');
//        echo json_encode(['success' => false, 'message' => 'خطا در ذخیره اطلاعات'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $_SESSION['otp'] = rand(1000, 9999);
    $otp=$_SESSION['otp'];
    $sms = new SendSms();
    $sms->sendMsgToUser($_POST['phone'], $otp);
    $phone=$_SESSION['phone'];
    $_SESSION['request_id']=$request_id;
    $stmt->close();

    $stmt2 = $conn->prepare("
        INSERT INTO reservation_phone_numbers (phone_number , code, status,request_id)
        VALUES (?, ?, ?,?)
    ");
    $stmt2->bind_param("siii", $phone, $otp, $STATUS0,$request_id);
    $stmt2->execute();

        }



    }
//
//}
else{
    $_SESSION['message'] = "<p style='color: red;'>کد امنیتی نادرست  است</p>";
    header("Location: ../html/reserv.php");
    exit;
//    alert('کد امنیتی نادرست  است');
//    echo json_encode(['success' => false, 'message' => ' کد امنیتی نادرست  است '], JSON_UNESCAPED_UNICODE);
}
header("Location: otp_check_index.php");
exit();
?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html lang="fa">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>تایید شماره تماس</title>-->
<!--    <style>-->
<!--        * {-->
<!--            box-sizing: border-box;-->
<!--        }-->
<!---->
<!--        body, html {-->
<!--            height: 100%;-->
<!--            margin: 0;-->
<!--            font-family: sans-serif;-->
<!--            /*background-color: #f0f0f0;*/-->
<!--            display: flex;-->
<!--            align-items: center;-->
<!--            justify-content: center;-->
<!--        }-->
<!---->
<!--        .container {-->
<!--            background-color: white;-->
<!--            padding: 30px 40px;-->
<!--            border-radius: 16px;-->
<!--            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);-->
<!--            text-align: center;-->
<!--        }-->
<!---->
<!--        input[type="text"] {-->
<!--            width: 100%;-->
<!--            padding: 10px;-->
<!--            margin-top: 15px;-->
<!--            margin-bottom: 20px;-->
<!--            border-radius: 8px;-->
<!--            border: 1px solid #ccc;-->
<!--            font-size: 13px;-->
<!--        }-->
<!---->
<!--        input[type="submit"] {-->
<!--            background-color: #2d7fe3;-->
<!--            color: white;-->
<!--            border: none;-->
<!--            padding: 12px 24px;-->
<!--            font-size: 16px;-->
<!--            border-radius: 8px;-->
<!--            cursor: pointer;-->
<!--        }-->
<!---->
<!--        input[type="submit"]:hover {-->
<!--            background-color: #0b3069;-->
<!--        }-->
<!---->
<!--        h2 {-->
<!--            margin-bottom: 20px;-->
<!--        }-->
<!--    </style>-->
<!--</head>-->
<!--<body style="text-align:center; margin-top:100px;">-->
<!--<div class="container">-->
<!--    <h2>کد ارسال شده به شماره --><?php //echo $_SESSION['phone']; ?><!-- را وارد کنید</h2>-->
<!--    <form method="post" action="otp_check.php">-->
<!--        <input type="text" name="otp_input" required>-->
<!--        <br><br>-->
<!--        <input type="submit" value="تایید">-->
<!--    </form>-->
<!--</div>-->
<!--</body>-->
<!--</html>-->
