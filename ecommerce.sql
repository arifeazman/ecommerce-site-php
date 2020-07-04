-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- Sunucu sürümü:                10.1.38-MariaDB - mariadb.org binary distribution
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               11.0.0.5919
-- --------------------------------------------------------


CREATE DATABASE IF NOT EXISTS `bitkidb` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_turkish_ci */;
USE `bitkidb`;


CREATE TABLE IF NOT EXISTS `member` (
  `mem_id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_firstname` varchar(255) COLLATE utf8_turkish_ci NOT NULL DEFAULT '',
  `mem_lastname` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `mem_password` varchar(255) COLLATE utf8_turkish_ci NOT NULL DEFAULT '',
  `mem_photo` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  `mem_tel` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  `mem_email` varchar(255) COLLATE utf8_turkish_ci NOT NULL DEFAULT '',
  `mem_address` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
  `mem_date` date NOT NULL DEFAULT '0000-00-00',
  `mem_role` varchar(255) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'regular_member',
  PRIMARY KEY (`mem_id`),
  UNIQUE KEY `mem_email` (`mem_email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `total_price` decimal(10,0) NOT NULL DEFAULT '0',
  `cart_status` varchar(50) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'active',
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `FK__member2` (`member_id`),
  CONSTRAINT `FK__member2` FOREIGN KEY (`member_id`) REFERENCES `member` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `unittype` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `order_status` varchar(50) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'waiting',
  `tracking_no` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `cart_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `FK__cart2` (`cart_id`),
  CONSTRAINT `FK__cart2` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `store` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `store_photo` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `mem_id` int(11) NOT NULL,
  PRIMARY KEY (`store_id`),
  UNIQUE KEY `store_name` (`store_name`),
  KEY `FK__member` (`mem_id`),
  CONSTRAINT `FK__member` FOREIGN KEY (`mem_id`) REFERENCES `member` (`mem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `product_explanation` varchar(400) COLLATE utf8_turkish_ci DEFAULT NULL,
  `product_unit_price` decimal(5,2) NOT NULL,
  `product_photo` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `in_store_quantity` decimal(5,2) DEFAULT NULL,
  `supply_date` date DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `FK__store` (`store_id`),
  KEY `FK_product_category` (`category_id`),
  KEY `FK_product_unittype` (`type_id`),
  CONSTRAINT `FK__store` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`),
  CONSTRAINT `FK_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `FK_product_unittype` FOREIGN KEY (`type_id`) REFERENCES `unittype` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


CREATE TABLE IF NOT EXISTS `product_cart` (
  `product_cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `toCart_quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`product_cart_id`),
  KEY `FK__product` (`product_id`),
  KEY `FK__cart` (`cart_id`),
  CONSTRAINT `FK__cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  CONSTRAINT `FK__product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
