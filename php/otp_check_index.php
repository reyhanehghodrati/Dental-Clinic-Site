<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>تایید کد</title>
</head>
<body style="text-align:center; margin-top:100px;">
<h2>کد ارسال شده به شماره <?php echo $_SESSION['mobile']; ?> را وارد کنید:</h2>
<form method="post" action="otp_check.php">
    <input type="text" name="otp_input" required>
    <br><br>
    <input type="submit" value="تایید">
</form>
</body>
</html>