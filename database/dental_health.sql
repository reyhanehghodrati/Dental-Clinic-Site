-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2025 at 12:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental_health`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultation_requests`
--

CREATE TABLE `consultation_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `docter` varchar(255) NOT NULL,
  `nobat` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_requests`
--

INSERT INTO `consultation_requests` (`id`, `full_name`, `email`, `phone`, `docter`, `nobat`, `created_at`) VALUES
(2, 'ریحانه قدرتی نسب', 'reyhanghodrati@gmail.com', '02156323544', 'dr-ahmadi', 'شنبه 25 اسفند - 14-16', '2025-03-12 10:40:08'),
(3, 'ریحانه قدرتی نسب', 'reyhanghodrati@gmail.com', '02156323544', 'dr-ahmadi', 'شنبه 25 اسفند - 14-16', '2025-03-12 11:00:15'),
(4, 'ریحانه قدرتی نسب', 'reyhanghodrati@gmail.com', '09109253995', 'dr-mohammadi', 'شنبه 25 اسفند - 14-16', '2025-03-12 11:35:54'),
(5, 'ریحانه قدرتی نسب', 'reyhanghodrati@gmail.com', '09109253995', 'dr-ahmadi', 'شنبه 25 اسفند - 14-16', '2025-03-12 11:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_requests_moshavereh`
--

CREATE TABLE `consultation_requests_moshavereh` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbo_add_doctors`
--

CREATE TABLE `dbo_add_doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `takhasos` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbo_add_doctors`
--

INSERT INTO `dbo_add_doctors` (`id`, `name`, `takhasos`, `phone`, `created_at`) VALUES
(152, 'غلامی', 'جراح', '09109253995', '2025-03-15 10:28:16'),
(154, 'قدرتی', 'دندان ', '0965142856', '2025-03-15 11:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `dbo_patient`
--

CREATE TABLE `dbo_patient` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `doctor_schedule_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbo_schedule_nobat`
--

CREATE TABLE `dbo_schedule_nobat` (
  `id` int(11) NOT NULL,
  `day_of_week` enum('شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه') NOT NULL,
  `time_slot` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbo_schedule_nobat`
--

INSERT INTO `dbo_schedule_nobat` (`id`, `day_of_week`, `time_slot`) VALUES
(1, 'دوشنبه', '9-12'),
(2, 'دوشنبه', '14-16'),
(3, 'یکشنبه', '14-16');

-- --------------------------------------------------------

--
-- Table structure for table `dbo_user_comments`
--

CREATE TABLE `dbo_user_comments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbo_user_comments`
--

INSERT INTO `dbo_user_comments` (`id`, `name`, `phone`, `comment`) VALUES
(2, 'ریحانه قدرتی', '9109253995', 'هم اینکه در ارسال بسته تاخیر هست هم اینکه جنس فیک بهمون دادن');

-- --------------------------------------------------------

--
-- Table structure for table `dental_health_responses`
--

CREATE TABLE `dental_health_responses` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `question1` text DEFAULT NULL,
  `question2` text DEFAULT NULL,
  `question3` text DEFAULT NULL,
  `question4` text DEFAULT NULL,
  `question5` text DEFAULT NULL,
  `question6` text DEFAULT NULL,
  `question7` text DEFAULT NULL,
  `question8` text DEFAULT NULL,
  `question9` text DEFAULT NULL,
  `question10` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

CREATE TABLE `doctor_schedule` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `max_capacity` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_schedule`
--

INSERT INTO `doctor_schedule` (`id`, `doctor_id`, `schedule_id`, `max_capacity`) VALUES
(6, 152, 1, 5),
(7, 154, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `background_image` text DEFAULT NULL,
  `created_in` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NULL DEFAULT current_timestamp(),
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `title`, `subtitle`, `background_image`, `created_in`, `last_updated`, `priority`) VALUES
(28, 'dhgyutdc47fdsfقبتلبل', '83gt58585555rsergs', '../image/vlog4 - Copy.jpg', '2025-03-01 13:02:21', '2025-03-04 05:12:44', 1),
(29, '89569gyfuy5نتادتاذنتلنرلنا', '83gtrsergsثبصثصgsgsg', '../image/vlog2 - Copy.jpg', '2025-03-04 06:55:12', '2025-03-04 05:12:02', 2),
(30, 'سلام ', 'fgfgfgfh66666ertgsh', '../image/vlog5 - Copy.jpg', '2025-03-04 06:50:10', '2025-03-04 05:31:19', 3);

-- --------------------------------------------------------

--
-- Table structure for table `wh_users`
--

CREATE TABLE `wh_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wh_users`
--

INSERT INTO `wh_users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin', '2025-02-25 06:33:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultation_requests`
--
ALTER TABLE `consultation_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultation_requests_moshavereh`
--
ALTER TABLE `consultation_requests_moshavereh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbo_add_doctors`
--
ALTER TABLE `dbo_add_doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbo_patient`
--
ALTER TABLE `dbo_patient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_schedule_id` (`doctor_schedule_id`);

--
-- Indexes for table `dbo_schedule_nobat`
--
ALTER TABLE `dbo_schedule_nobat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbo_user_comments`
--
ALTER TABLE `dbo_user_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dental_health_responses`
--
ALTER TABLE `dental_health_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wh_users`
--
ALTER TABLE `wh_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consultation_requests`
--
ALTER TABLE `consultation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `consultation_requests_moshavereh`
--
ALTER TABLE `consultation_requests_moshavereh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbo_add_doctors`
--
ALTER TABLE `dbo_add_doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `dbo_patient`
--
ALTER TABLE `dbo_patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbo_schedule_nobat`
--
ALTER TABLE `dbo_schedule_nobat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dbo_user_comments`
--
ALTER TABLE `dbo_user_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `dental_health_responses`
--
ALTER TABLE `dental_health_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `wh_users`
--
ALTER TABLE `wh_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbo_patient`
--
ALTER TABLE `dbo_patient`
  ADD CONSTRAINT `dbo_patient_ibfk_1` FOREIGN KEY (`doctor_schedule_id`) REFERENCES `doctor_schedule` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD CONSTRAINT `doctor_schedule_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `dbo_add_doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_schedule_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `dbo_schedule_nobat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
