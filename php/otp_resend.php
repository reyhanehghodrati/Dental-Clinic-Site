<?php
require_once 'config.php';
require_once 'otp_sendSms.php';
session_start();

header('Content-Type: application/json');

// گرفتن داده از بدنه JSON
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? null;


// چک کردن موجود بودن request_id
if (!isset($_SESSION['request_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'شناسه درخواست یافت نشد.']);
    exit;
}

$request_id = $_SESSION['request_id'];
$now = new DateTime('now', new DateTimeZone('Asia/Tehran'));

$sql = "SELECT * FROM reservation_phone_numbers WHERE request_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'درخواستی با این شناسه پیدا نشد.']);
    exit;
}

$phone = $row['phone_number'] ?? $row['phone'];
$otp = rand(1000, 9999);
$expires_at = $now->add(new DateInterval('PT2M'))->format('Y-m-d H:i:s'); // اعتبار دو دقیقه
$_SESSION['expire']=$expires_at;



$insertSql = "INSERT INTO reservation_phone_numbers (phone_number, code, status, request_id, expire_time)
              VALUES (?, ?, 0, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("siss", $phone, $otp, $request_id, $expires_at);
$insertStmt->execute();

// ارسال پیامک
$sms = new SendSms();
$sms->sendMsgToUser($phone, $otp);

// پاسخ موفقیت
echo json_encode([
    'status' => 'success',
    'message' => 'کد مجدد ارسال شد.'
]);
exit;
?>