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
ALTER TABLE `letter` ADD `isDefault` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '1-default letter,0-custom letter' AFTER `img`;

ALTER TABLE `letter` CHANGE `profile_id` `account_id` INT(11) NOT NULL;

ALTER TABLE `letters_sort` CHANGE `profile_id` `account_id` INT(11) NOT NULL;

ALTER TABLE `letters_sort` CHANGE `label` `letter_id` INT(20) NOT NULL;

ALTER TABLE `Account` ADD `agency_id` INT NOT NULL AFTER `Avatar`;

ALTER TABLE `RayVideoFiles` CHANGE `Owner` `account_id` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';

ALTER TABLE `Age_group` ADD PRIMARY KEY(`Age_group_id`);
ALTER TABLE `Age_group` CHANGE `Age_group_id` `Age_group_id` INT(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `age_group_preference` ADD PRIMARY KEY(`age_group_preference_id`);
ALTER TABLE `age_group_preference` CHANGE `age_group_preference_id` `age_group_preference_id` INT(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `adoption_type_preference` ADD PRIMARY KEY(`adoption_type_preference_id`);
ALTER TABLE `adoption_type_preference` CHANGE `adoption_type_preference_id` `adoption_type_preference_id` INT(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ContactDetails` ADD `website` VARCHAR(100) NOT NULL ;