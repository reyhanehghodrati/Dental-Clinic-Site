<?php
require_once 'config/database.php';

class reserve_schedule_insert
{

    public $day;
    public $time;


    public function schedule_insert()
    {

        $conn = database::connect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($this->day) && !empty($this->time)) {
                $check_sql = "SELECT * FROM reservation_schedule_slots WHERE day_of_week = '$this->day' AND time_slot = '$this->time'";
                $result = $conn->query($check_sql);

                if ($result->num_rows > 0) {
                    $_SESSION['message'] = "<span style='color: red;'>این بازه زمانی قبلاً ثبت شده است!</span>";
                } else {
                    $sql = "INSERT INTO reservation_schedule_slots (day_of_week, time_slot) VALUES ('$this->day', '$this->time')";
                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['message'] = "<span style='color: green;'>ثبت بازه با موفقیت انجام شد!</span>";
                    } else {
                        $_SESSION['message'] = "<span style='color: red;'>خطا در ثبت: " . $conn->error . "</span>";
                    }
                }
            } else {
                $_SESSION['message'] = "<span style='color: orange;'>لطفاً تمام فیلدها را پر کنید.</span>";
            }

        }
    }
}

