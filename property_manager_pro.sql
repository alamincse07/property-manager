/*
Navicat MySQL Data Transfer

Source Server         : MYSQL
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : property_manager_pro

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2016-07-16 04:34:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `attribute_name`
-- ----------------------------
DROP TABLE IF EXISTS `attribute_name`;
CREATE TABLE `attribute_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `position` smallint(6) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of attribute_name
-- ----------------------------
INSERT INTO `attribute_name` VALUES ('17', 'Interior1', '1', '2014-05-21 06:42:57');
INSERT INTO `attribute_name` VALUES ('18', 'External Hardware', '2', '2014-05-21 06:44:48');
INSERT INTO `attribute_name` VALUES ('19', 'Environmental Specifications', '3', '2014-05-21 06:44:58');

-- ----------------------------
-- Table structure for `auth_log`
-- ----------------------------
DROP TABLE IF EXISTS `auth_log`;
CREATE TABLE `auth_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_platform` varchar(64) NOT NULL,
  `browser` varchar(64) NOT NULL,
  `agent` varchar(100) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of auth_log
-- ----------------------------

-- ----------------------------
-- Table structure for `availability`
-- ----------------------------
DROP TABLE IF EXISTS `availability`;
CREATE TABLE `availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of availability
-- ----------------------------
INSERT INTO `availability` VALUES ('1', 'daily', '1');
INSERT INTO `availability` VALUES ('2', 'weekly', '1');
INSERT INTO `availability` VALUES ('3', 'monthly', '1');

-- ----------------------------
-- Table structure for `calendar`
-- ----------------------------
DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `date` date NOT NULL,
  `data` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of calendar
-- ----------------------------

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('2', 'Hotel');
INSERT INTO `category` VALUES ('3', 'Vacation Rental');

-- ----------------------------
-- Table structure for `date_format`
-- ----------------------------
DROP TABLE IF EXISTS `date_format`;
CREATE TABLE `date_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js` varchar(20) NOT NULL,
  `php` varchar(20) NOT NULL,
  `sql` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of date_format
-- ----------------------------
INSERT INTO `date_format` VALUES ('1', 'mm-dd-yy', 'm-d-Y', '%m-%d-%Y');
INSERT INTO `date_format` VALUES ('2', 'mm/dd/yy', 'm/d/Y', '%m/%d/%Y');
INSERT INTO `date_format` VALUES ('3', 'mm.dd.yy', 'm.d.Y', '%m.%d.%Y');
INSERT INTO `date_format` VALUES ('4', 'dd-mm-yy', 'd-m-Y', '%d-%m-%Y');
INSERT INTO `date_format` VALUES ('5', 'dd/mm/yy', 'd/m/Y', '%d/%m/%Y');
INSERT INTO `date_format` VALUES ('6', 'dd.mm.yy', 'd.m.Y', '%d.%m.%Y');

-- ----------------------------
-- Table structure for `enum`
-- ----------------------------
DROP TABLE IF EXISTS `enum`;
CREATE TABLE `enum` (
  `enum_id` int(11) NOT NULL,
  `enum_short_description` text,
  `enum_long_description` text,
  PRIMARY KEY (`enum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of enum
-- ----------------------------
INSERT INTO `enum` VALUES ('100', 'Open', 'Ticket is in open status.');
INSERT INTO `enum` VALUES ('101', 'Development', 'Ticket is in development status.');
INSERT INTO `enum` VALUES ('102', 'Testing', 'Ticket is in testing status.');
INSERT INTO `enum` VALUES ('103', 'Closed', 'Ticket is in closed status.');
INSERT INTO `enum` VALUES ('104', 'Critical', 'Ticket is in severity critical.');
INSERT INTO `enum` VALUES ('105', 'High', 'Ticket is in severity high.');
INSERT INTO `enum` VALUES ('106', 'Medium', 'Ticket is in severity medium.');
INSERT INTO `enum` VALUES ('107', 'Low', 'Ticket is in severity low.');
INSERT INTO `enum` VALUES ('108', 'On Hold', 'Ticket is in on hold status.');
INSERT INTO `enum` VALUES ('200', 'Ticket Created', 'A user has created a new ticket.');
INSERT INTO `enum` VALUES ('201', 'Ticket Note Added', 'A user has added a note to a ticket.');
INSERT INTO `enum` VALUES ('202', 'Ticket File Added', 'A user has added a file to a ticket.');
INSERT INTO `enum` VALUES ('203', 'Ticket Association', 'A user has associate a ticket to another.');
INSERT INTO `enum` VALUES ('204', 'Ticket Updated', 'A user has updated a ticket.');

-- ----------------------------
-- Table structure for `estate`
-- ----------------------------
DROP TABLE IF EXISTS `estate`;
CREATE TABLE `estate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `content` longtext,
  `country` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) NOT NULL,
  `address` varchar(150) NOT NULL,
  `gps` varchar(100) NOT NULL,
  `photo` text,
  `photoGallery` text,
  `catID` int(10) DEFAULT NULL,
  `estateTypeID` int(10) DEFAULT NULL,
  `addedDate` datetime DEFAULT NULL,
  `addedUserID` varchar(100) DEFAULT NULL,
  `views` bigint(20) DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `room` varchar(100) DEFAULT '0',
  `sleep` tinyint(3) DEFAULT NULL,
  `guest_count` tinyint(3) DEFAULT NULL,
  `bathroom` varchar(100) DEFAULT '0',
  `heating` varchar(100) DEFAULT NULL,
  `squaremeter` varchar(100) DEFAULT '0',
  `squarefoot` varchar(100) NOT NULL,
  `buildstatus` varchar(100) DEFAULT NULL,
  `floor` varchar(100) DEFAULT '0',
  `telephone` varchar(100) DEFAULT NULL,
  `gsm` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `showcase` tinyint(1) DEFAULT '0',
  `slayt` tinyint(1) DEFAULT '0',
  `default_min_los` int(10) DEFAULT NULL,
  `default_nightly` decimal(50,0) DEFAULT NULL,
  `default_weekly` decimal(50,0) DEFAULT NULL,
  `fk` tinyint(2) DEFAULT '1',
  `vrbo` tinyint(2) DEFAULT '0',
  `hw` tinyint(2) DEFAULT '1',
  `bk` tinyint(2) DEFAULT '1',
  `ht` tinyint(2) DEFAULT '1',
  `rm` tinyint(2) DEFAULT '1',
  `vast` tinyint(2) DEFAULT '1',
  `airbnb` tinyint(2) DEFAULT '1',
  `otalo` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of estate
-- ----------------------------

-- ----------------------------
-- Table structure for `estateattrib`
-- ----------------------------
DROP TABLE IF EXISTS `estateattrib`;
CREATE TABLE `estateattrib` (
  `eaid` int(10) NOT NULL AUTO_INCREMENT,
  `attribName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`eaid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of estateattrib
-- ----------------------------
INSERT INTO `estateattrib` VALUES ('1', 'Amenities');
INSERT INTO `estateattrib` VALUES ('2', 'External Hardware');
INSERT INTO `estateattrib` VALUES ('3', 'Environmental Properties');

-- ----------------------------
-- Table structure for `estatetype`
-- ----------------------------
DROP TABLE IF EXISTS `estatetype`;
CREATE TABLE `estatetype` (
  `eid` int(10) NOT NULL AUTO_INCREMENT,
  `estateName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of estatetype
-- ----------------------------
INSERT INTO `estatetype` VALUES ('1', 'Land');
INSERT INTO `estatetype` VALUES ('2', 'Apartment');
INSERT INTO `estatetype` VALUES ('3', 'Family House');
INSERT INTO `estatetype` VALUES ('4', 'Building');
INSERT INTO `estatetype` VALUES ('5', 'Workplace');
INSERT INTO `estatetype` VALUES ('6', 'Shop');
INSERT INTO `estatetype` VALUES ('7', 'Villa');
INSERT INTO `estatetype` VALUES ('8', 'Garden');
INSERT INTO `estatetype` VALUES ('9', 'Terrain');

-- ----------------------------
-- Table structure for `groups`
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `level` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', 'members', 'General Users', '1');
INSERT INTO `groups` VALUES ('2', 'blogger', 'Blogger Access', '2');
INSERT INTO `groups` VALUES ('3', 'editor', 'Editor Access', '3');
INSERT INTO `groups` VALUES ('4', 'administrator', 'Administrator Access', '4');
INSERT INTO `groups` VALUES ('5', 'developer', 'Developer Access', '5');

-- ----------------------------
-- Table structure for `login_attempts`
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for `memberships`
-- ----------------------------
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `membership` varchar(64) NOT NULL,
  `amount` int(11) NOT NULL,
  `valid_days` int(11) NOT NULL DEFAULT '30',
  `description` text NOT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of memberships
-- ----------------------------
INSERT INTO `memberships` VALUES ('1', 'Free Package', '0', '15', '<li>No</li><li>15</li><li>100MB</li><li>No</li><li><i class=\"icon-remove red\"></i></li><li><i class=\"icon-remove red\"></i></li>');
INSERT INTO `memberships` VALUES ('2', 'Basic Package', '10', '90', '<li>Yes</li>\r\n<li>90</li>\r\n<li>1000MB</li>\r\n<li>Yes</li>\r\n<li>$15</li>\r\n<li><i class=\"icon-ok green\"></i> 1</li>');
INSERT INTO `memberships` VALUES ('3', 'Starter Package', '20', '150', '<li>Yes</li>\r\n<li>150</li>\r\n<li>2000MB</li>\r\n<li>Yes</li>\r\n<li>$25</li>\r\n<li><i class=\"icon-ok green\"></i> 1</li>');
INSERT INTO `memberships` VALUES ('4', 'Business Package', '30', '240', '<li>Yes</li>\r\n<li>240</li>\r\n<li>Unlimited</li>\r\n<li>Yes</li>\r\n<li>$30</li>\r\n<li><i class=\"icon-ok green\"></i> 1</li>');
INSERT INTO `memberships` VALUES ('6', 'Ultimate Package', '40', '365', '<li>Yes</li>\r\n<li>365</li>\r\n<li>Unlimited</li>\r\n<li>Yes</li>\r\n<li>$100</li>\r\n<li><i class=\"icon-ok green\"></i> 1</li>');

-- ----------------------------
-- Table structure for `options`
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES ('1', 'site_title', 'Vacation Rental for Owners');
INSERT INTO `options` VALUES ('2', 'slogan', 'Owners Vacation Rentals');
INSERT INTO `options` VALUES ('3', 'estate_count', '10');
INSERT INTO `options` VALUES ('4', 'blog_count', '10');
INSERT INTO `options` VALUES ('5', 'site_eposta', 'example@example.com');
INSERT INTO `options` VALUES ('6', 'phone', '281-947-2080 ');
INSERT INTO `options` VALUES ('7', 'mobile_phone', '281-947-2080 ');
INSERT INTO `options` VALUES ('8', 'facebook', 'https://www.facebook.com/facebook');
INSERT INTO `options` VALUES ('9', 'twitter', '');
INSERT INTO `options` VALUES ('10', 'google', '');
INSERT INTO `options` VALUES ('11', 'from_email', 'example@example.com');
INSERT INTO `options` VALUES ('12', 'from_name', 'Real Estate CMS Pro');
INSERT INTO `options` VALUES ('13', 'mail_type', 'smtp');
INSERT INTO `options` VALUES ('14', 'smtp_username', 'mail@example.com');
INSERT INTO `options` VALUES ('15', 'smtp_password', 'alamin');
INSERT INTO `options` VALUES ('16', 'smtp_port', '465');
INSERT INTO `options` VALUES ('17', 'smtp_ssl', 'ssl');
INSERT INTO `options` VALUES ('18', 'smtp_auth', '1');
INSERT INTO `options` VALUES ('19', 'smtp_host', 'mail.example.com');
INSERT INTO `options` VALUES ('20', 'mail_charset', 'utf-8');
INSERT INTO `options` VALUES ('21', 'mail_encoding', 'html');
INSERT INTO `options` VALUES ('22', 'email_activation', '0');
INSERT INTO `options` VALUES ('23', 'track_login_attempts', '1');
INSERT INTO `options` VALUES ('24', 'maximum_login_attempts', '7');
INSERT INTO `options` VALUES ('25', 'default_group', 'members');
INSERT INTO `options` VALUES ('26', 'min_password_length', '6');
INSERT INTO `options` VALUES ('27', 'max_password_length', '20');
INSERT INTO `options` VALUES ('28', 'site_keyword', 'Vacation Rentals,Vacation Rental CMS for managers');
INSERT INTO `options` VALUES ('29', 'site_author', 'Alamin@vacation_management.com');
INSERT INTO `options` VALUES ('30', 'slider', 'null');
INSERT INTO `options` VALUES ('31', 'site_analytics', '');
INSERT INTO `options` VALUES ('32', 'image_settings', '{\"image_height\":\"768\",\"image_width\":\"1024\",\"image_quality\":\"100\",\"image_maintain_ratio\":\"1\",\"image_wm_font_path\":\"assets\\/admin\\/font\\/Ubuntu-Light.ttf\",\"image_wm_text\":\"Real Estate CMS Pro\",\"image_wm_font_size\":\"50\",\"image_wm_font_color\":\"FFFFFF\",\"image_wm_shadow_color\":\"000000\",\"image_wm_shadow_distance\":\"1\",\"image_wm_vrt_alignment\":\"middle\",\"image_wm_hor_alignment\":\"center\",\"image_watermarking\":\"1\",\"image_wm_hor_offset\":\"50\"}');
INSERT INTO `options` VALUES ('33', 'pinterest', '');
INSERT INTO `options` VALUES ('34', 'linkedin', '');
INSERT INTO `options` VALUES ('35', 'site_footer', 'Copyright 2013 - <a href=\"http://www.VacationRentalSwap.com\">Software Developer Pro</a>');
INSERT INTO `options` VALUES ('36', 'adress', 'Houston, Texas');
INSERT INTO `options` VALUES ('37', 'cache_timeout', '60');
INSERT INTO `options` VALUES ('38', 'set_export', 'xml,csv');
INSERT INTO `options` VALUES ('39', 'set_export_module', 'listings,users');

-- ----------------------------
-- Table structure for `price_rates`
-- ----------------------------
DROP TABLE IF EXISTS `price_rates`;
CREATE TABLE `price_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of price_rates
-- ----------------------------
INSERT INTO `price_rates` VALUES ('14', '23', 'higj', '2014-07-23', '2014-07-26', '8', '20', '30', '2014-07-05 11:31:09', '2014-07-05 11:31:09');
INSERT INTO `price_rates` VALUES ('15', '23', 'mid', '2014-08-04', '2014-08-12', '6', '52', '233', '2014-07-05 11:31:09', '2014-07-05 11:31:09');
INSERT INTO `price_rates` VALUES ('19', '22', 'high', '2014-06-25', '2014-06-26', '4', '40', '500', '2016-07-03 19:32:57', '2016-07-03 19:32:57');
INSERT INTO `price_rates` VALUES ('20', '22', 'low', '2014-07-15', '2014-07-17', '7', '62', '800', '2016-07-03 19:32:57', '2016-07-03 19:32:57');
INSERT INTO `price_rates` VALUES ('21', '22', 'set fixed', '2014-06-24', '2014-06-30', '10', '100', '900', '2016-07-03 19:32:57', '2016-07-03 19:32:57');
INSERT INTO `price_rates` VALUES ('26', '28', 'test', '2016-08-03', '2016-08-13', '3', '50', '350', '2016-07-15 20:33:08', '2016-07-15 20:33:08');
INSERT INTO `price_rates` VALUES ('28', '26', 'christmas time', '2016-07-23', '2016-08-04', '7', '50', '350', '2016-07-15 23:41:45', '2016-07-15 23:41:45');
INSERT INTO `price_rates` VALUES ('29', '29', 'christmas time', '2016-07-23', '2016-08-04', '3', '50', '350', '2016-07-16 00:15:25', '2016-07-16 00:15:25');
INSERT INTO `price_rates` VALUES ('31', '30', 'christmas time', '2016-07-23', '2016-08-04', '3', '50', '350', '2016-07-16 00:19:43', '2016-07-16 00:19:43');

-- ----------------------------
-- Table structure for `property`
-- ----------------------------
DROP TABLE IF EXISTS `property`;
CREATE TABLE `property` (
  `pid` bigint(20) NOT NULL AUTO_INCREMENT,
  `estateID` bigint(20) DEFAULT NULL,
  `value` tinyint(2) DEFAULT '0',
  `propID` int(5) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `propID` (`propID`,`estateID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=290 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of property
-- ----------------------------
INSERT INTO `property` VALUES ('1', '1', '1', '4');
INSERT INTO `property` VALUES ('2', '1', '1', '5');
INSERT INTO `property` VALUES ('3', '1', '1', '6');
INSERT INTO `property` VALUES ('4', '1', '1', '13');
INSERT INTO `property` VALUES ('5', '1', '1', '14');
INSERT INTO `property` VALUES ('6', '1', '1', '15');
INSERT INTO `property` VALUES ('7', '1', '1', '18');
INSERT INTO `property` VALUES ('8', '1', '1', '19');
INSERT INTO `property` VALUES ('9', '1', '1', '20');
INSERT INTO `property` VALUES ('10', '1', '1', '28');
INSERT INTO `property` VALUES ('11', '1', '1', '31');
INSERT INTO `property` VALUES ('12', '1', '1', '33');
INSERT INTO `property` VALUES ('13', '1', '1', '41');
INSERT INTO `property` VALUES ('14', '1', '1', '42');
INSERT INTO `property` VALUES ('15', '1', '1', '43');
INSERT INTO `property` VALUES ('16', '1', '1', '48');
INSERT INTO `property` VALUES ('17', '1', '1', '49');
INSERT INTO `property` VALUES ('18', '1', '1', '54');
INSERT INTO `property` VALUES ('19', '2', '1', '8');
INSERT INTO `property` VALUES ('20', '2', '1', '10');
INSERT INTO `property` VALUES ('21', '2', '1', '17');
INSERT INTO `property` VALUES ('22', '2', '1', '21');
INSERT INTO `property` VALUES ('23', '2', '1', '22');
INSERT INTO `property` VALUES ('24', '2', '1', '25');
INSERT INTO `property` VALUES ('25', '2', '1', '28');
INSERT INTO `property` VALUES ('26', '2', '1', '30');
INSERT INTO `property` VALUES ('27', '2', '1', '31');
INSERT INTO `property` VALUES ('28', '2', '1', '32');
INSERT INTO `property` VALUES ('29', '2', '1', '39');
INSERT INTO `property` VALUES ('30', '2', '1', '40');
INSERT INTO `property` VALUES ('31', '2', '1', '41');
INSERT INTO `property` VALUES ('32', '2', '1', '45');
INSERT INTO `property` VALUES ('33', '2', '1', '46');
INSERT INTO `property` VALUES ('34', '2', '1', '47');
INSERT INTO `property` VALUES ('35', '2', '1', '49');
INSERT INTO `property` VALUES ('36', '2', '1', '53');
INSERT INTO `property` VALUES ('37', '3', '1', '2');
INSERT INTO `property` VALUES ('38', '3', '1', '3');
INSERT INTO `property` VALUES ('39', '3', '1', '4');
INSERT INTO `property` VALUES ('40', '3', '1', '5');
INSERT INTO `property` VALUES ('41', '3', '1', '12');
INSERT INTO `property` VALUES ('42', '3', '1', '14');
INSERT INTO `property` VALUES ('43', '3', '1', '16');
INSERT INTO `property` VALUES ('44', '3', '1', '20');
INSERT INTO `property` VALUES ('45', '3', '1', '21');
INSERT INTO `property` VALUES ('46', '3', '1', '22');
INSERT INTO `property` VALUES ('47', '3', '1', '26');
INSERT INTO `property` VALUES ('48', '3', '1', '29');
INSERT INTO `property` VALUES ('49', '3', '1', '33');
INSERT INTO `property` VALUES ('50', '3', '1', '34');
INSERT INTO `property` VALUES ('51', '3', '1', '36');
INSERT INTO `property` VALUES ('52', '3', '1', '39');
INSERT INTO `property` VALUES ('53', '3', '1', '40');
INSERT INTO `property` VALUES ('54', '3', '1', '41');
INSERT INTO `property` VALUES ('55', '3', '1', '43');
INSERT INTO `property` VALUES ('56', '3', '1', '44');
INSERT INTO `property` VALUES ('57', '3', '1', '45');
INSERT INTO `property` VALUES ('58', '3', '1', '49');
INSERT INTO `property` VALUES ('59', '3', '1', '50');
INSERT INTO `property` VALUES ('60', '3', '1', '54');
INSERT INTO `property` VALUES ('61', '4', '1', '1');
INSERT INTO `property` VALUES ('62', '4', '1', '6');
INSERT INTO `property` VALUES ('63', '4', '1', '9');
INSERT INTO `property` VALUES ('64', '4', '1', '11');
INSERT INTO `property` VALUES ('65', '4', '1', '16');
INSERT INTO `property` VALUES ('66', '4', '1', '20');
INSERT INTO `property` VALUES ('67', '4', '1', '24');
INSERT INTO `property` VALUES ('68', '4', '1', '28');
INSERT INTO `property` VALUES ('69', '4', '1', '31');
INSERT INTO `property` VALUES ('70', '4', '1', '32');
INSERT INTO `property` VALUES ('71', '4', '1', '35');
INSERT INTO `property` VALUES ('72', '4', '1', '36');
INSERT INTO `property` VALUES ('73', '4', '1', '42');
INSERT INTO `property` VALUES ('74', '4', '1', '45');
INSERT INTO `property` VALUES ('75', '4', '1', '48');
INSERT INTO `property` VALUES ('76', '4', '1', '49');
INSERT INTO `property` VALUES ('77', '4', '1', '55');
INSERT INTO `property` VALUES ('78', '5', '1', '6');
INSERT INTO `property` VALUES ('79', '5', '1', '7');
INSERT INTO `property` VALUES ('80', '5', '1', '9');
INSERT INTO `property` VALUES ('81', '5', '1', '10');
INSERT INTO `property` VALUES ('82', '5', '1', '13');
INSERT INTO `property` VALUES ('83', '5', '1', '15');
INSERT INTO `property` VALUES ('84', '5', '1', '16');
INSERT INTO `property` VALUES ('85', '5', '1', '18');
INSERT INTO `property` VALUES ('86', '5', '1', '19');
INSERT INTO `property` VALUES ('87', '5', '1', '20');
INSERT INTO `property` VALUES ('88', '5', '1', '31');
INSERT INTO `property` VALUES ('89', '5', '1', '32');
INSERT INTO `property` VALUES ('90', '5', '1', '33');
INSERT INTO `property` VALUES ('91', '5', '1', '34');
INSERT INTO `property` VALUES ('92', '5', '1', '37');
INSERT INTO `property` VALUES ('93', '5', '1', '39');
INSERT INTO `property` VALUES ('94', '5', '1', '40');
INSERT INTO `property` VALUES ('95', '5', '1', '41');
INSERT INTO `property` VALUES ('96', '5', '1', '42');
INSERT INTO `property` VALUES ('97', '5', '1', '43');
INSERT INTO `property` VALUES ('98', '5', '1', '44');
INSERT INTO `property` VALUES ('99', '5', '1', '45');
INSERT INTO `property` VALUES ('100', '5', '1', '46');
INSERT INTO `property` VALUES ('101', '6', '1', '4');
INSERT INTO `property` VALUES ('102', '6', '1', '6');
INSERT INTO `property` VALUES ('103', '6', '1', '7');
INSERT INTO `property` VALUES ('104', '6', '1', '8');
INSERT INTO `property` VALUES ('105', '6', '1', '9');
INSERT INTO `property` VALUES ('106', '6', '1', '10');
INSERT INTO `property` VALUES ('107', '6', '1', '11');
INSERT INTO `property` VALUES ('108', '6', '1', '12');
INSERT INTO `property` VALUES ('109', '6', '1', '28');
INSERT INTO `property` VALUES ('110', '6', '1', '29');
INSERT INTO `property` VALUES ('111', '6', '1', '30');
INSERT INTO `property` VALUES ('112', '6', '1', '31');
INSERT INTO `property` VALUES ('113', '6', '1', '32');
INSERT INTO `property` VALUES ('114', '6', '1', '33');
INSERT INTO `property` VALUES ('115', '6', '1', '34');
INSERT INTO `property` VALUES ('116', '6', '1', '35');
INSERT INTO `property` VALUES ('117', '6', '1', '41');
INSERT INTO `property` VALUES ('118', '6', '1', '42');
INSERT INTO `property` VALUES ('119', '6', '1', '43');
INSERT INTO `property` VALUES ('120', '6', '1', '44');
INSERT INTO `property` VALUES ('121', '6', '1', '45');
INSERT INTO `property` VALUES ('122', '6', '1', '46');
INSERT INTO `property` VALUES ('123', '6', '1', '47');
INSERT INTO `property` VALUES ('124', '6', '1', '48');
INSERT INTO `property` VALUES ('125', '7', '1', '5');
INSERT INTO `property` VALUES ('126', '7', '1', '6');
INSERT INTO `property` VALUES ('127', '7', '1', '7');
INSERT INTO `property` VALUES ('128', '7', '1', '8');
INSERT INTO `property` VALUES ('129', '7', '1', '9');
INSERT INTO `property` VALUES ('130', '7', '1', '10');
INSERT INTO `property` VALUES ('131', '7', '1', '11');
INSERT INTO `property` VALUES ('132', '7', '1', '12');
INSERT INTO `property` VALUES ('133', '7', '1', '13');
INSERT INTO `property` VALUES ('134', '7', '1', '28');
INSERT INTO `property` VALUES ('135', '7', '1', '29');
INSERT INTO `property` VALUES ('136', '7', '1', '30');
INSERT INTO `property` VALUES ('137', '7', '1', '31');
INSERT INTO `property` VALUES ('138', '7', '1', '32');
INSERT INTO `property` VALUES ('139', '7', '1', '33');
INSERT INTO `property` VALUES ('140', '7', '1', '34');
INSERT INTO `property` VALUES ('141', '7', '1', '44');
INSERT INTO `property` VALUES ('142', '7', '1', '47');
INSERT INTO `property` VALUES ('143', '7', '1', '48');
INSERT INTO `property` VALUES ('144', '7', '1', '49');
INSERT INTO `property` VALUES ('145', '7', '1', '50');
INSERT INTO `property` VALUES ('146', '7', '1', '51');
INSERT INTO `property` VALUES ('147', '7', '1', '52');
INSERT INTO `property` VALUES ('148', '8', '1', '11');
INSERT INTO `property` VALUES ('149', '8', '1', '13');
INSERT INTO `property` VALUES ('150', '8', '1', '14');
INSERT INTO `property` VALUES ('151', '8', '1', '15');
INSERT INTO `property` VALUES ('152', '8', '1', '16');
INSERT INTO `property` VALUES ('153', '8', '1', '17');
INSERT INTO `property` VALUES ('154', '8', '1', '18');
INSERT INTO `property` VALUES ('155', '8', '1', '32');
INSERT INTO `property` VALUES ('156', '8', '1', '34');
INSERT INTO `property` VALUES ('157', '8', '1', '35');
INSERT INTO `property` VALUES ('158', '8', '1', '36');
INSERT INTO `property` VALUES ('159', '8', '1', '37');
INSERT INTO `property` VALUES ('160', '8', '1', '38');
INSERT INTO `property` VALUES ('161', '8', '1', '41');
INSERT INTO `property` VALUES ('162', '8', '1', '42');
INSERT INTO `property` VALUES ('163', '8', '1', '43');
INSERT INTO `property` VALUES ('164', '8', '1', '44');
INSERT INTO `property` VALUES ('165', '8', '1', '45');
INSERT INTO `property` VALUES ('166', '9', '1', '7');
INSERT INTO `property` VALUES ('167', '9', '1', '8');
INSERT INTO `property` VALUES ('168', '9', '1', '9');
INSERT INTO `property` VALUES ('169', '9', '1', '10');
INSERT INTO `property` VALUES ('170', '9', '1', '11');
INSERT INTO `property` VALUES ('171', '9', '1', '12');
INSERT INTO `property` VALUES ('172', '9', '1', '13');
INSERT INTO `property` VALUES ('173', '9', '1', '14');
INSERT INTO `property` VALUES ('174', '9', '1', '15');
INSERT INTO `property` VALUES ('175', '9', '1', '16');
INSERT INTO `property` VALUES ('176', '9', '1', '17');
INSERT INTO `property` VALUES ('177', '9', '1', '18');
INSERT INTO `property` VALUES ('178', '9', '1', '19');
INSERT INTO `property` VALUES ('179', '9', '1', '20');
INSERT INTO `property` VALUES ('180', '9', '1', '21');
INSERT INTO `property` VALUES ('181', '9', '1', '22');
INSERT INTO `property` VALUES ('182', '9', '1', '23');
INSERT INTO `property` VALUES ('183', '9', '1', '24');
INSERT INTO `property` VALUES ('184', '9', '1', '25');
INSERT INTO `property` VALUES ('185', '9', '1', '26');
INSERT INTO `property` VALUES ('186', '9', '1', '27');
INSERT INTO `property` VALUES ('187', '9', '1', '28');
INSERT INTO `property` VALUES ('188', '9', '1', '29');
INSERT INTO `property` VALUES ('189', '9', '1', '30');
INSERT INTO `property` VALUES ('190', '9', '1', '31');
INSERT INTO `property` VALUES ('191', '9', '1', '32');
INSERT INTO `property` VALUES ('192', '9', '1', '33');
INSERT INTO `property` VALUES ('193', '9', '1', '34');
INSERT INTO `property` VALUES ('194', '9', '1', '35');
INSERT INTO `property` VALUES ('195', '9', '1', '36');
INSERT INTO `property` VALUES ('196', '9', '1', '37');
INSERT INTO `property` VALUES ('197', '9', '1', '38');
INSERT INTO `property` VALUES ('198', '9', '1', '39');
INSERT INTO `property` VALUES ('199', '9', '1', '40');
INSERT INTO `property` VALUES ('200', '9', '1', '41');
INSERT INTO `property` VALUES ('201', '9', '1', '42');
INSERT INTO `property` VALUES ('202', '9', '1', '43');
INSERT INTO `property` VALUES ('203', '9', '1', '44');
INSERT INTO `property` VALUES ('204', '9', '1', '45');
INSERT INTO `property` VALUES ('205', '9', '1', '46');
INSERT INTO `property` VALUES ('206', '9', '1', '47');
INSERT INTO `property` VALUES ('207', '10', '1', '1');
INSERT INTO `property` VALUES ('208', '10', '1', '2');
INSERT INTO `property` VALUES ('209', '10', '1', '3');
INSERT INTO `property` VALUES ('210', '10', '1', '4');
INSERT INTO `property` VALUES ('211', '10', '1', '5');
INSERT INTO `property` VALUES ('212', '10', '1', '6');
INSERT INTO `property` VALUES ('213', '10', '1', '7');
INSERT INTO `property` VALUES ('214', '10', '1', '8');
INSERT INTO `property` VALUES ('215', '10', '1', '9');
INSERT INTO `property` VALUES ('216', '10', '1', '10');
INSERT INTO `property` VALUES ('217', '10', '1', '11');
INSERT INTO `property` VALUES ('218', '10', '1', '12');
INSERT INTO `property` VALUES ('219', '10', '1', '38');
INSERT INTO `property` VALUES ('220', '10', '1', '29');
INSERT INTO `property` VALUES ('221', '10', '1', '30');
INSERT INTO `property` VALUES ('222', '10', '1', '32');
INSERT INTO `property` VALUES ('223', '10', '1', '33');
INSERT INTO `property` VALUES ('224', '10', '1', '34');
INSERT INTO `property` VALUES ('225', '10', '1', '41');
INSERT INTO `property` VALUES ('226', '10', '1', '43');
INSERT INTO `property` VALUES ('227', '10', '1', '46');
INSERT INTO `property` VALUES ('228', '10', '1', '47');
INSERT INTO `property` VALUES ('229', '10', '1', '51');
INSERT INTO `property` VALUES ('230', '19', '0', '1');
INSERT INTO `property` VALUES ('231', '20', '1', '8');
INSERT INTO `property` VALUES ('232', '20', '1', '11');
INSERT INTO `property` VALUES ('233', '20', '1', '14');
INSERT INTO `property` VALUES ('234', '20', '1', '17');
INSERT INTO `property` VALUES ('235', '20', '1', '32');
INSERT INTO `property` VALUES ('236', '20', '1', '35');
INSERT INTO `property` VALUES ('237', '20', '1', '38');
INSERT INTO `property` VALUES ('238', '20', '1', '41');
INSERT INTO `property` VALUES ('239', '20', '1', '44');
INSERT INTO `property` VALUES ('240', '21', '1', '11');
INSERT INTO `property` VALUES ('241', '21', '1', '14');
INSERT INTO `property` VALUES ('242', '21', '1', '17');
INSERT INTO `property` VALUES ('243', '21', '1', '34');
INSERT INTO `property` VALUES ('244', '21', '1', '35');
INSERT INTO `property` VALUES ('245', '21', '1', '36');
INSERT INTO `property` VALUES ('246', '21', '1', '43');
INSERT INTO `property` VALUES ('247', '21', '1', '47');
INSERT INTO `property` VALUES ('248', '21', '1', '48');
INSERT INTO `property` VALUES ('249', '22', '1', '2');
INSERT INTO `property` VALUES ('250', '22', '1', '5');
INSERT INTO `property` VALUES ('251', '22', '1', '8');
INSERT INTO `property` VALUES ('252', '22', '1', '29');
INSERT INTO `property` VALUES ('253', '22', '1', '32');
INSERT INTO `property` VALUES ('254', '22', '1', '35');
INSERT INTO `property` VALUES ('255', '22', '1', '38');
INSERT INTO `property` VALUES ('256', '22', '1', '41');
INSERT INTO `property` VALUES ('257', '22', '1', '44');
INSERT INTO `property` VALUES ('258', '23', '1', '2');
INSERT INTO `property` VALUES ('259', '23', '1', '5');
INSERT INTO `property` VALUES ('260', '23', '1', '8');
INSERT INTO `property` VALUES ('261', '23', '1', '11');
INSERT INTO `property` VALUES ('262', '23', '1', '46');
INSERT INTO `property` VALUES ('263', '23', '1', '49');
INSERT INTO `property` VALUES ('264', '23', '1', '52');
INSERT INTO `property` VALUES ('265', '23', '1', '55');
INSERT INTO `property` VALUES ('266', '24', '1', '1');
INSERT INTO `property` VALUES ('267', '24', '1', '5');
INSERT INTO `property` VALUES ('268', '24', '1', '9');
INSERT INTO `property` VALUES ('269', '24', '1', '15');
INSERT INTO `property` VALUES ('270', '24', '1', '29');
INSERT INTO `property` VALUES ('271', '24', '1', '38');
INSERT INTO `property` VALUES ('272', '25', '0', '1');
INSERT INTO `property` VALUES ('273', '26', '1', '38');
INSERT INTO `property` VALUES ('274', '26', '1', '41');
INSERT INTO `property` VALUES ('275', '27', '1', '29');
INSERT INTO `property` VALUES ('276', '27', '1', '32');
INSERT INTO `property` VALUES ('277', '27', '1', '35');
INSERT INTO `property` VALUES ('278', '28', '1', '12');
INSERT INTO `property` VALUES ('279', '28', '1', '18');
INSERT INTO `property` VALUES ('280', '26', '1', '40');
INSERT INTO `property` VALUES ('281', '26', '1', '52');
INSERT INTO `property` VALUES ('282', '29', '1', '5');
INSERT INTO `property` VALUES ('283', '29', '1', '6');
INSERT INTO `property` VALUES ('284', '29', '1', '8');
INSERT INTO `property` VALUES ('285', '29', '1', '9');
INSERT INTO `property` VALUES ('286', '30', '1', '5');
INSERT INTO `property` VALUES ('287', '30', '1', '6');
INSERT INTO `property` VALUES ('288', '30', '1', '8');
INSERT INTO `property` VALUES ('289', '30', '1', '9');

-- ----------------------------
-- Table structure for `propertyfield`
-- ----------------------------
DROP TABLE IF EXISTS `propertyfield`;
CREATE TABLE `propertyfield` (
  `pfid` int(10) NOT NULL AUTO_INCREMENT,
  `typeID` int(5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pfid`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of propertyfield
-- ----------------------------
INSERT INTO `propertyfield` VALUES ('1', '1', 'ADSL');
INSERT INTO `propertyfield` VALUES ('2', '1', 'Wood Window');
INSERT INTO `propertyfield` VALUES ('3', '1', 'Alarm');
INSERT INTO `propertyfield` VALUES ('4', '1', 'Kitchen');
INSERT INTO `propertyfield` VALUES ('5', '1', 'Lift');
INSERT INTO `propertyfield` VALUES ('6', '1', 'Furniture');
INSERT INTO `propertyfield` VALUES ('7', '1', 'Laundry Room');
INSERT INTO `propertyfield` VALUES ('8', '1', 'Steel Door');
INSERT INTO `propertyfield` VALUES ('9', '1', 'Shower');
INSERT INTO `propertyfield` VALUES ('10', '1', 'Bath');
INSERT INTO `propertyfield` VALUES ('11', '1', 'Master Bathroom');
INSERT INTO `propertyfield` VALUES ('12', '1', 'Closet');
INSERT INTO `propertyfield` VALUES ('13', '1', 'Entry');
INSERT INTO `propertyfield` VALUES ('14', '1', 'Booster');
INSERT INTO `propertyfield` VALUES ('15', '1', 'Isımcam');
INSERT INTO `propertyfield` VALUES ('16', '1', 'Cable TV-Satellite');
INSERT INTO `propertyfield` VALUES ('17', '1', 'Papier');
INSERT INTO `propertyfield` VALUES ('18', '1', 'Liminant');
INSERT INTO `propertyfield` VALUES ('19', '1', 'Furnished');
INSERT INTO `propertyfield` VALUES ('20', '1', 'Kitchen Natural Gas');
INSERT INTO `propertyfield` VALUES ('21', '1', 'Flooring');
INSERT INTO `propertyfield` VALUES ('22', '1', 'PVC Window');
INSERT INTO `propertyfield` VALUES ('23', '1', 'Fireplace');
INSERT INTO `propertyfield` VALUES ('24', '1', 'Painted');
INSERT INTO `propertyfield` VALUES ('25', '1', 'Fiber Optic Internet');
INSERT INTO `propertyfield` VALUES ('26', '1', 'Air Conditioning');
INSERT INTO `propertyfield` VALUES ('27', '1', 'Geyser');
INSERT INTO `propertyfield` VALUES ('28', '2', 'Site Within');
INSERT INTO `propertyfield` VALUES ('29', '2', 'On - Parking');
INSERT INTO `propertyfield` VALUES ('30', '2', 'Off - Parking');
INSERT INTO `propertyfield` VALUES ('31', '2', 'Theme Parks');
INSERT INTO `propertyfield` VALUES ('32', '2', 'Swimming Pool');
INSERT INTO `propertyfield` VALUES ('33', '2', 'Shopping');
INSERT INTO `propertyfield` VALUES ('34', '2', 'Sport');
INSERT INTO `propertyfield` VALUES ('35', '2', 'Water Tank');
INSERT INTO `propertyfield` VALUES ('36', '2', 'Fire Escape');
INSERT INTO `propertyfield` VALUES ('37', '3', 'Strait Landscape');
INSERT INTO `propertyfield` VALUES ('38', '3', 'Sea View');
INSERT INTO `propertyfield` VALUES ('39', '3', 'Kake view');
INSERT INTO `propertyfield` VALUES ('40', '3', 'Near the sea');
INSERT INTO `propertyfield` VALUES ('41', '3', 'Street No');
INSERT INTO `propertyfield` VALUES ('42', '3', 'Near the Airport');
INSERT INTO `propertyfield` VALUES ('43', '3', 'Public Transportation');
INSERT INTO `propertyfield` VALUES ('44', '3', 'Close to Highway');
INSERT INTO `propertyfield` VALUES ('45', '3', 'Metro');
INSERT INTO `propertyfield` VALUES ('46', '3', 'Primary School');
INSERT INTO `propertyfield` VALUES ('47', '3', 'High');
INSERT INTO `propertyfield` VALUES ('48', '3', 'The University');
INSERT INTO `propertyfield` VALUES ('49', '3', 'Pharmacy');
INSERT INTO `propertyfield` VALUES ('50', '3', 'Hospital');
INSERT INTO `propertyfield` VALUES ('51', '3', 'Market');
INSERT INTO `propertyfield` VALUES ('52', '3', 'Park');
INSERT INTO `propertyfield` VALUES ('53', '3', 'Neighborhood Market');
INSERT INTO `propertyfield` VALUES ('54', '3', 'Police Station');
INSERT INTO `propertyfield` VALUES ('55', '3', 'Shopping Mall');

-- ----------------------------
-- Table structure for `roles`
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `default` tinyint(2) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'admin', '0');
INSERT INTO `roles` VALUES ('2', 'user', '1');

-- ----------------------------
-- Table structure for `selectbox`
-- ----------------------------
DROP TABLE IF EXISTS `selectbox`;
CREATE TABLE `selectbox` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of selectbox
-- ----------------------------
INSERT INTO `selectbox` VALUES ('1', 'room', '0');
INSERT INTO `selectbox` VALUES ('3', 'room', '1');
INSERT INTO `selectbox` VALUES ('5', 'room', '2');
INSERT INTO `selectbox` VALUES ('8', 'room', '3');
INSERT INTO `selectbox` VALUES ('11', 'room', '4');
INSERT INTO `selectbox` VALUES ('16', 'room', '5');
INSERT INTO `selectbox` VALUES ('21', 'room', '6');
INSERT INTO `selectbox` VALUES ('26', 'room', '7');
INSERT INTO `selectbox` VALUES ('31', 'room', '8');
INSERT INTO `selectbox` VALUES ('36', 'room', '9');
INSERT INTO `selectbox` VALUES ('41', 'room', '10');
INSERT INTO `selectbox` VALUES ('42', 'bathroom', '0');
INSERT INTO `selectbox` VALUES ('43', 'bathroom', '0');
INSERT INTO `selectbox` VALUES ('44', 'bathroom', '1');
INSERT INTO `selectbox` VALUES ('45', 'bathroom', '2');
INSERT INTO `selectbox` VALUES ('46', 'bathroom', '3');
INSERT INTO `selectbox` VALUES ('47', 'bathroom', '4');
INSERT INTO `selectbox` VALUES ('48', 'bathroom', '5');
INSERT INTO `selectbox` VALUES ('49', 'bathroom', '6');
INSERT INTO `selectbox` VALUES ('50', 'bathroom', '7');
INSERT INTO `selectbox` VALUES ('51', 'bathroom', '8');
INSERT INTO `selectbox` VALUES ('52', 'heating', '0');
INSERT INTO `selectbox` VALUES ('53', 'heating', 'Floor Heating');
INSERT INTO `selectbox` VALUES ('54', 'heating', 'Natural Gas');
INSERT INTO `selectbox` VALUES ('55', 'heating', 'Central Heating');
INSERT INTO `selectbox` VALUES ('56', 'heating', 'Stove');
INSERT INTO `selectbox` VALUES ('58', 'heating', 'Air Conditioning');
INSERT INTO `selectbox` VALUES ('59', 'heating', 'Solar Energy');
INSERT INTO `selectbox` VALUES ('61', 'squaremeter', '0');
INSERT INTO `selectbox` VALUES ('62', 'squaremeter', '60');
INSERT INTO `selectbox` VALUES ('63', 'squaremeter', '70');
INSERT INTO `selectbox` VALUES ('64', 'squaremeter', '80');
INSERT INTO `selectbox` VALUES ('65', 'squaremeter', '90');
INSERT INTO `selectbox` VALUES ('66', 'squaremeter', '100');
INSERT INTO `selectbox` VALUES ('67', 'squaremeter', '110');
INSERT INTO `selectbox` VALUES ('68', 'squaremeter', '120');
INSERT INTO `selectbox` VALUES ('69', 'squaremeter', '130');
INSERT INTO `selectbox` VALUES ('70', 'squaremeter', '140');
INSERT INTO `selectbox` VALUES ('71', 'squaremeter', '150');
INSERT INTO `selectbox` VALUES ('72', 'squaremeter', '160');
INSERT INTO `selectbox` VALUES ('73', 'squaremeter', '170');
INSERT INTO `selectbox` VALUES ('74', 'squaremeter', '180');
INSERT INTO `selectbox` VALUES ('75', 'squaremeter', '190');
INSERT INTO `selectbox` VALUES ('76', 'squaremeter', '200');
INSERT INTO `selectbox` VALUES ('77', 'squaremeter', '210');
INSERT INTO `selectbox` VALUES ('78', 'squaremeter', '220');
INSERT INTO `selectbox` VALUES ('79', 'squaremeter', '230');
INSERT INTO `selectbox` VALUES ('80', 'squaremeter', '240');
INSERT INTO `selectbox` VALUES ('81', 'squaremeter', '250');
INSERT INTO `selectbox` VALUES ('82', 'squaremeter', '260');
INSERT INTO `selectbox` VALUES ('83', 'squaremeter', '270');
INSERT INTO `selectbox` VALUES ('84', 'squaremeter', '280');
INSERT INTO `selectbox` VALUES ('85', 'squaremeter', '290');
INSERT INTO `selectbox` VALUES ('86', 'squaremeter', '300');
INSERT INTO `selectbox` VALUES ('93', 'floor', '0');
INSERT INTO `selectbox` VALUES ('94', 'floor', '1');
INSERT INTO `selectbox` VALUES ('95', 'floor', '2');
INSERT INTO `selectbox` VALUES ('96', 'floor', '3');
INSERT INTO `selectbox` VALUES ('97', 'floor', '4');
INSERT INTO `selectbox` VALUES ('98', 'floor', '5');
INSERT INTO `selectbox` VALUES ('99', 'floor', '6');
INSERT INTO `selectbox` VALUES ('100', 'floor', '7');
INSERT INTO `selectbox` VALUES ('101', 'floor', '8');
INSERT INTO `selectbox` VALUES ('102', 'floor', '9');
INSERT INTO `selectbox` VALUES ('103', 'floor', '10');
INSERT INTO `selectbox` VALUES ('104', 'floor', '11');
INSERT INTO `selectbox` VALUES ('105', 'floor', '12');
INSERT INTO `selectbox` VALUES ('106', 'floor', '13');
INSERT INTO `selectbox` VALUES ('107', 'floor', '14');
INSERT INTO `selectbox` VALUES ('108', 'floor', '15');
INSERT INTO `selectbox` VALUES ('109', 'floor', '16');
INSERT INTO `selectbox` VALUES ('110', 'floor', '17');
INSERT INTO `selectbox` VALUES ('111', 'floor', '18');
INSERT INTO `selectbox` VALUES ('112', 'floor', '19');
INSERT INTO `selectbox` VALUES ('113', 'floor', '20');
INSERT INTO `selectbox` VALUES ('114', 'floor', '21');
INSERT INTO `selectbox` VALUES ('115', 'floor', '22');
INSERT INTO `selectbox` VALUES ('116', 'floor', '23');
INSERT INTO `selectbox` VALUES ('117', 'floor', '24');
INSERT INTO `selectbox` VALUES ('118', 'floor', '25');
INSERT INTO `selectbox` VALUES ('119', 'floor', '26');
INSERT INTO `selectbox` VALUES ('120', 'floor', '27');
INSERT INTO `selectbox` VALUES ('121', 'floor', '28');
INSERT INTO `selectbox` VALUES ('122', 'floor', '29');
INSERT INTO `selectbox` VALUES ('123', 'floor', '30');
INSERT INTO `selectbox` VALUES ('124', 'floor', '31');
INSERT INTO `selectbox` VALUES ('125', 'floor', '32');
INSERT INTO `selectbox` VALUES ('126', 'floor', '33');
INSERT INTO `selectbox` VALUES ('127', 'floor', '34');
INSERT INTO `selectbox` VALUES ('128', 'floor', '35');
INSERT INTO `selectbox` VALUES ('129', 'floor', '36');
INSERT INTO `selectbox` VALUES ('130', 'floor', '37');
INSERT INTO `selectbox` VALUES ('131', 'floor', '38');
INSERT INTO `selectbox` VALUES ('132', 'floor', '39');
INSERT INTO `selectbox` VALUES ('133', 'floor', '40');
INSERT INTO `selectbox` VALUES ('134', 'floor', '41');
INSERT INTO `selectbox` VALUES ('135', 'floor', '42');
INSERT INTO `selectbox` VALUES ('136', 'floor', '43');
INSERT INTO `selectbox` VALUES ('137', 'floor', '44');
INSERT INTO `selectbox` VALUES ('138', 'floor', '45');
INSERT INTO `selectbox` VALUES ('139', 'floor', '46');
INSERT INTO `selectbox` VALUES ('140', 'floor', '47');
INSERT INTO `selectbox` VALUES ('141', 'floor', '48');
INSERT INTO `selectbox` VALUES ('142', 'floor', '49');
INSERT INTO `selectbox` VALUES ('143', 'floor', '50');

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_id` int(1) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `invoice_logo` varchar(255) NOT NULL,
  `site_name` varchar(55) NOT NULL,
  `language` varchar(20) NOT NULL,
  `currency_prefix` varchar(3) NOT NULL,
  `default_tax_rate` int(2) NOT NULL,
  `rows_per_page` int(2) NOT NULL,
  `no_of_rows` int(2) NOT NULL,
  `total_rows` int(2) NOT NULL,
  `dateformat` tinyint(4) NOT NULL,
  `print_payment` tinyint(4) NOT NULL,
  `calendar` tinyint(4) NOT NULL,
  `restrict_sales` tinyint(4) NOT NULL,
  `major` varchar(25) DEFAULT NULL,
  `minor` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('1', 'logo.png', 'logo2.png', 'Invoice Manager', 'english', '$', '1', '10', '9', '49', '6', '1', '0', '1', '$', '$');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of unavailability
-- ----------------------------
INSERT INTO `unavailability` VALUES ('1', '19', '[\"2014-06-22\",\"2014-06-23\"]', '2014-06-21 16:21:23', '2014-06-21 16:21:23');
INSERT INTO `unavailability` VALUES ('2', '20', '[\"2014-06-22\",\"2014-06-23\",\"2014-07-23\",\"2014-07-24\",\"2014-07-25\",\"2014-07-26\",\"2014-07-27\",\"2014-07-28\",\"2014-07-29\",\"2014-07-30\",\"2014-07-31\"]', '2014-06-21 16:31:01', '2014-06-21 16:31:01');
INSERT INTO `unavailability` VALUES ('8', '23', '[\"\"]', '2014-07-05 11:31:09', '2014-07-05 11:31:09');
INSERT INTO `unavailability` VALUES ('10', '22', '[\"2014-06-26\",\"2014-08-10\",\"2014-08-11\",\"2014-08-12\",\"2014-08-15\",\"2014-08-16\",\"2014-08-17\",\"2014-08-18\",\"2014-08-19\",\"2014-08-22\",\"2014-08-23\",\"2014-08-25\",\"2014-08-26\",\"2014-08-29\"]', '2016-07-03 19:32:58', '2016-07-03 19:32:58');
INSERT INTO `unavailability` VALUES ('11', '25', '[\"\"]', '2016-07-10 20:40:24', '2016-07-10 20:40:24');
INSERT INTO `unavailability` VALUES ('14', '27', '[\"\"]', '2016-07-15 19:40:44', '2016-07-15 19:40:44');
INSERT INTO `unavailability` VALUES ('21', '28', '[\"\"]', '2016-07-15 20:33:08', '2016-07-15 20:33:08');
INSERT INTO `unavailability` VALUES ('23', '26', '[\"2016-07-21\",\"2016-07-22\",\"2016-07-27\",\"2016-07-28\",\"2016-07-29\",\"2016-07-30\"]', '2016-07-15 23:41:46', '2016-07-15 23:41:46');
INSERT INTO `unavailability` VALUES ('24', '29', '[\"2016-07-20\",\"2016-07-21\",\"2016-07-22\",\"2016-07-27\",\"2016-07-28\"]', '2016-07-16 00:15:25', '2016-07-16 00:15:25');
INSERT INTO `unavailability` VALUES ('26', '30', '[\"2016-07-20\",\"2016-07-21\",\"2016-07-22\",\"2016-07-27\",\"2016-07-28\"]', '2016-07-16 00:19:43', '2016-07-16 00:19:43');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `user_company_id` int(11) NOT NULL,
  `user_department_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `job_title` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '0', 0x00000000000000000000000000000001, '1', '0', 'developer', '78ad37d71e5980cf663a0fcc587283919840b35d', null, 'developer@developer.com', null, null, null, 'aaad72d2e4789863c98ea750e5dc418d6b3b4be1', '1363843923', '1468604142', '1', 'Developer', 'Developer', '', '-', '');
INSERT INTO `users` VALUES ('2', '0', 0x00000000000000000000000000000001, '1', '0', 'admin', '2c1e547d411858a067761fa4ddec3b6bd0450060', null, 'admin@admin.com', null, null, null, '035f02ec04f3df7d6edb85f1ab71cbac78200cfc', '1363843868', '1468177242', '1', 'Admin', 'Admin', '', '-', '');
INSERT INTO `users` VALUES ('3', '0', 0x00000000000000000000000000000001, '1', '0', 'useruser', '60569a453d5f2c510b47c4be3816ac15c043b0cb', null, 'user@user.com', null, null, null, null, '1363752379', '1467304501', '1', 'User', 'User', '', '-', '');
INSERT INTO `users` VALUES ('4', '0', 0x00000000000000000000000000000001, '1', '0', 'blogger', '42347eb961116725c5e6f002195bcc82cb817514', null, 'blogger@blogger.com', null, null, null, null, '1363748312', '1365536687', '1', 'Blogger', 'Blogger', '', '-', '');
INSERT INTO `users` VALUES ('5', '0', 0x00000000000000000000000000000001, '1', '0', 'editor', '6da83bfbe5fd1eac8c7c02f6171d1919f1c6ea92', null, 'editor@editor.com', null, null, null, null, '1363748281', '1365523224', '1', 'Editor', 'Editor', '', '-', '');
INSERT INTO `users` VALUES ('6', '0', 0x32313330373036343333, '1', '0', 'amarvora', '4e9bd203d59653d847463e14c84186b4cf3f1885', null, 'amar@amkosys.com', null, null, null, null, '1395767885', '1395767885', '1', 'Amar', 'Vora', 'Amkosys', '281-9726603', '');
INSERT INTO `users` VALUES ('7', '0', 0x30, '0', '0', 'alamincse07', '68085a513c3a8d024b6a0bad42aaa7afdd482aa8', null, 'alamin_cse07@yahoo.com', null, null, null, null, '1467569937', '1467570069', '1', 'alamin', 'abdullah', 'w3', '+88-0171746', '');
INSERT INTO `users` VALUES ('8', '0', 0x30, '0', '0', 'mithu', 'f735cce4bb522a995e8dc3599c70132a7bdf43bb', null, 'mithu@w3.com', null, null, null, null, '1467570471', '1467570579', '1', 'rashid', 'mithu', 'w3', '-', '');
INSERT INTO `users` VALUES ('9', '0', 0x30, '0', '0', 'alamin', '8d2a3646f9ab06524ed20e7230fd2c330d4f57f9', null, 'alamincse07@gamil.com', null, null, null, null, '1468162763', '1468163085', '1', 'alamin', 'abdullah', 'w2', '880-1717469', '');
INSERT INTO `users` VALUES ('10', '0', 0x30, '0', '0', 'joshviner', '838ad92e87a3bb25d9d8ef6983042cab87f6334a', null, 'josh@josh.com', null, null, null, null, '1468164648', '1468164888', '1', 'josh', 'viner', 'w3', '-', '');
INSERT INTO `users` VALUES ('11', '0', 0x30, '0', '0', 'caryl', '1ba16a6f63e6940fc70b523fe345ebf74d28d1b1', null, 'caryl@yahoo.com', null, null, null, null, '1468165164', '1468174194', '1', 'caryl', 'longden', 'w3', '-', '');
INSERT INTO `users` VALUES ('12', '0', 0x30, '0', '0', 'johnlotd', '0401df30c1f3704e0bfa400be59676bda7d769c6', null, 'john@yahoo.com', null, null, null, null, '1468178497', '1468617560', '1', 'john', 'liotyer', 'w2', '6465-6546464645546', '');
INSERT INTO `users` VALUES ('13', '0', 0x30, '0', '0', 'criscris@yahoo.com', '81182f212c436aac533bfaecb1c1488d66730628', null, 'criscris@yahoo.com', null, null, null, null, '1468621827', '1468621835', '1', 'cris', 'jension', 'kij', '+18-6565671212', '');

-- ----------------------------
-- Table structure for `users_groups`
-- ----------------------------
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_groups
-- ----------------------------
INSERT INTO `users_groups` VALUES ('1', '1', '5');
INSERT INTO `users_groups` VALUES ('2', '2', '4');
INSERT INTO `users_groups` VALUES ('3', '3', '3');
INSERT INTO `users_groups` VALUES ('4', '4', '2');
INSERT INTO `users_groups` VALUES ('5', '5', '1');
INSERT INTO `users_groups` VALUES ('6', '7', '1');
INSERT INTO `users_groups` VALUES ('7', '8', '1');
INSERT INTO `users_groups` VALUES ('8', '9', '1');
INSERT INTO `users_groups` VALUES ('9', '10', '3');
INSERT INTO `users_groups` VALUES ('10', '11', '1');
INSERT INTO `users_groups` VALUES ('11', '12', '1');
INSERT INTO `users_groups` VALUES ('12', '13', '1');

-- ----------------------------
-- Table structure for `vacations`
-- ----------------------------
DROP TABLE IF EXISTS `vacations`;
CREATE TABLE `vacations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `approval_status_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vacations
-- ----------------------------
INSERT INTO `vacations` VALUES ('1', '4', '2014-04-04', '4', '0000-00-00 00:00:00', '2014-04-04 13:56:20');
INSERT INTO `vacations` VALUES ('6', '1', '2014-04-21', '1', '2014-04-04 10:54:02', '0000-00-00 00:00:00');
INSERT INTO `vacations` VALUES ('7', '1', '2014-06-27', '1', '2014-04-29 03:59:55', '0000-00-00 00:00:00');
INSERT INTO `vacations` VALUES ('8', '1', '2014-04-22', '1', '2014-04-29 03:59:55', '0000-00-00 00:00:00');
INSERT INTO `vacations` VALUES ('9', '1', '2014-04-11', '1', '2014-04-29 03:59:55', '0000-00-00 00:00:00');
