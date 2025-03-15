<?php
session_start();
include ('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $doctor_schedule_id = $conn->real_escape_string($_POST['doctor_schedule_id']);

    $sql = "INSERT INTO appointments (patient_name, patient_email, phone, doctor_schedule_id) 
            VALUES ('$full_name', '$email', '$phone', '$doctor_schedule_id')";

    if ($conn->query($sql) === TRUE) {
        echo "نوبت شما با موفقیت ثبت شد!";
    } else {
        echo "خطا در ثبت نوبت: " . $conn->error;
    }
}

$conn->close();
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_db";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("اتصال ناموفق: " . $conn->connect_error);
}

$sql = "SELECT id, full_name FROM doctors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['full_name']}</option>";
    }
} else {
    echo "<option value=''>پزشکی یافت نشد</option>";
}

$conn->close();
?>
