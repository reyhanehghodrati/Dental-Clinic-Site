<?php

require_once 'config.php';
session_start();

header('Content-Type: application/json');
$response = [];

if(!isset($_POST["token"]) || !isset($_SESSION["token"])){
    exit(json_encode(['status'=>'error', 'message'=>'توکن ارسال نشده']));
}

if($_POST["token"] !== $_SESSION["token"]) {
    exit(json_encode(['status'=>'error', 'message'=>'توکن نامعتبر است']));
}

if(time() >= $_SESSION["token-expire"]){
    unset($_SESSION["token"]);
    unset($_SESSION["token-expire"]);
    exit(json_encode(['status'=>'error', 'message'=>'توکن منقضی شده، فرم را مجدد بارگذاری کنید.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $doctor_id = intval($_POST['doctor_id'] ?? 0);
    $nobat = mysqli_real_escape_string($conn, $_POST['nobat'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone) || empty($doctor_id) || empty($nobat)) {
        $response['status'] = 'error';
        $response['message'] = 'تمام فیلدها باید پر شوند.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

// دریافت اطلاعات زمان نوبت
    list($date_shamsi_part, $day_time_part) = explode(" | ", $nobat);
    list($day_of_week, $time_slot) = explode(" - ", $day_time_part);
    $date_shamsi = trim($date_shamsi_part);

// گرفتن time_id از جدول زمانبندی
    $query_time_to_id = "
    SELECT id 
    FROM dbo_schedule_nobat
    WHERE day_of_week = '$day_of_week' AND time_slot = '$time_slot'
";
    $result_check_time = mysqli_query($conn, $query_time_to_id);
    $row_check_time = mysqli_fetch_assoc($result_check_time);

    if (!$row_check_time) {
        echo json_encode([
            'status' => 'error',
            'message' => 'زمان انتخاب شده معتبر نیست.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $time_id = $row_check_time['id'];

// بررسی ظرفیت
    $query_check_capacity = "
    SELECT ds.max_capacity, COUNT(cr.id) AS reserved
    FROM doctor_schedule ds
    LEFT JOIN consultation_requests cr 
        ON cr.doctor_id = ds.doctor_id 
        AND cr.time_id = '$time_id' 
        AND cr.tarikh = '$date_shamsi'
    WHERE ds.doctor_id = $doctor_id AND ds.schedule_id = '$time_id'
    GROUP BY ds.max_capacity
";
    $result_check_capacity = mysqli_query($conn, $query_check_capacity);
    $row_check = mysqli_fetch_assoc($result_check_capacity);

    if (!$row_check || $row_check['reserved'] >= $row_check['max_capacity']) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ظرفیت این نوبت تکمیل شده است.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

// ثبت در پایگاه داده
    $query = "
    INSERT INTO consultation_requests (doctor_id, full_name, email, phone, time_id, tarikh)
    VALUES ('$doctor_id', '$full_name', '$email', '$phone', '$time_id', '$date_shamsi')
";

    if (mysqli_query($conn, $query)) {
        $query_update_capacity = "
        UPDATE doctor_schedule ds
        JOIN dbo_schedule_nobat sn ON ds.schedule_id = sn.id
        SET ds.max_capacity = ds.max_capacity - 1
        WHERE ds.doctor_id = $doctor_id AND sn.day_of_week = '$day_of_week' AND sn.time_slot = '$time_slot'
    ";
        $result_update_capacity = mysqli_query($conn, $query_update_capacity);

        if ($result_update_capacity) {
            echo json_encode([
                'status' => 'success',
                'message' => 'رزرو نوبت با موفقیت ثبت شد.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'خطا در کاهش ظرفیت.'
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'خطا در ثبت رزرو.'
        ], JSON_UNESCAPED_UNICODE);
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$response['status'] = 'error';
$response['message'] = 'درخواست نامعتبر.';
echo json_encode($response, JSON_UNESCAPED_UNICODE);

