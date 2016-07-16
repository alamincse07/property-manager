/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : wesom_tsuk_demo

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2014-06-20 01:48:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `export_drivers`
-- ----------------------------
DROP TABLE IF EXISTS `export_drivers`;
CREATE TABLE `export_drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of export_drivers
-- ----------------------------
INSERT INTO `export_drivers` VALUES ('1', 'xml', '0', '2014-06-19 23:11:53');
INSERT INTO `export_drivers` VALUES ('2', 'csv', '1', '2014-06-19 23:12:07');

-- ----------------------------
-- Table structure for `price_rates`
-- ----------------------------
DROP TABLE IF EXISTS `price_rates`;
CREATE TABLE `price_rates` (
  `id` int(11) NOT NULL,
  `estate_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `min_los` int(11) DEFAULT NULL,
  `nightly_price` float DEFAULT NULL,
  `weekly_price` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of price_rates
-- ----------------------------

-- ----------------------------
-- Table structure for `unavailability`
-- ----------------------------
DROP TABLE IF EXISTS `unavailability`;
CREATE TABLE `unavailability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estate_id` int(11) DEFAULT NULL,
  `unavailable_date` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of unavailability
-- ----------------------------
