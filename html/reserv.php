<?php
session_start();
$_SESSION["token"] = bin2hex(random_bytes(32));
// expiration token:
$_SESSION["token-expire"] = time() + 3600;
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>رزرو نوبت</title>
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
    <form id="reservationForm" method="POST" action="../php/save_request.php">
        <label>نام و نام خانوادگی:</label>
        <input type="text" name="full_name" required>

        <label>ایمیل:</label>
        <input type="email" name="email" required>

        <label>شماره تماس:</label>
        <input type="tel" name="phone" required>

        <label>پزشک:</label>
        <select id="doctorSelect" name="doctor_id" required>
            <option value="">انتخاب پزشک</option>
            <?php include '../php/get_doctors.php'; ?>
        </select>

        <label>هفته:</label>
        <select id="weekSelect">
            <option value="0">این هفته</option>
            <option value="1">هفته آینده</option>
            <option value="2">دو هفته بعد</option>
        </select>

        <label>نوبت:</label>
        <div id="timeContainer">ابتدا پزشک را انتخاب کنید</div>
        <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>"/>
        <input type="hidden" name="time_id" id="timeIdInput">
        <input type="hidden" name="tarikh" id="tarikhInput">

        <label>کد امنیتی</label>
        <img src="../php/captcha.php" alt="captcha code">
        <input type="text" name="captcha_input" placeholder="کد را وارد کنید ">

        <button type="submit">ثبت نوبت</button>
    </form>
    <div id="responseMessage" style="margin-top: 20px;"></div>
</div>

<script>
    const doctorSelect = document.getElementById('doctorSelect');
    const weekSelect = document.getElementById('weekSelect');
    const timeContainer = document.getElementById('timeContainer');
    const reservationForm = document.getElementById('reservationForm');
    const responseMessage = document.getElementById('responseMessage');

    // تابع بارگذاری نوبت‌ها
    function loadTimes() {
        const doctorId = doctorSelect.value;
        const week = weekSelect.value;

        if (doctorId) {
            fetch(`../php/get_nobat.php?doctor_id=${doctorId}&week=${week}`)
                .then(response => response.json())
                .then(data => {
                    console.log("داده‌های دریافت شده از سرور:", data);

                    timeContainer.innerHTML = ''; // پاکسازی محتویات قبلی
                    data.forEach(item => {
                        const span = document.createElement('span');
                        span.classList.add('time-slot');
                        span.setAttribute('data-time', item.time_slot);
                        span.setAttribute('data-day', item.day_of_week);
                        span.setAttribute('data-date', item.date_shamsi);
                        span.innerText = `${item.day_of_week} (${item.date_shamsi}) - ${item.time_slot}`;

                        // افزودن data-time_id به span (برای ذخیره time_id در درون data attributes)
                        span.setAttribute('data-time-id', item.time_id);

                        // بررسی ظرفیت و تغییر استایل نوبت پر
                        if (item.capacity_left <= 0) {
                            span.style.backgroundColor = '#ccc';
                            span.style.cursor = 'not-allowed';
                            span.style.color = '#777';
                            span.title = 'ظرفیت تکمیل شده';
                            span.classList.add('disabled');
                        } else {
                            span.addEventListener("click", function () {
                                document.querySelectorAll(".time-slot").forEach(t => t.classList.remove("selected"));
                                this.classList.add("selected");

                                // پر کردن input مخفی
                                document.getElementById("timeIdInput").value = this.getAttribute('data-time-id');
                                document.getElementById("tarikhInput").value = item.date_miladi; // تاریخ میلادی ذخیره می‌شود
                            });
                        }

                        timeContainer.appendChild(span);
                    });
                })
                .catch(error => console.log('خطا در بارگذاری نوبت‌ها:', error));
        }
    }

    // ارسال رزرو
    reservationForm.addEventListener('submit', function (e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به‌صورت پیش‌فرض

        const formData = new FormData(reservationForm);

        fetch('../php/save_request.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                responseMessage.innerHTML = `<div style="padding: 10px; background-color: ${data.status === 'success' ? '#d4edda' : '#f8d7da'}; color: ${data.status === 'success' ? '#155724' : '#721c24'}; border: 1px solid ${data.status === 'success' ? '#c3e6cb' : '#f5c6cb'}; border-radius: 5px;">${data.message}</div>`;

                if (data.status === 'success') {
                    reservationForm.reset();  // ریست کردن فرم پس از ارسال موفق
                    loadTimes();  // بارگذاری دوباره نوبت‌ها برای نمایش ظرفیت‌های جدید
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessage.innerHTML = '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">خطا در ارسال اطلاعات. لطفا دوباره تلاش کنید.</div>';
            });
    });

    // افزودن EventListener برای تغییر پزشک و هفته
    doctorSelect.addEventListener('change', loadTimes);
    weekSelect.addEventListener('change', loadTimes);

    // بارگذاری نوبت‌ها پس از لود شدن صفحه
    document.addEventListener('DOMContentLoaded', loadTimes);
</script>
</body>
</html>
