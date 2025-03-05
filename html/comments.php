<?php
include('../php/config.php');

$sql='select * from dbo_user_comments';

if (isset($_POST['name'])){$fullName = $_POST['name'];
    $phone = $_POST['phone'];
    $comments=$_POST['comment'];

    if (empty($fullName) || empty($phone) || empty($comments)) {
        $message = "لطفاً تمام فیلدها را پر کنید.";
        echo $message;
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO dbo_user_comments (name,phone, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $fullName, $phone, $comments);

    if ($stmt->execute()) {
        $message = "نظر شما با موفقیت ثبت شد.";
        echo $message;
    } else {
        $message = "خطا در ثبت نظر: " . $stmt->error;
        echo $message;
    }}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>مشاوره آنلاین</title>
  <link rel="stylesheet" href="../css/moshaver.css" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
</head>
<body dir="rtl">
  <div class="container">
    <span class="big-circle"></span>
    <div class="form">
      <div class="contact-info">
        <div class="info">
          <div class="img">
            <img src="../image/moshavereh.jpg" alt="" class="img-moshaver">
          </div>
          <div class="information">
            <img src="../image/telegram.png" class="icon" alt="" />
            <p>dr.yara@</p>
          </div>
          <div class="information">
            <img src="../image/whatsapp.png" class="icon" alt="" />
            <p>09121000123</p>
          </div>
          <div class="information">
            <img src="../image/insta.png" class="icon" alt="" />
            <p>dr_yara</p>
          </div>
        </div>

        <div class="social-media">
          <p>راه‌های ارتباطی :</p>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>

      <div class="contact-form">
        <span class="circle one"></span>
        <span class="circle two"></span>

        <form id="consultationForm" method="POST"  autocomplete="off" >
          <h3 class="title">ثبت درخواست</h3>
          <div class="input-container">
            <input type="text" name="name" class="input" required />
            <label for="name">نام و نام خانوادگی</label>
            <span>Username</span>
          </div>
          <div class="input-container">
            <input type="tel" name="phone" class="input" required />
            <label for="phone">شماره تماس</label>
            <span>Phone</span>
          </div>
          <div class="input-container textarea">
            <textarea name="comment" class="input" required></textarea>
            <label for="comment">نظر شما</label>
            <span>Message</span>
          </div>
            <div class="input-container textarea">
                    <input
                            type="text"
                            placeholder=" a b c e d 0"
                            disabled
                            class="input-field disable input"
                    />
                    <button type="button" class="btn">تغییر</button>
            </div>
            <div class="input-container textarea">
                    <input
                            type="text"
                            placeholder="Enter Your Captcha"
                            class="input-field captcha input"
                    />
            </div>
            <br>
          <input type="submit" value="ارسال" class="btn"  />
        </form>
      </div>
    </div>
  </div>

  <script src="../js/app.js"></script>
</body>
</html>
