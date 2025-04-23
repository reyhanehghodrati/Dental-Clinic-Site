--جدول ارسال و وضعیت تلفن کاربران otp
CREATE TABLE reservation_phone_numbers (
    id INT(11) NOT NULL AUTO_INCREMENT,
    phone_number VARCHAR(20) NOT NULL,
    code INT(11) NOT NULL,
    create_in DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status INT(11) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `reservation_phone_numbers` ADD `request_id` INT NOT NULL AFTER `status`;