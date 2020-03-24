-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2018 at 03:25 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_webshop`
--
CREATE DATABASE IF NOT EXISTS `lab_webshop` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `lab_webshop`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_basket`
--

CREATE TABLE `tbl_basket` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `id_cookie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cookie`
--

CREATE TABLE `tbl_cookie` (
  `id` int(11) NOT NULL,
  `cookie_user` varchar(200) NOT NULL,
  `id_user` int(11) NOT NULL,
  `logged_in` tinyint(1) NOT NULL,
  `expiration_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `tokenCode` varchar(100) NOT NULL,
  'userType' varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_basket`
--
ALTER TABLE `tbl_basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cookie` (`id_cookie`);

--
-- Indexes for table `tbl_cookie`
--
ALTER TABLE `tbl_cookie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_item` (`id_item`),
  ADD KEY `tbl_orders_ibfk_1` (`id_user`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_basket`
--
ALTER TABLE `tbl_basket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cookie`
--
ALTER TABLE `tbl_cookie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_basket`
--
ALTER TABLE `tbl_basket`
  ADD CONSTRAINT `tbl_basket_ibfk_1` FOREIGN KEY (`id_cookie`) REFERENCES `tbl_cookie` (`id`);

--
-- Constraints for table `tbl_cookie`
--
ALTER TABLE `tbl_cookie`
  ADD CONSTRAINT `tbl_cookie_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);

DROP TABLE IF EXISTS `tbl_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_products`
--

LOCK TABLES `tbl_products` WRITE;
/*!40000 ALTER TABLE `tbl_products` DISABLE KEYS */;
INSERT INTO `tbl_products` VALUES (1,'Test Product 1','New Product '),(2,'Second Product','Second!');
UNLOCK TABLES;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

DROP TABLE IF EXISTS `tbl_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sessions` (
  `id` varchar(32) NOT NULL,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE tbl_products ADD image varchar(100) NULL;
ALTER TABLE tbl_products ADD price DOUBLE NULL;
ALTER TABLE tbl_products ADD quantity INT NULL;
ALTER TABLE tbl_products ADD is_featured BOOL DEFAULT 0 NOT NULL;
ALTER TABLE tbl_products ADD created_at DATETIME NOT NULL;
ALTER TABLE tbl_products ADD updated_at DATETIME NULL;

ALTER TABLE tbl_user ADD address2 varchar(100) NULL;
ALTER TABLE tbl_user ADD state varchar(100) NULL;
ALTER TABLE tbl_user ADD zipCode varchar(10) NULL;
ALTER TABLE tbl_user ADD count INT NOT NULL;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;