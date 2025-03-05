<?php
include('config.php');

if (isset($_GET['id']) && isset($_GET['direction'])) {
    $id = (int) $_GET['id'];
    $direction = $_GET['direction'];

    // دریافت اطلاعات اسلاید فعلی
    $query = "SELECT id, priority FROM site_settings WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $current = mysqli_fetch_assoc($result);

    if (!$current) {
        die("رکورد پیدا نشد.");
    }

    $current_priority = $current['priority'];

    // جستجوی رکوردی که باید جابجا شود
    if ($direction == 'up') {
        $swap_query = "SELECT id, priority FROM site_settings WHERE priority < $current_priority ORDER BY priority DESC LIMIT 1";
    } else {
        $swap_query = "SELECT id, priority FROM site_settings WHERE priority > $current_priority ORDER BY priority ASC LIMIT 1";
    }

    $swap_result = mysqli_query($conn, $swap_query);
    $swap = mysqli_fetch_assoc($swap_result);

    if ($swap) {
        // جابجا کردن مقدار اولویت‌ها
        mysqli_query($conn, "UPDATE site_settings SET priority = {$swap['priority']} WHERE id = $id");
        mysqli_query($conn, "UPDATE site_settings SET priority = $current_priority WHERE id = {$swap['id']}");
    }

    // بازگشت به داشبورد
    header("Location: admin_dashboard.php");
    exit();
}
?>


