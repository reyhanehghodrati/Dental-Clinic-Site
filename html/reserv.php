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
  <h2 >فرم رزرو نوبت</h2>
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
    <input type="hidden" name="nobat" id="selectedNobat" required>

    <button type="submit">ثبت نوبت</button>
  </form>
  <div id="responseMessage" style="margin-top: 20px;"></div>
</div>

<script>
const doctorSelect = document.getElementById('doctorSelect');
const weekSelect = document.getElementById('weekSelect');
const timeContainer = document.getElementById('timeContainer');
const selectedNobat = document.getElementById('selectedNobat');
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
        timeContainer.innerHTML = '';

        data.forEach(item => {
          const span = document.createElement('span');
          span.classList.add('time-slot');
          span.setAttribute('data-time', item.time_slot);
          span.setAttribute('data-day', item.day_of_week);
          span.setAttribute('data-date', item.date_shamsi);
          span.innerText = `${item.day_of_week} (${item.date_shamsi}) - ${item.time_slot}`;

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
              selectedNobat.value = `${item.date_shamsi} | ${item.day_of_week} - ${item.time_slot}`;
            });
          }

          timeContainer.appendChild(span);
        });
      });
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
    // نمایش پیام در همان صفحه
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

doctorSelect.addEventListener('change', loadTimes);
weekSelect.addEventListener('change', loadTimes);
document.addEventListener('DOMContentLoaded', loadTimes);


</script>
</body>
</html>
