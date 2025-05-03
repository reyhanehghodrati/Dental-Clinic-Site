<?php
require_once 'config/database.php';
class reserve_doc_schedule_delete{

    public $id;

    public function doc_schedule_delete(){
        $conn = database::connect();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($this->id)) {

            // حذف نوبت از جدول doctor_schedule با شناسه دریافتی
            $sql = "DELETE FROM reservation_doctor_schedules WHERE id = '$this->id'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "<span style='color: green;'> با موفقیت حذف شد!</span>";
            } else {
                $_SESSION['message'] = "<span style='color: red;'>خطا در حذف: " . $conn->error . "</span>";
            }
        } else {
            $_SESSION['message'] = "<span style='color: orange;'>شناسه نامعتبر است.</span>";
        }
    }
}