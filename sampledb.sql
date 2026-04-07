-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2023 at 11:02 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sampledb`
--

-- --------------------------------------------------------

--
-- Table structure for table `basic`
--

CREATE TABLE `basic` (
  `ID` int(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basic`
--

INSERT INTO `basic` (`ID`, `First_Name`, `Last_Name`) VALUES
(1, 'Daryll', 'Esteroza');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active | 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `created`, `modified`, `status`) VALUES
(1, 'Mga plano ko sa buhay', '2023-03-02', '2023-03-02 15:48:25', '2023-03-02 15:48:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events_description`
--

CREATE TABLE `events_description` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `date_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events_description`
--

INSERT INTO `events_description` (`id`, `name`, `visibility`, `date_time_recorder`) VALUES
(1, 'ResearchColloquium.png', 'visible', '2023-04-13 03:00:30'),
(2, 'FashionDesignExhibition.png', 'visible', '2023-04-13 03:01:17'),
(3, 'InnovationDialogues.png', 'visible', '2023-04-13 03:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `fashion_design_exhibition`
--

CREATE TABLE `fashion_design_exhibition` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `date_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fashion_design_exhibition`
--

INSERT INTO `fashion_design_exhibition` (`id`, `name`, `visibility`, `date_time_recorder`) VALUES
(1, 'fde1.png', 'visible', '2023-04-13 05:46:05'),
(2, 'fde2.png', 'visible', '2023-04-13 05:46:20'),
(3, 'fde4.png', 'visible', '2023-04-13 05:46:36'),
(4, 'fde5.png', 'visible', '2023-04-13 05:47:04'),
(5, 'fde6.png', 'visible', '2023-04-13 05:47:16'),
(6, 'fde7.png', 'visible', '2023-04-13 05:47:27'),
(7, 'fde8.png', 'visible', '2023-04-13 05:47:41'),
(8, 'fde9.png', 'visible', '2023-04-13 05:47:59'),
(9, 'fde10.png', 'visible', '2023-04-13 05:48:11'),
(10, 'fde11.png', 'visible', '2023-04-13 05:48:19'),
(11, 'fde12.png', 'visible', '2023-04-13 06:01:11'),
(12, 'fde13.png', 'visible', '2023-04-13 06:01:23');

-- --------------------------------------------------------

--
-- Table structure for table `innovation_dialogue`
--

CREATE TABLE `innovation_dialogue` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `date_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `innovation_dialogue`
--

INSERT INTO `innovation_dialogue` (`id`, `name`, `visibility`, `date_time_recorder`) VALUES
(1, '1.png', 'visible', '2023-04-13 05:35:27'),
(2, '2.png', 'visible', '2023-04-13 05:36:06'),
(3, '3.png', 'visible', '2023-04-13 05:36:31'),
(4, '4.png', 'visible', '2023-04-13 05:36:40'),
(5, '5.png', 'visible', '2023-04-13 05:36:51'),
(6, '6.png', 'visible', '2023-04-13 05:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `landingpage_video`
--

CREATE TABLE `landingpage_video` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `data_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landingpage_video`
--

INSERT INTO `landingpage_video` (`id`, `name`, `visibility`, `data_time_recorder`) VALUES
(1, 'video.mp4', 'visible', '2023-04-13 02:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `email_address`, `password`) VALUES
(1, 'esterozad@gmail.com', 'b1c32920ce11da6c6ef0945e73683e18'),
(2, 'dada@gmail.com', 'b01abf84324066bdb4eed4d5bf20f887'),
(3, 'joshuadelpuso@gmail.com', '123'),
(4, 'daryll', 'esterozaa');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `visibility` varchar(20) NOT NULL DEFAULT 'visible',
  `data_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `name`, `visibility`, `data_time_recorder`) VALUES
(1, 'Speaker2.png', 'visible', '2023-03-27 03:25:13'),
(2, 'Speaker1.png', 'visible', '2023-03-16 03:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `research_colloquium`
--

CREATE TABLE `research_colloquium` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `date_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_colloquium`
--

INSERT INTO `research_colloquium` (`id`, `name`, `visibility`, `date_time_recorder`) VALUES
(1, 'Picture1.png', 'visible', '2023-04-13 03:38:27'),
(2, 'Picture2.png', 'visible', '2023-04-13 03:38:48'),
(3, 'Picture3.png', 'visible', '2023-04-13 03:39:13'),
(4, 'Picture4.png', 'visible', '2023-04-13 03:39:47'),
(5, 'Picture5.png', 'visible', '2023-04-13 03:39:59'),
(6, 'Picture6.png', 'visible', '2023-04-13 03:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `speakers`
--

CREATE TABLE `speakers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `visibility` varchar(50) NOT NULL,
  `date_time_recorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speakers`
--

INSERT INTO `speakers` (`id`, `name`, `visibility`, `date_time_recorder`) VALUES
(1, '1.png', 'visible', '2023-03-27 04:56:17'),
(2, '2.png', 'visible', '2023-03-27 05:02:07'),
(3, '3.png', 'visible', '2023-03-27 05:02:52'),
(4, '4.png', 'visible', '2023-03-27 05:03:10'),
(5, '5.png', 'visible', '2023-03-27 05:03:27'),
(6, '6.png', 'visible', '2023-03-27 05:04:03'),
(7, '7.png', 'visible', '2023-03-27 05:04:17'),
(8, '8.png', 'visible', '2023-03-27 05:04:32'),
(9, '9.png', 'visible', '2023-03-27 05:04:43'),
(10, '10.png', 'visible', '2023-03-27 05:05:01'),
(11, '11.png', 'visible', '2023-03-27 05:05:12'),
(12, '12.png', 'visible', '2023-03-27 05:05:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basic`
--
ALTER TABLE `basic`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_description`
--
ALTER TABLE `events_description`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fashion_design_exhibition`
--
ALTER TABLE `fashion_design_exhibition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `innovation_dialogue`
--
ALTER TABLE `innovation_dialogue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landingpage_video`
--
ALTER TABLE `landingpage_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_colloquium`
--
ALTER TABLE `research_colloquium`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speakers`
--
ALTER TABLE `speakers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basic`
--
ALTER TABLE `basic`
  MODIFY `ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events_description`
--
ALTER TABLE `events_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fashion_design_exhibition`
--
ALTER TABLE `fashion_design_exhibition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `innovation_dialogue`
--
ALTER TABLE `innovation_dialogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `landingpage_video`
--
ALTER TABLE `landingpage_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `research_colloquium`
--
ALTER TABLE `research_colloquium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `speakers`
--
ALTER TABLE `speakers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
