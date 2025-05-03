<?php
require_once 'config/database.php';

class reserve_doc_delete
{
    public $id;

    public function delete_doc()
    {

        $conn = database::connect();
        $sql = "DELETE FROM reservation_doctor_profiles WHERE id = '$this->id)'";
        if (isset($this->id) && is_numeric($this->id)) {
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "<span style='color: green;'>پزشک با موفقیت حذف شد!</span>";
            } else {
                $_SESSION['message'] = "<span style='color: red;'>خطا در حذف: " . $conn->error . "</span>";
            }
        } else {
            $_SESSION['message'] = "<span style='color: orange;'>شناسه پزشک نامعتبر است.</span>";
        }


    }
}