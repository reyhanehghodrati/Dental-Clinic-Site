<?php
include_once("jdf.php");

// تاریخ شمسی مورد نظر
$shamsi_year = 1404;
$shamsi_month = 1;
$shamsi_day = 21;

// تبدیل تاریخ شمسی به میلادی
$gregorian_date = jalali_to_gregorian($shamsi_year, $shamsi_month, $shamsi_day);

// نمایش تاریخ میلادی
echo "تاریخ میلادی: " . $gregorian_date[0] . "-" . $gregorian_date[1] . "-" . $gregorian_date[2];
?>
