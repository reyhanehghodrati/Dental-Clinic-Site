
<?php
header('Content-Type: application/json');
require_once 'config.php';
require_once 'jdf.php';

$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$week_offset = isset($_GET['week']) ? intval($_GET['week']) : 0;

if (!$doctor_id) {
    echo json_encode([]);
    exit;
}

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

    $today = new DateTime();
    $today->modify('this week +' . ($week_offset * 7) . ' days');
    $weekdayNum = $daysMap[$day];

    $monday = clone $today;
    $monday->modify('monday this week');
    $exactDate = clone $monday;
    $exactDate->modify("+$weekdayNum days");
    $date_miladi = $exactDate->format('Y-m-d');

    // تبدیل به شمسی
    list($gy, $gm, $gd) = explode('-', $date_miladi);
    $jdate = jdate("j F Y", mktime(0, 0, 0, $gm, $gd, $gy));

    // گرفتن time_id برای این روز و ساعت
    $query_time_id = "
        SELECT id 
        FROM dbo_schedule_nobat 
        WHERE day_of_week = '$day' AND time_slot = '$time'
    ";
    $result_time_id = mysqli_query($conn, $query_time_id);
    $row_time_id = mysqli_fetch_assoc($result_time_id);

    $time_id = $row_time_id['id'];

    // محاسبه ظرفیت باقی‌مانده
    $query_capacity = "
        SELECT ds.max_capacity, COUNT(cr.id) AS reserved
        FROM doctor_schedule ds
        LEFT JOIN consultation_requests cr
            ON cr.doctor_id = ds.doctor_id
            AND cr.time_id = '$time_id'
            AND cr.tarikh = '$date_miladi'
        WHERE ds.doctor_id = $doctor_id AND ds.schedule_id = '$time_id'
        GROUP BY ds.max_capacity
    ";
    $result_capacity = mysqli_query($conn, $query_capacity);
    $row_capacity = mysqli_fetch_assoc($result_capacity);

    $max_capacity = $row_capacity['max_capacity'] ?? 0;
    $reserved = $row_capacity['reserved'] ?? 0;
    $capacityLeft = $max_capacity - $reserved;

    $response[] = [
        'day_of_week' => $day,
        'time_slot' => $time,
        'date_shamsi' => $jdate,
        'date_miladi' => $date_miladi,
        'capacity_left' => $capacityLeft,
        'time_id' => $time_id
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);