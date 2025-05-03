<?php
require_once 'config/database.php';

class reserve_doc_insert{

public $name, $takhasos, $phone;

    public function insert_doctors(){

        $conn = database::connect();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "INSERT INTO reservation_doctor_profiles (name, takhasos, phone) VALUES ('$this->name', '$this->takhasos', '$this->phone')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "<span style='color: green;'>ثبت پزشک با موفقیت انجام شد!</span>";
        } else {
            $_SESSION['message'] = "<span style='color: red;'>خطا در ثبت: " . $conn->error . "</span>";
        }
    } else {
$_SESSION['message'] = "<span style='color: orange;'>لطفاً تمام فیلدها را پر کنید.</span>";
}
    }


}
