<?php
require_once 'config.php';
session_start();
$response = [];

//if(!isset($_POST['captcha_input'])&&!isset($_SESSION['captcha'])){
//    echo json_encode(['success' => false, 'message' => ' کد امنیتی تنظیم نشده است '], JSON_UNESCAPED_UNICODE);
//    exit();
//}
//if($_POST['captcha_input']===$_SESSION['captcha']) {
//
//    if (!isset($_POST["token"]) || !isset($_SESSION["token"])) {
//        exit("token not set");
//    }
////check token:
//    if ($_POST["token"] == $_SESSION["token"]) {
//        if (time() >= $_SESSION["token-expire"]) {
//            unset($_SESSION["token"]);
//            unset($_SESSION["token-expire"]);
//            exit("token expire.reload the form");
//        }

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
                echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است'], JSON_UNESCAPED_UNICODE);
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
                echo json_encode([
                    'success' => false, 'message' => 'تاریخ انتخاب شده فرمتش معتبر نیست'], JSON_UNESCAPED_UNICODE);
                exit();
            }



            if ($input_start_time < $now || $input_start_time > $two_week ) {
                if($input_end_time<$now){
                echo json_encode(['success' => false, 'message' => 'تاریخ انتخاب شده معتبر نیست'], JSON_UNESCAPED_UNICODE);
                exit();
            }}


//            ---------------- بررسی اینکه ظرفیت هنوز باقی‌ست یا نه

                $checkQuery = "
            SELECT ds.max_capacity, COUNT(cr.id) AS reserved
            FROM reservation_doctor_schedules ds
            LEFT JOIN reservation_requests cr
                ON cr.doctor_id = ds.doctor_id
                AND cr.time_id = $time_id
                AND cr.tarikh = '$tarikh'
            WHERE ds.doctor_id = $doctor_id AND ds.schedule_id = $time_id
            GROUP BY ds.max_capacity
        ";
            $checkResult = mysqli_query($conn, $checkQuery);
            $row = mysqli_fetch_assoc($checkResult);

            $max_capacity = $row['max_capacity'] ?? 0;
            $reserved = $row['reserved'] ?? 0;

            if ($reserved >= $max_capacity) {
                echo json_encode(['success' => false, 'message' => 'ظرفیت این نوبت پر شده است'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // ذخیره درخواست مشاوره
            $stmt = $conn->prepare("
        INSERT INTO reservation_requests (full_name, email ,phone , doctor_id, time_id, tarikh) 
        VALUES (?, ?, ?, ?, ?,?)
    ");
            $stmt->bind_param("sssiis", $name, $email, $phone, $doctor_id, $time_id, $tarikh);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'نوبت با موفقیت رزرو شد'], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success' => false, 'message' => 'خطا در ذخیره اطلاعات'], JSON_UNESCAPED_UNICODE);
            }

            $stmt->close();
//        }
//    }
}
//else{
//    echo json_encode(['success' => false, 'message' => ' کد امنیتی نادرست  است '], JSON_UNESCAPED_UNICODE);
//}
?>
