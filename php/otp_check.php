<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['otp_input'];
    $request_id=$_SESSION['request_id'];
    $STATUS=1;

    if(!$input||!$request_id){
        echo"شماره یا کد وارد نشده است";
        exit();
    }
    $sql="select * from reservation_phone_numbers where request_id=? and code=? and status=0";
    $stmt=$conn-> prepare($sql);
    $stmt->bind_param("ii",$request_id,$input);
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result->num_rows===1) {
        $conn->query("update reservation_phone_numbers set status=1 where request_id='$request_id'");
        $conn->query("update reservation_requests set status=1 where id='$request_id' and STATUS=0");
        $_SESSION['message'] = '<div style="padding: 10px; background-color: #fafafa; color: #37ff00; border: 1px solid #37ff00; border-radius: 5px;">رزرو با موفقیت انجام شد</div>';

        header("Location: ../html/reserv.php");
        exit;
        //echo "<h2 style='color: green; text-align:center;'>رزرو با موفقیت انجام شد</h2>";
    } else {
        $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">کد نامعتبر است دوباره وارد کنید</div>';
        header("Location: otp_check_index.php");
        exit;
       // echo "<h2 style='color: red; text-align:center;'>کد وارد شده اشتباه است</h2>";
    }
}
?>