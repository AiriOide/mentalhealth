-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 06:26 PM
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
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `name` varchar(64) NOT NULL,
  `birthday` date NOT NULL,
  `gender` text NOT NULL,
  `job` varchar(64) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `first_login` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`name`, `birthday`, `gender`, `job`, `email`, `password`, `first_login`) VALUES
('AIRI OIDE', '2024-12-30', 'female', '学生', '123@gmail.com', 'abc', '2025-01-13'),
('ぽん', '2024-12-30', 'female', '学生', '1@cat.com', 'abc', '2025-01-13'),
('pon', '2025-01-13', 'other', '学生', '1@gmail.com', '$2y$10$YpBuJnvrvIFmSGrR1NVFQuZiyrqRV9THT4qfwpxHIIwohPfPseEte', NULL),
('Airi', '2024-12-31', 'female', '学生', '21021237@gakushuin.ac.jp', 'abc', '2025-01-13'),
('ぽん', '2025-01-13', 'female', '学生', '39@gmail.com', '$2y$10$Dd2IosroHuGrFnB1BlzptuNFA0d7E60eXO3zkr6gSxJiPU/iAA6We', '2025-01-13'),
('Untitled', '2025-01-14', 'female', '学生', '7@gakushuin.ac.jp', '$2y$10$Y40qnPmOsPYdHmgeMXYDUu2ZFRFKECzbvg/0vFOyE7leMiVbVzTD6', NULL),
('Airi', '2024-12-30', 'female', '学生', 'a@cat.com', 'az', '2025-01-13'),
('Airi', '2024-12-30', 'female', '学生', 'abc@cat.com', 'az', '2025-01-13'),
('Airi', '2024-12-30', 'female', '学生', 'q@cat.com', 'az', '2025-01-13'),
('山崎大助', '2002-01-01', '女性', '学生', 'user_1@example.com', '', '2025-01-13'),
('', '0000-00-00', 'female', '', 'user_2@example.com', '', '2025-01-13'),
('Airi', '2024-12-02', 'female', '学生', 'user_3@example.com', '', '2025-01-13'),
('ぽん', '2025-01-11', 'female', '学生', 'z@cat.com', '$2y$10$yrgq8YZUwYQzTuCPTOpcQ.US9lctSoBsUKkdyvrsgaNhh.KaeFYbu', '2025-01-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `email` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
