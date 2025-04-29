--ویرایش اکسپایر شدن OTP
ALTER TABLE `reservation_phone_numbers` ADD `expire_time` DATETIME;
ALTER TABLE `reservation_phone_numbers` ADD `resend_code` INT default 0;