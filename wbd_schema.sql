-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2018 at 10:00 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wbd_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_token`
--

CREATE TABLE `access_token` (
  `token_id` varchar(32) NOT NULL,
  `username` varchar(20) NOT NULL,
  `expiry_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`token_id`, `username`, `expiry_time`) VALUES
('34e04648c49da200704da95d7a94e30a', 'cirno_strongest', '2018-10-26 09:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `book_pic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `description`, `book_pic`) VALUES
(1, 'WOOP', 'WOOP', 'That\'s the sound of da police', NULL),
(2, 'Principles of Engineering Thermodynamics', 'Michael J. Moran, Howard N. Shapiro', 'Integrated throughout the text are real-world applications that emphasize the relevance of thermodynamics principles to some of the most critical problems and issues of today, including a wealth of coverage of topics related to energy and the environment, biomedical/bioengineering, and emerging technologies.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `date_bought` date NOT NULL,
  `rating` int(1) NOT NULL DEFAULT '0',
  `review` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`order_id`, `book_id`, `username`, `amount`, `date_bought`, `rating`, `review`) VALUES
(1, 2, 'cirno_strongest', 5, '2018-10-24', 4, 'Why is this book so hard'),
(2, 2, 'cirno_strongest', 10, '2018-10-24', 0, NULL),
(3, 1, 'cirno_strongest', 2, '2018-10-24', 0, NULL),
(4, 1, 'kevin_fernaldy', 3, '2018-10-24', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(15) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone_num` varchar(15) NOT NULL,
  `display_pic` varchar(100) NOT NULL DEFAULT './icon/pp_default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `name`, `email`, `address`, `phone_num`, `display_pic`) VALUES
('cirno_strongest', '999AAA999', 'THE STRONGEST', '999_cirno_999@gmail.com', 'Misty Lake', '0123456789', './pp/bb1.gif'),
('EEEEEEEEEEEEEEE', 'eeeeeEEEEE', 'EEEEEEEEEEEEEEEEEEEE', 'eeeeeeeeee@eeeee.com', 'djnwqdjkndqkdnwkqdnk', '909088080', './pp/LOGIN REGISTER.jpg'),
('johnwick', 'defaultdance', 'test', 'johnwick@fortnite.com', 'pol', '00009999000', './icon/pp_default.jpg'),
('kevin_fernaldy', '010101010', 'Kevin Fernaldy', '13516109@std.stei.itb.ac.d', 'djnwqdjkndqkdnwkqdnk', '2147483647', './icon/pp_default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_token`
--
ALTER TABLE `access_token`
  ADD PRIMARY KEY (`token_id`,`username`),
  ADD KEY `fk_accesstoken_user_username` (`username`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`order_id`,`book_id`,`username`),
  ADD KEY `fk_transaction_user_username` (`username`),
  ADD KEY `fk_transaction_book_bookid` (`book_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_token`
--
ALTER TABLE `access_token`
  ADD CONSTRAINT `fk_accesstoken_user_username` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_book_bookid` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_user_username` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
