<?php
// اتصال به پایگاه داده
require_once 'config.php';
session_start();
$response = [];
if(!isset($_POST["token"]) || !isset($_SESSION["token"])){
    exit("token not set");
}
//check token:
if($_POST["token"]==$_SESSION["token"]) {
    if(time() >= $_SESSION["token-expire"]){
        unset($_SESSION["token"]);
        unset($_SESSION["token-expire"]);
        exit("token expire.reload the form");
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // دریافت داده‌ها از فرم
        $full_name = isset($_POST['full_name']) ? mysqli_real_escape_string($conn, $_POST['full_name']) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
        $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
        $doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
        $nobat = isset($_POST['nobat']) ? mysqli_real_escape_string($conn, $_POST['nobat']) : '';
//
//        $stm_doctor_id=$conn->prepare("select count(*) from dbo_add_doctors where id=?");
//        $stm_doctor_id->execute([$doctor_id]);
//        $doc_exist=$stm_doctor_id->fetchcolumn();
//        if(!$doc_exist){
//            echo json_encode([
//                'status'=>'error',
//                'message'=>'دکتری با این آیدی یافت نشد'
//            ]);
//            exit();
//        }
        // بررسی داده‌ها
        if (empty($full_name) || empty($email) || empty($phone) || empty($doctor_id) || empty($nobat)) {
            $response['status'] = 'error';
            $response['message'] = 'تمام فیلدها باید پر شوند.';
        } else {
            // تقسیم کردن nobat به روز و ساعت
            list($day_of_week, $time_slot) = explode(' - ', $nobat);

            // بررسی ظرفیت نوبت
            $query_check_capacity = "
            SELECT ds.max_capacity, COUNT(cr.id) AS reserved
            FROM doctor_schedule ds
            LEFT JOIN consultation_requests cr ON cr.doctor_id = ds.doctor_id AND cr.nobat = '$nobat'
            WHERE ds.doctor_id = $doctor_id
            GROUP BY ds.max_capacity
        ";
            $result_check_capacity = mysqli_query($conn, $query_check_capacity);
            $row_check = mysqli_fetch_assoc($result_check_capacity);
            $max_capacity = $row_check['max_capacity'];
            $reserved = $row_check['reserved'];
            //list($day_of_week, $time_slot) = explode(" - ", $nobat);

            if ($reserved >= $max_capacity) {
                $response['status'] = 'error';
                $response['message'] = 'ظرفیت این نوبت تکمیل شده است.';
            } else {

                // ذخیره‌سازی در پایگاه داده
                $query = "
                INSERT INTO consultation_requests (doctor_id, full_name, email, phone, time_id,data_miladi)
                VALUES ('$doctor_id', '$full_name', '$email', '$phone', '')
            ";

                if (mysqli_query($conn, $query)) {
                    // کاهش ظرفیت نوبت در جدول doctor_schedule
                    // استخراج روز و ساعت از متغیر nobat
                    list($day_of_week, $time_slot) = explode(" - ", $nobat);

                    // جستجو برای ساعت و روز
                    $query_update_capacity = "
                    UPDATE doctor_schedule ds
                    JOIN dbo_schedule_nobat sn ON sn.day_of_week = '$day_of_week' AND sn.time_slot = '$time_slot'
                    SET ds.max_capacity = ds.max_capacity - 1
                    WHERE ds.doctor_id = $doctor_id AND sn.id = ds.schedule_id
                ";

                    // اجرای Query
                    $result_update_capacity = mysqli_query($conn, $query_update_capacity);

                    if ($result_update_capacity) {
                        $response['status'] = 'success';
                        $response['message'] = 'رزرو نوبت با موفقیت ثبت شد .';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'خطا در کاهش ظرفیت.';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'خطا در ثبت رزرو.';
                }
            }
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'داده‌های ارسال‌شده صحیح نیستند.';
    }

}
else{
    exit("token not valid");
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
