<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>تایید شماره تماس</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: sans-serif;
            /*background-color: #f0f0f0;*/
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 13px;
        }

        input[type="submit"] {
            background-color: #2d7fe3;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0b3069;
        }

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="text-align:center; margin-top:100px;">
<div class="container">
<h2>کد ارسال شده به شماره <?php echo $_SESSION['mobile']; ?> را وارد کنید</h2>
<form method="post" action="reservation_save_request.php">
    <input type="text" name="otp_input" required>
    <br><br>
    <input type="submit" value="تایید">
</form>
</div>
</body>
</html>