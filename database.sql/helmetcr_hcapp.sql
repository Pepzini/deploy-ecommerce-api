-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL AUTO_INCREMENT,
  `adm_email` varchar(100) NOT NULL,
  `adm_password` varchar(100) NOT NULL,
  `adm_name` varchar(100) NOT NULL,
  `adm_date_registered` date NOT NULL,
  `adm_level` enum('DEFAULT','SUPER') NOT NULL,
  `adm_disabled` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_email` varchar(100) NOT NULL,
  `cust_address` varchar(100) NOT NULL,
  `cust_name` varchar(100) NOT NULL,
  `cust_phone` varchar(20) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `product_fragrance` varchar(100) NOT NULL,
  `product_origin` varchar(100) NOT NULL,
  `product_category` varchar(20) NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_name` (`product_name`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `order_product` varchar(100) NOT NULL,
  `order_quantity` varchar(100) NOT NULL,
  `order_customer` varchar(20) NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_id` (`order_id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


