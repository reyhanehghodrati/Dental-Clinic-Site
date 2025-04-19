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
            font-size: 16px;
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
<body>
<div class="container">
    <h2>لطفاً شماره موبایل خود را وارد کنید</h2>
    <form action="otp_submit.php" method="post">
        <input type="text" name="phone" placeholder="مثلاً 09121234567" required>
        <input type="submit" value="ثبت">
    </form>
</div>
</body>
</html>
