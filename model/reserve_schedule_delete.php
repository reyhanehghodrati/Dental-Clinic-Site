<?php
require_once 'config/database.php';

class reserve_schedule_delete{

    public $id;



    public function schedule_delete(){

        $conn=database::connect();
        if (isset($this->id) && is_numeric($this->id)) {
        $sql = "DELETE FROM reservation_schedule_slots WHERE id = '$this->id'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "<span style='color: green;'>بازه با موفقیت حذف شد!</span>";
        } else {
            $_SESSION['message'] = "<span style='color: red;'>خطا در حذف: " . $conn->error . "</span>";
        }
    } else {
$_SESSION['message'] = "<span style='color: orange;'>شناسه پزشک نامعتبر است.</span>";
}
    }
}