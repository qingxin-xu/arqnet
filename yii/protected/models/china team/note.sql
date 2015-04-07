/*
Navicat MySQL Data Transfer

Source Server         : 95
Source Server Version : 50519
Source Host           : 157.22.244.95:3306
Source Database       : arqbrand_dev03

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2015-04-07 09:18:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for note
-- ----------------------------
DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` mediumtext,
  `image_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `show_on_frontpage` tinyint(4) DEFAULT NULL,
  `stick_post` tinyint(4) DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `visibility_id` int(11) DEFAULT NULL,
  `ae_response_id` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `publish_time` varchar(10) DEFAULT '12:00 AM',
  `fb_message_id` bigint(20) DEFAULT NULL,
  `fb_image_ids` varchar(50) DEFAULT NULL,
  `fb_video_ids` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `note_user_idx` (`user_id`),
  KEY `note_aer_idx` (`ae_response_id`),
  KEY `_note_status` (`status_id`),
  KEY `_note_visibility` (`visibility_id`),
  CONSTRAINT `note_aer` FOREIGN KEY (`ae_response_id`) REFERENCES `ae_response` (`ae_response_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `note_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `_note_status` FOREIGN KEY (`status_id`) REFERENCES `note_status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `_note_visibility` FOREIGN KEY (`visibility_id`) REFERENCES `note_visibility` (`visibility_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1766 DEFAULT CHARSET=utf8;
