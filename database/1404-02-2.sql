--جدول ارسال و وضعیت تلفن کاربران otp
CREATE TABLE `resevation_phone_numbers` (
                                            `id` int(11) NOT NULL,
                                            `phone_number` int(11) NOT NULL,
                                            `code` int(11) NOT NULL,
                                            `create_in` datetime NOT NULL DEFAULT current_timestamp(),
                                            `status` int(11) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
