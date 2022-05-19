-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2022 at 10:42 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ninicom_merce`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_email` varchar(100) NOT NULL,
  `cust_address` varchar(100) NOT NULL,
  `cust_name` varchar(100) NOT NULL,
  `cust_phone` varchar(20) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_email`, `cust_address`, `cust_name`, `cust_phone`) VALUES
(3, 'tumise@gmail.com', 'Adiyan,Gasline.', 'Tumise Olajide', '8765432345678'),
(4, 'jideniniola@gmail.com', 'Adiyan,Gasline.', 'Olajide Blesing Niniola', '9123455869');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `order_product` varchar(100) DEFAULT NULL,
  `order_quantity` varchar(100) NOT NULL,
  `order_customer` varchar(100) DEFAULT NULL,
  `order_remarks` text NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `order_date`, `order_product`, `order_quantity`, `order_customer`, `order_remarks`) VALUES
(10, '2022-05-16', 'La Cruise ', '56', 'Olajide Blesing Niniola', 'jnbhgvcfdxszawsedfgh');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `product_fragrance` varchar(100) NOT NULL,
  `product_origin` varchar(100) NOT NULL,
  `product_category` varchar(20) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_details` text NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_name` (`product_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_fragrance`, `product_origin`, `product_category`, `product_price`, `product_details`) VALUES
(1, 'Spiced Tonka Bean', 'fush seed', 'USA', 'mild', 0, ''),
(2, 'La Cruise ', 'onions', 'France', 'Slick', 0, 'l,mkjnbhgvcfxdzsa'),
(3, 'Candle Nini', 'Rose', 'USA', 'mild', 3000, 'Improve life quality'),
(4, 'La mish da', 'Spice nika', 'France', 'Clove', 6000, 'Could be used during the day and at night'),
(6, 'Tinashe', 'Rose', 'France', 'Clove', 400, ';lkjhtrfdewsasdfgvjnmk,.');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_time_reg` date NOT NULL,
  `user_level` enum('DEFAULT','SUPER') NOT NULL,
  `user_disabled` int(1) NOT NULL DEFAULT '0',
  `user_phone` int(15) NOT NULL,
  `user_address` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_password`, `user_name`, `user_time_reg`, `user_level`, `user_disabled`, `user_phone`, `user_address`) VALUES
(1, 'yemitula@gmail.com', 'yemi123', 'Adeyemi', '2019-06-10', 'SUPER', 0, 674456, 'mkjnbhgvf'),
(2, 'ruth@tulabyte.net', 'ruth123', 'Ruth Chisom Obidike', '2019-08-06', 'SUPER', 0, 813474545, 'I WILL NOT TELL YOU'),
(3, 'ijemba@gmail.com', 'gbese123', 'Murphy Ijemba Noise', '2019-08-06', 'DEFAULT', 0, 0, ''),
(4, 'raph@gmail.com', 'raph123', 'Obidike Raphael', '2020-09-04', 'DEFAULT', 0, 0, ''),
(12, 'jideniniola@gmail.com', 'testhis', 'Olajide Blessing Niniola', '2022-04-14', 'DEFAULT', 0, 917894563, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
