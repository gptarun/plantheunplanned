-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2020 at 09:38 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plantheunplanned`
--

-- --------------------------------------------------------

--
-- Table structure for table `trek_leader`
--

CREATE TABLE `trek_leader` (
  `leader_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `bio` mediumtext DEFAULT NULL,
  `treks` mediumtext DEFAULT NULL,
  `upi` varchar(100) NOT NULL,
  `t_size` varchar(100) NOT NULL,
  `banglore_address` mediumtext DEFAULT NULL,
  `is_active` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (leader_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trek_leader`
--

INSERT INTO `trek_leader` (`leader_id`, `name`, `mobile`, `email`, `bio`, `treks`, `upi`, `t_size`, `banglore_address`, `is_active`, `created_at`) VALUES
(1, 'Jatin Munvar', '9035182699', 'accordify.solutions@gmail.com', 'Young, energetic and full of enthusiasm is what describes him best.Being an explorer, he strongly believes that the best part of memories is to create one.He would leave no stones unturned, to mark his footprints on your memory lane.\r\n', NULL, '', 'M', NULL, '1', '2019-12-20 06:45:37'),
(2, 'Tarun Rathi', '9428909952', 'tarung1201@gmail.com', 'An adventure junkie by passion and a techie by profession. He likes exploring the mountain, beaches, and nature. He believes adventure is worthwhile.\r\n', NULL, '', 'M\r\n', NULL, '1', '2019-12-20 06:47:02'),
(3, 'Isha Chaudhari', '9561422797', 'tarung1201@gmail.com', 'Traveling is her passion and likes staying connected to nature. She makes sure each of your experience is fun-filled and full of energy.\r\n', 'Kudremukh, kodachadri, Gokarna, Wayanad, Kotagiri, Hampi, Tadianmol, Makalidurga , Munnar Top Station, Kumara Parvatha  \r\n', '', 'S', 'Palm - 208, SJR Park Vista, Off Harlur Main road, Bangalore - 560102\r\n', '1', '2019-12-20 06:47:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trek_leader`
--
ALTER TABLE `trek_leader`
  ADD PRIMARY KEY (`leader_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `trek_leader`
--
ALTER TABLE `trek_leader`
  MODIFY `leader_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
