/*
Navicat MySQL Data Transfer

Source Server         : 122.14.213.239
Source Server Version : 50724
Source Host           : 122.14.213.239:3306
Source Database       : xunjian

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2018-11-16 16:12:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for points
-- ----------------------------
DROP TABLE IF EXISTS `points`;
CREATE TABLE `points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `qrcode_filename` varchar(255) NOT NULL DEFAULT '',
  `workyard_id` int(11) NOT NULL,
  `created_by` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`workyard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of points
-- ----------------------------

-- ----------------------------
-- Table structure for shifts
-- ----------------------------
DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_type_name` varchar(255) NOT NULL DEFAULT '',
  `workyard_id` int(10) unsigned NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `times` int(11) NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workyard_id` (`workyard_id`,`start_time`,`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shifts
-- ----------------------------

-- ----------------------------
-- Table structure for shift_guard
-- ----------------------------
DROP TABLE IF EXISTS `shift_guard`;
CREATE TABLE `shift_guard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL,
  `guard_id` int(255) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_id` (`shift_id`,`guard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=520 DEFAULT CHARSET=utf8 COMMENT='规定巡检班次和保安人员的关系';

-- ----------------------------
-- Records of shift_guard
-- ----------------------------

-- ----------------------------
-- Table structure for shift_time
-- ----------------------------
DROP TABLE IF EXISTS `shift_time`;
CREATE TABLE `shift_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL COMMENT '本次巡检归属人',
  `status` varchar(255) NOT NULL,
  `guard_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time
-- ----------------------------

-- ----------------------------
-- Table structure for shift_time_point
-- ----------------------------
DROP TABLE IF EXISTS `shift_time_point`;
CREATE TABLE `shift_time_point` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_time_id` int(10) unsigned NOT NULL COMMENT '巡检班次的某一次数',
  `point_id` int(10) unsigned NOT NULL COMMENT '巡检点id',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '本次巡检点记录',
  `time` int(10) unsigned NOT NULL COMMENT '扫描巡检点时间',
  `address_path` varchar(255) NOT NULL DEFAULT '' COMMENT '扫描巡检点时巡检员地理坐标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`shift_time_id`,`point_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time_point
-- ----------------------------

-- ----------------------------
-- Table structure for shift_type
-- ----------------------------
DROP TABLE IF EXISTS `shift_type`;
CREATE TABLE `shift_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `start_time` varchar(11) NOT NULL,
  `end_time` varchar(10) NOT NULL,
  `is_next_day` varchar(255) NOT NULL,
  `workyard_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`workyard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shift_type
-- ----------------------------
INSERT INTO `shift_type` VALUES ('45', '早班', '10:25', '20:23', 'NO', '35');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户登录名',
  `password` varchar(60) NOT NULL DEFAULT '' COMMENT '用户密码 60位',
  `workyard_id` int(255) NOT NULL,
  `realname` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(24) NOT NULL DEFAULT '' COMMENT '用户电话',
  `status` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('123', 'admin', '$2y$10$Th3iJ4dLNuWOzXAJlUHgHOQS9gZzsSZALN2rsCk/nypeNX61yNmYO', '0', '', '', 'ENABLED', 'SUPER_ADMIN');
INSERT INTO `users` VALUES ('135', 'xyj123', '$2y$10$e5Ss4lSGdpigi24mIHis8OTifJ2GfKFhGme0Dzo/E1ZNKQBODCA9y', '35', '邢烟酒', '13012345678', 'ENABLED', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('136', 'xyj234', '$2y$10$3BKZDtTJVc9QrSk2fkfJF./cXEyI1YayJk8sHbas8l03EYE5yOlPa', '35', '邢研究', '13012345678', 'ENABLED', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('137', 'qinchong', '$2y$10$twsSZv5Ne3ElvIPciVbdEOl7NNKmkkTSLt97mwvYEidgKKRbqqUz2', '35', 'qinchong-test', '13001030857', 'ENABLED', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('138', 'qin1', '$2y$10$J5ayq0i/y2VhO9tLPyy5Me.Al38FBBdsHfnL037pgguzgjxkW8O1C', '35', 'q', '13001030857', 'ENABLED', 'WORKYARD_GUARD');

-- ----------------------------
-- Table structure for workyards
-- ----------------------------
DROP TABLE IF EXISTS `workyards`;
CREATE TABLE `workyards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `address_path` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of workyards
-- ----------------------------
INSERT INTO `workyards` VALUES ('35', '望京1号地块', '', '望京1号地块', '[[116.467446,40.018219],[116.471265,40.018318],[116.471415,40.016115],[116.467381,40.016148]]');
