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
//    $stmt->bind_param("ii",$request_id,$input);
    $stmt->execute([$request_id,$input]);
    $result=$stmt->get_result();



    $sql="select * from reservation_phone_numbers where request_id='$request_id' order by id DESC LIMIT 1";
    $stmt = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($stmt);

    $now = new DateTime('now',new DateTimeZone('Asia/Tehran'));
    if ($result->num_rows===1){
        if (new DateTime($row['expire_time'],new DateTimeZone('Asia/Tehran')) < $now) {

            $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">کد منقضی شده دوباره تلاش کنید</div>';
            $_SESSION['old_values'] = [
                'otp_input' => $input,
            ];
            header("Location: otp_check_index.php");
            exit;
        }
            $conn->query("update reservation_phone_numbers set status=1 where request_id='$request_id' and id='{$row['id']}'");
            $conn->query("update reservation_requests set status=1 where id='$request_id' and STATUS=0 ");
            $_SESSION['message'] = '<div style="padding: 10px; background-color: #fafafa; color: #37ff00; border: 1px solid #37ff00; border-radius: 5px;">رزرو با موفقیت انجام شد</div>';

            header("Location: ../html/reserv.php");
            exit;
        }else {
            $_SESSION['message'] = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">کد نامعتبر است دوباره وارد کنید</div>';
            header("Location: otp_check_index.php");
            exit;
        }

}
?>