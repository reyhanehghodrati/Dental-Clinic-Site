<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>نوبت دهی</title>
    <style>
        body { font-family: sans-serif; direction: rtl; }
        .container { width: 600px; margin: auto; padding-top: 40px; }
        label, select, input, button { display: block; margin: 15px 0; width: 100%; }
        .time-slot { display: inline-block; padding: 10px; border: 1px solid #007bff; border-radius: 10px; margin: 5px; cursor: pointer; }
        .time-slot:hover { background-color: #e0f0ff; }
        .selected { background-color: #007bff; color: white; }
        .disabled { background-color: #ccc !important; color: #666; cursor: not-allowed !important; }
    </style>
</head>
<body>

<div class="container">
    <h2>فرم رزرو نوبت</h2>
    <form id="reservationForm" method="POST" action="../php/reservation_save_request.php">
        <label>نام و نام خانوادگی:</label>
        <input type="text" name="full_name" value="<?php echo $old_values['full_name'] ?? '' ?>" required>

        <label>ایمیل:</label>
        <input type="email" name="email" value="<?php echo $old_values['email'] ?? '' ?>" required>

        <label>شماره تماس:</label>
        <input type="tel" name="phone" value="<?php echo $old_values['phone'] ?? '' ?>" required>

        <label>پزشک:</label>
        <select id="doctorSelect" name="doctor_id"  required>
            <option value="">انتخاب پزشک</option>
            <!--            --><?php //require_once '../php/reservation_docs_get.php'; ?>
        </select>

        <label>هفته:</label>
        <select id="weekSelect" name="week">
            <option value="0" <?= (isset($old_values['week']) && $old_values['week'] == 0) ? 'selected' : '' ?>این هفته</option>
            <option value="1" <?= (isset($old_values['week']) && $old_values['week'] == 1) ? 'selected' : '' ?>>هفته آینده</option>
            <option value="2" <?= (isset($old_values['week']) && $old_values['week'] == 2) ? 'selected' : '' ?>>دو هفته بعد</option>
        </select>

        <label>نوبت:</label>
        <div id="timeContainer">ابتدا پزشک را انتخاب کنید</div>
        <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>"/>
        <input type="hidden" name="time_id" id="timeIdInput" value="<?php echo $old_values['time_id'] ?? '' ?>">
        <input type="hidden" name="tarikh" id="tarikhInput" value="<?php echo $old_values['tarikh'] ?? '' ?>">

        <label>کد امنیتی</label>
        <img src="../php/captcha.php" alt="captcha code">
        <input type="text" name="captcha_input" placeholder="کد را وارد کنید " value="<?php echo $old_values['captcha'] ?? '' ?>">

        <button type="submit" >ثبت نوبت</button>
    </form>

    <?php
    if (isset($_SESSION['message'])):
        ?>
    <p style="color: red"><?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?></p>
    <?php
    endif; ?>
    <div id="responseMessage" style="margin-top: 20px;"></div>
</div>
</body>
</html>