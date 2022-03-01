-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 01, 2022 at 09:20 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_email`, `cust_address`, `cust_name`, `cust_phone`) VALUES
(1, 'jideniniola@gmail.com', 'Adiyan,Gasline.', 'Olajide Blessing Niniola', '9843567098'),
(2, 'tosinolajide@gamil.com', 'Ig I were to be', 'Tosin Olajide ', '679764468'),
(3, 'tumise@hmail.com', 'Adiyan,Gasline.', 'Tumise Olajide', '8765432345678');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `order_product` varchar(100) NOT NULL,
  `order_quantity` varchar(100) NOT NULL,
  `order_customer` varchar(100) NOT NULL,
  `order_remarks` text NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `order_date`, `order_product`, `order_quantity`, `order_customer`, `order_remarks`) VALUES
(1, '2022-02-05', 'La Cruise Seeder', '65', 'Olajide Blessing Niniola', ',mkjnhbgvtfcdrwsatrfgbjhnkml'),
(2, '2022-02-16', 'Candle 2', '5', 'Tosin Olajide ', 'jkiuhygtfdrseawqasedfghbjnmk,');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_fragrance`, `product_origin`, `product_category`, `product_price`, `product_details`) VALUES
(1, 'Spiced Tonka Bean', 'fush seed', 'USA', 'mild', 0, ''),
(2, 'La Cruise Seeder', 'onions', 'France', 'Slick', 0, ''),
(3, 'Candle 3', 'Rose', 'USA', 'mild', 3000, 'Improve life quality'),
(4, 'La mish da', 'Spice nika', 'France', 'Clove', 6000, 'Could be used during the day and at night'),
(5, 'Candle 2', 'Lavendar', 'India', 'Slick thread', 5000, 'Good for couples');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
