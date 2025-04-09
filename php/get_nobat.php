<?php
header('Content-Type: application/json');
require_once 'config.php';  // اتصال به دیتابیس
require_once 'jdf.php';     // برای تبدیل تاریخ میلادی به شمسی

$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$week_offset = isset($_GET['week']) ? intval($_GET['week']) : 0;

if (!$doctor_id) {
    echo json_encode([]);
    exit;
}

// نگاشت روزهای هفته به شماره برای پیدا کردن تاریخ دقیق
$daysMap = [
    'شنبه' => 0,
    'یکشنبه' => 1,
    'دوشنبه' => 2,
    'سه‌شنبه' => 3,
    'چهارشنبه' => 4,
    'پنجشنبه' => 5,
    'جمعه' => 6,
];

$query = "
  SELECT s.day_of_week, s.time_slot, s.id AS schedule_id
  FROM doctor_schedule ds
  JOIN dbo_schedule_nobat s ON ds.schedule_id = s.id
  WHERE ds.doctor_id = $doctor_id
";

$result = mysqli_query($conn, $query);
$response = [];

while ($row = mysqli_fetch_assoc($result)) {
    $day = $row['day_of_week'];
    $time = $row['time_slot'];
    $schedule_id = $row['schedule_id'];

    // محاسبه تاریخ میلادی دقیق آن روز
    $today = new DateTime();
    $today->modify('this week +' . ($week_offset * 7) . ' days'); // محاسبه تاریخ دقیق هفته آینده
    $weekdayNum = $daysMap[$day];

    $monday = clone $today;
    $monday->modify('monday this week');
    $exactDate = clone $monday;
    $exactDate->modify("+$weekdayNum days");
    $dateStr = $exactDate->format('Y-m-d'); // تاریخ میلادی

    // تبدیل تاریخ به شمسی
    list($gy, $gm, $gd) = explode('-', $dateStr);
    $jdate = jdate("j F Y", mktime(0, 0, 0, $gm, $gd, $gy));

    // محاسبه ظرفیت باقی‌مانده
    $checkQuery = "
    SELECT COUNT(*) AS count
    FROM consultation_requests
    WHERE time_id = '$day - $time ' and  tarikh ='$jdate'
  ";
    $checkResult = mysqli_query($conn, $checkQuery);
    $countRow = mysqli_fetch_assoc($checkResult);
    $taken = $countRow['count'];
    $capacityLeft = 5 - $taken;

    $response[] = [
        'day_of_week' => $day,
        'time_slot' => $time,
        'date_shamsi' => $jdate,
        'capacity_left' => $capacityLeft
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
