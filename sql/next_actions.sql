-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 06:24 PM
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
-- Database: `mental`
--

-- --------------------------------------------------------

--
-- Table structure for table `next_actions`
--

CREATE TABLE `next_actions` (
  `email` varchar(256) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT 3,
  `due_date` date DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `next_actions`
--

INSERT INTO `next_actions` (`email`, `user_id`, `action`, `status`, `created_at`, `updated_at`, `category_id`, `priority`, `due_date`, `display_order`, `id`) VALUES
('0', 25, '太陽にあたる', 'completed', '2025-01-11 01:57:28', '2025-01-13 08:33:59', NULL, 3, NULL, 0, 1),
('0', 25, '基本情報技術者試験勉強', 'active', '2025-01-13 08:55:57', '2025-01-13 08:55:57', 1, 1, '2025-02-28', 4, 2),
('0', 25, '筋トレ', 'active', '2025-01-13 08:56:32', '2025-01-13 08:56:32', 2, 2, '2025-03-31', 5, 3),
('0', 27, '基本情報技術者試験勉強', 'active', '2025-01-13 14:42:02', '2025-01-13 14:42:02', 3, 1, '2025-02-28', 1, 4),
('0', 27, '筋トレ', 'active', '2025-01-13 14:42:17', '2025-01-13 14:42:17', 4, 2, '2025-03-31', 2, 5),
('7@gakushuin.ac.jp', 27, '太陽にあたる', 'completed', '2025-01-13 14:44:49', '2025-01-13 16:50:26', 4, 3, '2025-01-13', 3, 6),
('', 7, '基本情報技術者試験勉強', 'active', '2025-01-13 16:57:06', '2025-01-13 16:57:06', NULL, 1, '2025-02-28', 1, 7),
('', 7, '太陽にあたる', 'active', '2025-01-13 16:57:39', '2025-01-13 16:57:39', 6, 3, '2025-01-14', 2, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `next_actions`
--
ALTER TABLE `next_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `next_actions`
--
ALTER TABLE `next_actions`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `next_actions`
--
ALTER TABLE `next_actions`
  ADD CONSTRAINT `next_actions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
