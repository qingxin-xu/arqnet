/*
Navicat MySQL Data Transfer

Source Server         : 95
Source Server Version : 50519
Source Host           : 157.22.244.95:3306
Source Database       : arqbrand_dev03

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2015-04-07 09:20:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `birthday` date DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `location_city` varchar(45) DEFAULT NULL,
  `location_state` varchar(5) DEFAULT NULL,
  `relationship_status_id` int(11) DEFAULT NULL,
  `orientation_id` int(11) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `hometown_city` varchar(45) DEFAULT NULL,
  `hometown_state` varchar(5) DEFAULT NULL,
  `about_me` text,
  `meaning_of_life` text,
  `interests` text,
  `favorite_music` text,
  `favorite_movies` text,
  `favorite_books` text,
  `favorite_tv_shows` text,
  `favorite_quotes` text,
  `website` varchar(100) DEFAULT NULL,
  `twiiter_username` varchar(45) DEFAULT NULL,
  `facebook_username` varchar(45) DEFAULT NULL,
  `instagram_username` varchar(45) DEFAULT NULL,
  `googleplus_username` varchar(45) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(100) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `facebook_url` varchar(200) DEFAULT NULL,
  `twitter_url` varchar(200) DEFAULT NULL,
  `linkedin_url` varchar(200) DEFAULT NULL,
  `gplus_url` varchar(200) DEFAULT NULL,
  `secure_browsing` enum('on','off') DEFAULT NULL,
  `text_msg_login_notifications` enum('on','off') DEFAULT NULL,
  `email_login_notifications` enum('on','off') DEFAULT NULL,
  `max_login_attempts` int(11) DEFAULT NULL,
  `followers` enum('enable','disable') DEFAULT NULL,
  `who_can_contact_me` enum('friends','public','private') DEFAULT NULL,
  `who_can_look_me_up` enum('friends','public','private') DEFAULT NULL,
  `who_can_see_my_journals` enum('friends','public','private') DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `register_from` varchar(200) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `google_url` varchar(200) DEFAULT NULL,
  `occupation` varchar(200) DEFAULT NULL,
  `sign` varchar(200) DEFAULT NULL,
  `encrypt_pwd` varchar(200) DEFAULT NULL,
  `moderator_flag` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `uesr_relationship_status_idx` (`relationship_status_id`),
  KEY `user_orientation_idx` (`orientation_id`),
  KEY `user_image_idx` (`image_id`),
  CONSTRAINT `uesr_relationship_status` FOREIGN KEY (`relationship_status_id`) REFERENCES `relationship_status` (`relationship_status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_orientation` FOREIGN KEY (`orientation_id`) REFERENCES `orientation` (`orientation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
