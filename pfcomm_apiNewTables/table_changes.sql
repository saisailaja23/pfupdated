-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2016 at 08:02 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 7.0.1

ALTER TABLE `profiles` CHANGE `waiting` `waiting_id` INT(5) NOT NULL;
ALTER TABLE `Countries` CHANGE `Name` `country` VARCHAR(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
 ALTER TABLE `Countries` CHANGE `Code` `country_code` VARCHAR(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
 ALTER TABLE `Countries` CHANGE `Id` `country_id` INT(5) NOT NULL AUTO_INCREMENT;
 ALTER TABLE `States` CHANGE `Country` `country_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `CountryCode` `CountryCode` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
  ALTER TABLE `States` DROP `CountryCode`;
   ALTER TABLE `States` ADD `state_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    ALTER TABLE `States` ADD `accounts_id` INT NOT NULL ;
