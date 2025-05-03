<?php
require_once 'config/database.php';


class reserve_nobat_delete
{

    public $id;


    public function nobat_delete()
    {
        $conn = database::connect();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($this->id)) {

            $sql = "DELETE FROM reservation_requests WHERE id = '$this->id'";


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