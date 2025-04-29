<?php
session_start();
$old_values=$_SESSION['old_values'] ?? [];
unset($_SESSION['old_values']);
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


<h2>کد ارسال شده را وارد کنید</h2>
<form method="post" action="otp_check.php">
    <input type="text" name="otp_input" value="<?php echo $old_values['otp_input'] ?? '' ?>" required>
    <?php
    if (isset($_SESSION['message'])):
        ?>
        <p style="color: red"><?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?></p>
    <?php
    endif; ?>
    <button id="sendBtn" onclick="sendOTP()">ارسال کد</button>
    <p id="message"></p>
    <p id="timer"></p>
    <br><br>
    <input type="submit" value="تایید">

</form>
</div>

<script>
    let countdown;
    let timeLeft = 0;

    // function sendOTP() {
    //     const phone = document.getElementById("phone").value;
    //     const sendBtn = document.getElementById("sendBtn");
    //
    //     fetch("send_otp.php", {
    //         method: "POST",
    //         headers: {"Content-Type": "application/x-www-form-urlencoded"},
    //         body: "phone=" + encodeURIComponent(phone)
    //     })
    //         .then(res => res.text())
    //         .then(data => {
    //             document.getElementById("message").innerText = data;
    //             timeLeft = 30;
    //             startTimer();
    //             sendBtn.disabled = true;
    //         });
    // }

    function startTimer() {
        clearInterval(countdown);
        updateTimerDisplay();

        countdown = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();

            if (timeLeft <= 0) {
                clearInterval(countdown);
                document.getElementById("sendBtn").disabled = false;
                document.getElementById("timer").innerText = "می‌تونی دوباره کد بگیری.";
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        document.getElementById("timer").innerText = "زمان باقی‌مانده: " + timeLeft + " ثانیه";
    }
</script>
</body>
</html>