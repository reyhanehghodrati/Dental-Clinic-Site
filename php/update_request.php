<?php
require_once 'config.php';
session_start();
$response = [];


if($_POST['captcha_input']===$_SESSION['captcha']) {

    if (!isset($_POST["token"]) || !isset($_SESSION["token"])) {
        exit("token not set");
    }
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
            $date_time = $_POST['timeInput'] ?? '';

            if (!$name || !$phone || !$doctor_id || !$time_id || !$tarikh) {
                echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است'], JSON_UNESCAPED_UNICODE);
                exit;
            }
//            ------------time_slot check

            list($start_hour, $end_hour) = explode('-', $date_time);
            $start_hour = trim($start_hour);

            $now = new DateTime();
            $two_week = (new DateTime())->modify('+14 days');
//            $start_hour=14;
            $input_datetime = DateTime::createFromFormat('Y-m-d H', "$tarikh $start_hour");

            if (!$input_datetime) {
                echo json_encode([
                    'success' => false, 'message' => 'تاریخ انتخاب شده فرمتش معتبر نیست'], JSON_UNESCAPED_UNICODE);
                exit();
            }

            if ($input_datetime < $now || $input_datetime > $two_week) {
                echo json_encode(['success' => false, 'message' => 'تاریخ انتخاب شده معتبر نیست'], JSON_UNESCAPED_UNICODE);
                exit();
            }


//            ---------------- بررسی اینکه ظرفیت هنوز باقی‌ست یا نه

                $checkQuery = "
            SELECT ds.max_capacity, COUNT(cr.id) AS reserved
            FROM doctor_schedule ds
            LEFT JOIN consultation_requests cr
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

                $update_sql = 'UPDATE consultation_requests SET full_name=?, phone=?, doctor_id=?, last_updated=? WHERE id=?';
                $state = mysqli_prepare($conn, $update_sql);
                $stmt->bind_param("ssiis", $name,  $phone, $doctor_id, $time_id, $tarikh);
                mysqli_stmt_bind_param($state, "ssssi", $title, $subtitle, $background_image, $time, $id);

                if (mysqli_stmt_execute($state)) {
                    $_SESSION['message'] = "<p style='color: #42e230;'>اسلاید بروز شد.</p>";
                } else {
                    $_SESSION['message'] = "<p style='color: red;'>خطا در بروزرسانی اسلاید.</p>";
                }

                mysqli_stmt_close($state);

                header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
                exit();
            } else {
                $_SESSION['message'] = "<p style='color: red;'>" . implode("<br>", $errors) . "</p>";
            }


            $stmt->bind_param("sssiis", $name, $email, $phone, $doctor_id, $time_id, $tarikh);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'نوبت با موفقیت رزرو شد'], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['success' => false, 'message' => 'خطا در ذخیره اطلاعات'], JSON_UNESCAPED_UNICODE);
            }

            $stmt->close();
        }
    }
}
else{
    echo json_encode(['success' => false, 'message' => ' کد امنیتی نادرست  است '], JSON_UNESCAPED_UNICODE);
}
?>
