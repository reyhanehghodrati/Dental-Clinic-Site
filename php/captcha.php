<?php

session_start();
$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$random_str = str_shuffle($str);
$captcha_code = substr($random_str, 0, 6);
$_SESSION["captcha"] = $captcha_code;

$font = "../font/times_new_yorker.ttf";
$font_size = 20;
$img_width = 135;
$img_height = 35;

$image = imagecreate($img_width, $img_height);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

imagettftext($image, $font_size, 0, 15, 25, $text_color, $font, $captcha_code);

header("Content-Type: image/jpeg");
imagejpeg($image);
imagedestroy($image);

?>