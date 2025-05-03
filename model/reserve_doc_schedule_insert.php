<?php
require_once 'config/database.php';

class reserve_doc_schedule_insert{

    public $doctor_id,$Schedule_id;



    function doc_schedule_insert(){
        $conn = database::connect();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $check_sql = "SELECT * FROM reservation_doctor_schedules WHERE doctor_id = '$this->doctor_id' AND schedule_id = '$this->Schedule_id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $_SESSION['message'] = "<span style='color: red;'>این پزشک قبلاً این زمان‌بندی را ثبت کرده است.</span>";
        } else {

            $insert_sql = "INSERT INTO reservation_doctor_schedules (doctor_id, schedule_id) VALUES ('$this->doctor_id', '$this->Schedule_id')";
            if ($conn->query($insert_sql) === TRUE) {
                $_SESSION['message'] = "<span style='color: green;'>ثبت نوبت کاری با موفقیت انجام شد!</span>";
            } else {
                $_SESSION['message'] = "<span style='color: red;'>خطا در ثبت: " . $conn->error . "</span>";
            }
        }
    } else {
$_SESSION['message'] = "<span style='color: orange;'>لطفاً تمام فیلدها را پر کنید.</span>";
}
    }
}