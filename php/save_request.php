<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//    echo json_encode(['post_data' => $_POST], JSON_UNESCAPED_UNICODE);
//    exit;


    $name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $doctor_id = intval($_POST['doctor_id'] ?? 0);
    $time_id = intval($_POST['time_id'] ?? 0);
    $tarikh = $_POST['tarikh'] ?? ''; // فرمت YYYY-MM-DD

    if (!$name || !$phone || !$doctor_id || !$time_id || !$tarikh) {
        echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // بررسی اینکه ظرفیت هنوز باقی‌ست یا نه
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
    $stmt = $conn->prepare("
        INSERT INTO consultation_requests (full_name, phone, doctor_id, time_id, tarikh) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssiis", $name, $phone, $doctor_id, $time_id, $tarikh);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'نوبت با موفقیت رزرو شد'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => false, 'message' => 'خطا در ذخیره اطلاعات'], JSON_UNESCAPED_UNICODE);
    }

    $stmt->close();
}
?>
