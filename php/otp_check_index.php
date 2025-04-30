<?php
session_start();
require_once 'config.php';
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
    <form method="post" action="otp_check.php" id="otpForm">
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
        <p id="message"></p>
        <p id="timer"></p>
        <input type="submit" value="تایید">
    </form>
<br>
    <button id="resendButton" disabled>ارسال مجدد کد</button>
    <span id="countdownText">(فعال‌سازی در ۳۰ ثانیه)</span>
</div>
<?php
$request_id = $_SESSION['request_id'];
$sql = "SELECT create_in,expire_time FROM reservation_phone_numbers WHERE request_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$created_timestamp = $row ? strtotime($row['create_in']) : null;
$expires_timestamp=$row ? strtotime($row['expire_time']) : null;
var_dump(date('Y-m-d H:i:s', $created_timestamp));
var_dump(date('Y-m-d H:i:s', $expires_timestamp));
?>
<script>
    let timer;
    const expiresat = <?php echo $expires_timestamp ?? 'null'; ?>;
    const resendButton = document.getElementById('resendButton');
    const countdownText = document.getElementById('countdownText');
    const createAt=<?php echo $created_timestamp ?? 'null'; ?>;
    const delaySeconds = 50;

    let countdown = (createAt && expiresat && (expiresat < createAt + 50))
        ? (createAt + 50 - expiresat)
        : 0;
    console.log("createAt:", createAt);
    console.log("createAt + delaySeconds:", createAt + delaySeconds);
    console.log("countdown:", countdown);
    function startCountdown() {
        if (countdown <= 0) {
            resendButton.disabled = false;
            countdownText.textContent = "اکنون قابل استفاده است";
            return;
        }

        resendButton.disabled = true;
        countdownText.textContent = `فعال‌سازی در ${countdown} ثانیه`;

        timer = setInterval(() => {
            countdown--;
            if (countdown > 0) {
                countdownText.textContent = `فعال‌سازی در ${countdown} ثانیه`;
            } else {
                clearInterval(timer);
                countdownText.textContent = "اکنون قابل استفاده است";
                resendButton.disabled = false;
            }
        }, 1000);}

    resendButton.addEventListener('click', () => {




        // ارسال درخواست ریسند با fetch
        fetch('otp_resend.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ action: 'resend' }),
        })

        .then(response => response.json())
        .then(data => {
            console.log('ریسپانس سرور:', data);
            // نمایش پیام موفقیت یا خطا اگه بخوای
        })

        .catch(error => {
            console.error('خطا در ریسند:', error);
        });


        startCountdown();
    });
    // شروع تایمر به صورت دستی
    startCountdown();
</script>

</body>
</html>