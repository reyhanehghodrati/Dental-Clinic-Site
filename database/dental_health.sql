-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 01:24 PM
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
  `appointment_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'ریحانه قدرتی', '9109253995', 'هم اینکه در ارسال بسته تاخیر هست هم اینکه جنس فیک بهمون دادن'),
(3, 'سلیمانژاد', '91256561258', 'ارسال محصول فیک'),
(24, 'ds', '0', 'bx'),
(25, 'ds', '0', 'bx'),
(26, 'ds', '0', 'bx'),
(27, 'ds', '0', 'bx'),
(28, 'ds', '0', 'bx'),
(29, 'ds', '0', 'bx'),
(30, 'ds', '0', 'bx'),
(31, 'ds', '0', 'bx'),
(32, 'ds', '0', 'bx'),
(33, 'ds', '0', 'bx');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultation_requests_moshavereh`
--
ALTER TABLE `consultation_requests_moshavereh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `wh_users`
--
ALTER TABLE `wh_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
