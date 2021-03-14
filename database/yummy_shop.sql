-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2021 at 10:18 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yummy_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `fulfillment_status` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `shipping_cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `payment_status`, `fulfillment_status`, `order_date`, `shipping_cost`) VALUES
(20, 4, 'PENDING', 'CANCELLED', '2021-01-15 11:56:00', 5),
(21, 2, 'PENDING', 'PROCESSING', '2021-01-16 01:59:00', 5),
(26, 2, 'SUCCESS', 'PROCESSING', '2021-01-15 21:05:00', 3),
(37, 3, 'SUCCESS', 'PROCESSING', '2021-01-18 17:24:00', 6),
(43, 2, 'SUCCESS', 'PROCESSING', '2021-01-19 17:13:00', 3),
(44, 2, 'SUCCESS', 'PROCESSING', '2021-01-19 17:13:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_quantity` int(11) NOT NULL,
  `order_note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `order_quantity`, `order_note`) VALUES
(19, 20, 8, 1, NULL),
(20, 21, 9, 6, NULL),
(23, 26, 10, 1, 'test 123'),
(33, 37, 16, 3, 'Keep the change'),
(39, 43, 16, 2, ''),
(40, 44, 19, 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `product_details` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `price` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `type`, `product_details`, `image`, `price`) VALUES
(8, 'Spaghetti Carbonara', 'food', 'Simple and yummy carbonara', 'spaghetti_carbonara.jpg', '8.30'),
(9, 'Mushroom Soup', 'soup', 'Spicy and melting soup', 'mushroom_soup.jpg', '5.00'),
(10, 'Lamb Chop', 'food', 'Juicy and yummy lamb chop', 'lamb_chop.jpg', '34.50'),
(11, 'Spaghetti Bolognese', 'food', 'Slurpee bolognese', 'spaghetti_bolognese.jpg', '9.50'),
(16, 'Ice Cream', 'ice', 'Yummy ice cream rainbow', 'ice_cream.jpg', '6.20'),
(19, 'Watermelon Juice', 'juice', 'Test', 'watermelon_juice.jpg', '5.70');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL,
  `registered_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `phone_number`, `email`, `password`, `level`, `address`, `address2`, `city`, `state`, `zip_code`, `registered_date`) VALUES
(1, 'Muhammad', 'Anas', 175574904, 'anas@gmail.com', '$2y$10$ZZtF2RiYAc37hqDI8DXd..8nIwNYM9rB4/OQZDtME6hJw6T.iCCCS', 'ADMIN', NULL, NULL, NULL, NULL, NULL, '2020-12-30 14:07:28'),
(2, 'Ahmad', 'Kamil', 123456789, 'kamil@gmail.com', '$2y$10$FcQcxhe61FhIfvNCwAOS/eZPAUCAW0XU2dt0pl8nbhRnC8XIpdfk.', 'CUSTOMER', 'No 14', 'Taman Merbok', 'Merbok', 'Kedah', 8400, '2020-12-30 14:12:58'),
(4, 'Mohd', 'Ikhwan', 123312334, 'johar@gmail.com', '$2y$10$FsLVQeVe/gDX9js4i2bheuwAbJF0UiCp8kDvhZHfNeHMwIEatrWqa', 'CUSTOMER', 'No 98, Jalan Sejati 4', 'Taman Sejati Indah', 'Sungai Petani', 'Kedah', 8000, '2020-12-30 19:33:44'),
(31, 'Lee', 'Chong', 123456789, 'lee@gmail.com', '$2y$10$/ANQSf/A/JWnDFD6rOKC2.rNBSLZurJ1VIcN41NyRn18MLYyf0vbO', 'CUSTOMER', 'Jalan 14', 'Bandar Laguna Merbok', 'Sungai Petani', 'Kedah', 8000, '2021-01-15 23:24:48'),
(41, 'Yummy', 'Admin', 123456789, 'admin@localhost', '$2y$10$5Q2Qpue5CfNgxqqA4JjU7uI16pv8TYWZAv.PSakCVg0cVBy2NdTY6', 'ADMIN', NULL, NULL, NULL, NULL, NULL, '2021-01-18 18:11:49'),
(43, 'Ahmad', 'Ali', 112345678, 'ali@gmail.com', '$2y$10$fucAvrjZl9rO.f7vz4OiX.HPszvwj2Hbn2Y/.jionCev82beYa.8e', 'CUSTOMER', 'No 14', 'Taman Merbok Jaya', 'Merbok', 'Kedah', 8400, '2021-01-19 15:42:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_detail_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
