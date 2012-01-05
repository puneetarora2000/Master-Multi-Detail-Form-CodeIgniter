/*
Navicat MySQL Data Transfer

Source Server         : LEVASQUEZ
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : ci_form

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2012-01-05 08:09:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `options`
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `type` varchar(24) NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `options` VALUES ('1', '1', '1', 'colors', 'Detail', '0');

-- ----------------------------
-- Table structure for `option_values`
-- ----------------------------
DROP TABLE IF EXISTS `option_values`;
CREATE TABLE `option_values` (
  `id` int(11) NOT NULL auto_increment,
  `option_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` float(10,2) NOT NULL,
  `weight` float(10,2) NOT NULL,
  `sequence` int(11) NOT NULL,
  `limit` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of option_values
-- ----------------------------
INSERT INTO `option_values` VALUES ('1', '1', 'pink', '', '299.99', '0.00', '0', null);
INSERT INTO `option_values` VALUES ('2', '1', 'black', '', '289.99', '0.00', '1', null);
INSERT INTO `option_values` VALUES ('3', '1', 'blue', '', '289.99', '0.00', '2', null);
INSERT INTO `option_values` VALUES ('4', '1', 'white', '', '289.99', '0.00', '3', null);

-- ----------------------------
-- Table structure for `products`
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sku` varchar(30) default NULL,
  `name` varchar(128) default NULL,
  `slug` varchar(128) default NULL,
  `route_id` int(11) NOT NULL,
  `description` text,
  `excerpt` text,
  `price` float(10,2) NOT NULL default '0.00',
  `saleprice` float(10,2) NOT NULL default '0.00',
  `free_shipping` tinyint(1) NOT NULL default '0',
  `shippable` tinyint(1) NOT NULL default '1',
  `weight` varchar(10) NOT NULL default '0',
  `in_stock` tinyint(1) NOT NULL default '1',
  `related_products` text,
  `images` text,
  `seo_title` text,
  `meta` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'Dell Mini 1010', 'Dell Mini 1010', 'dell-mini-1010', '0', null, null, '299.99', '289.99', '0', '1', '100', '1', null, '{\"4290ad590980741054145abf2991848b\":{\"filename\":\"4290ad590980741054145abf2991848b.jpg\",\"alt\":\"\",\"caption\":\"\",\"primary\":true}}', null, null);
