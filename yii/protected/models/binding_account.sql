/*
Navicat MySQL Data Transfer

Source Server         : 95
Source Server Version : 50519
Source Host           : 157.22.244.95:3306
Source Database       : arqbrand_dev03

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2015-02-09 09:46:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for binding_account
-- ----------------------------
DROP TABLE IF EXISTS `binding_account`;
CREATE TABLE `binding_account` (
  `binding_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `arq_id` int(11) DEFAULT NULL,
  `third_party_id` varchar(255) DEFAULT NULL,
  `third_party` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `third_part_account` varchar(255) DEFAULT NULL,
  `auto_update` int(11) DEFAULT NULL,
  PRIMARY KEY (`binding_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
