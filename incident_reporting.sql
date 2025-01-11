-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 07:08 PM
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
-- Database: `incident_reporting`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `gps_location` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Ongoing','Completed') DEFAULT 'Pending',
  `details` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `student_id`, `date`, `time`, `gps_location`, `status`, `details`, `image`, `comment`) VALUES
(3, 'DC98778', '2025-01-02', '17:41:00', '2.966245973774339, 101.72746485611525', 'Ongoing', 'The lif is brokenn broo oh no', 'uploads/lifbroken.jpeg', 'The lif will be fixed on 7 pm , i already contact the person in charge for this. Thanks for reporting this info.'),
(4, 'DC98793', '2025-01-03', '00:04:00', '2.9658781235262603, 101.7274044173421', 'Completed', 'C1-03-01 , The light is not working bruh...', 'uploads/lampurosak.jpeg', 'The light is now fixed. You can check it now :D'),
(5, 'CS23442', '2025-01-03', '01:56:00', '2.9662483032504614, 101.72692663879542', 'Ongoing', 'C2-02-09 , The door handle is broken . Please we cant close the door', 'uploads/handledoorrosak.jpeg', 'The technician will come around 10 am. Please wait for the time being');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `student_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `student_id`) VALUES
(1, 'admin@example.com', '$2y$10$2WVe2lBW35EF0.Y2UKgkdO7b/RO1FSu4QwHO0TzpTKex/edNA/wpe', 'admin', NULL),
(2, 'danyshq.hakim04@gmail.com', '$2y$10$Zmi2ooT0WNzheKNkD8fZtuEkg8j2GhHc5rNnmb10JV7pyvbpjXGhu', 'student', 'DC98778'),
(3, 'sslambow@gmail.com', '$2y$10$bmEC2YekVC4LmmZghlntcutRCXJ8qMa0ol2OhlntrpofdmxKF07qC', 'student', 'DC98793'),
(4, 'alephensem@gmail.com', '$2y$10$NfFFqmMRNcDi.Sm9ie51ludwKXF..YffVk5bJ1Uc7McDyXfAf2FgS', 'student', 'CS23442');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
