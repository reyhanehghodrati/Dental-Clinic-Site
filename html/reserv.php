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
    <form id="reservationForm" method="POST" action="../php/reservation_save_request.php">
        <label>نام و نام خانوادگی:</label>
        <input type="text" name="full_name" value="<?= $_SESSION['full_name'] ?? '' ?>" required>

        <label>ایمیل:</label>
        <input type="email" name="email" value="<?= $_SESSION['email'] ?? '' ?>" required>

        <label>شماره تماس:</label>
        <input type="tel" name="phone" value="<?= $_SESSION['phone'] ?? '' ?>" required>

        <label>پزشک:</label>
        <select id="doctorSelect" name="doctor_id" required>
            <option value="">انتخاب پزشک</option>
            <?php require_once '../php/reservation_docs_get.php'; ?>
        </select>

        <label>هفته:</label>
        <select id="weekSelect" name="week">
            <option value="0" <?= isset($_SESSION['week']) && $_SESSION['week'] == '0' ? 'selected' : '' ?>>این هفته</option>
            <option value="1" <?= isset($_SESSION['week']) && $_SESSION['week'] == '1' ? 'selected' : '' ?>>هفته آینده</option>
            <option value="2" <?= isset($_SESSION['week']) && $_SESSION['week'] == '2' ? 'selected' : '' ?>>دو هفته بعد</option>
        </select>

        <label>نوبت:</label>
        <div id="timeContainer">ابتدا پزشک را انتخاب کنید</div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>"/>
        <input type="hidden" name="time_id" id="timeIdInput" value="<?= $_SESSION['time_id'] ?? '' ?>">
        <input type="hidden" name="tarikh" id="tarikhInput" value="<?= $_SESSION['tarikh'] ?? '' ?>">

        <button type="submit">ثبت نوبت</button>
    </form>

    <div id="responseMessage" style="margin-top: 20px;"></div>

    <script>
        const doctorSelect = document.getElementById('doctorSelect');
        const weekSelect = document.getElementById('weekSelect');
        const timeContainer = document.getElementById('timeContainer');
        const timeIdInput = document.getElementById('timeIdInput');
        const tarikhInput = document.getElementById('tarikhInput');

        const savedTimeId = "<?= $_SESSION['time_id'] ?? '' ?>";
        const savedDate = "<?= $_SESSION['tarikh'] ?? '' ?>";
        const savedDoctor = "<?= $_SESSION['doctor_id'] ?? '' ?>";
        const savedWeek = "<?= $_SESSION['week'] ?? '' ?>";

        function loadTimes() {
            const doctorId = doctorSelect.value;
            const week = weekSelect.value;

            if (doctorId) {
                fetch(`../php/reservation_nobat_get.php?doctor_id=${doctorId}&week=${week}`)
            .then(response => response.json())
                    .then(data => {
                        timeContainer.innerHTML = '';
                        data.forEach(item => {
                            const span = document.createElement('span');
                            span.classList.add('time-slot');
                            span.setAttribute('data-time-id', item.time_id);
                            span.setAttribute('data-date-miladi', item.date_miladi);
                            span.innerText = ${item.day_of_week} (${item.date_shamsi}) - ${item.time_slot};

                            if (item.capacity_left <= 0) {
                                span.classList.add('disabled');
                                span.style.backgroundColor = '#ccc';
                                span.style.cursor = 'not-allowed';
                                span.style.color = '#777';
                                span.title = 'ظرفیت تکمیل شده';
                            } else {
                                span.addEventListener("click", function () {
                                    document.querySelectorAll(".time-slot").forEach(t => t.classList.remove("selected"));
                                    this.classList.add("selected");
                                    timeIdInput.value = this.getAttribute('data-time-id');
                                    tarikhInput.value = this.getAttribute('data-date-miladi');
                                });

                                if (item.time_id === savedTimeId && item.date_miladi === savedDate) {
                                    span.classList.add("selected");
                                }
                            }
                            timeContainer.appendChild(span);
                        });
                    })
                    .catch(error => console.log('خطا در بارگذاری نوبت‌ها:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (savedDoctor) doctorSelect.value = savedDoctor;
            if (savedWeek) weekSelect.value = savedWeek;
            loadTimes();
        });

        doctorSelect.addEventListener('change', loadTimes);
        weekSelect.addEventListener('change', loadTimes);
    </script>

</body>
</html>