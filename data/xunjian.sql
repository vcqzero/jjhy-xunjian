/*
Navicat MySQL Data Transfer

Source Server         : 122.14.213.239
Source Server Version : 50724
Source Host           : 122.14.213.239:3306
Source Database       : xunjian

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2018-11-14 09:14:09
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of points
-- ----------------------------
INSERT INTO `points` VALUES ('59', 'x1', 'data/qrcode/33/33_321542112891.png', '33', 'qinchong', '212', '1542112891');
INSERT INTO `points` VALUES ('60', 'x2', 'data/qrcode/33/33_141542112944.png', '33', 'qinchong', 'sd', '1542112944');
INSERT INTO `points` VALUES ('61', 'x3', 'data/qrcode/33/33_261542112958.png', '33', 'qinchong', 'x3', '1542112958');
INSERT INTO `points` VALUES ('62', 'x4', 'data/qrcode/33/33_611542112972.png', '33', 'qinchong', 'x4', '1542112972');
INSERT INTO `points` VALUES ('63', '测试一下', 'data/qrcode/33/33_821542157374.png', '33', 'qinchong', '不在巡检范围', '1542157373');

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
) ENGINE=InnoDB AUTO_INCREMENT=387 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shifts
-- ----------------------------
INSERT INTO `shifts` VALUES ('385', '中班', '33', '', '1', '1542093240', '1542121920', '1542119519');
INSERT INTO `shifts` VALUES ('386', '全天', '33', '2322', '3', '1542125100', '1542204000', '1542157259');

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
) ENGINE=InnoDB AUTO_INCREMENT=516 DEFAULT CHARSET=utf8 COMMENT='规定巡检班次和保安人员的关系';

-- ----------------------------
-- Records of shift_guard
-- ----------------------------
INSERT INTO `shift_guard` VALUES ('512', '383', '134');
INSERT INTO `shift_guard` VALUES ('513', '384', '134');
INSERT INTO `shift_guard` VALUES ('514', '385', '134');
INSERT INTO `shift_guard` VALUES ('515', '386', '134');

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time
-- ----------------------------
INSERT INTO `shift_time` VALUES ('40', '383', 'DONE', '134');
INSERT INTO `shift_time` VALUES ('41', '383', 'WORKING', '134');
INSERT INTO `shift_time` VALUES ('42', '384', 'DONE', '134');
INSERT INTO `shift_time` VALUES ('43', '384', 'DONE', '134');
INSERT INTO `shift_time` VALUES ('44', '384', 'DONE', '134');
INSERT INTO `shift_time` VALUES ('45', '385', 'WORKING', '134');
INSERT INTO `shift_time` VALUES ('46', '386', 'WORKING', '134');

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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time_point
-- ----------------------------
INSERT INTO `shift_time_point` VALUES ('42', '40', '59', '', '1542113401', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('43', '42', '59', '', '1542115004', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('44', '42', '60', '', '1542115014', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('45', '42', '61', '', '1542115021', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('46', '42', '62', '', '1542115064', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('47', '43', '61', '', '1542115089', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('48', '43', '62', '', '1542115096', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('49', '43', '60', '', '1542115102', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('50', '43', '59', '', '1542115109', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('51', '44', '59', '', '1542115137', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('52', '44', '60', '', '1542115155', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('53', '44', '62', '', '1542115163', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('54', '44', '61', '', '1542115169', '[\"116.51558\",\"39.83769\"]');
INSERT INTO `shift_time_point` VALUES ('55', '46', '63', '', '1542157390', '[\"116.467735\",\"39.88742\"]');
INSERT INTO `shift_time_point` VALUES ('56', '46', '62', '', '1542157750', '[\"116.467735\",\"39.88742\"]');

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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shift_type
-- ----------------------------
INSERT INTO `shift_type` VALUES ('43', '中班', '15:14', '23:12', 'NO', '33');
INSERT INTO `shift_type` VALUES ('44', '全天', '00:05', '22:00', 'NO', '33');

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
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('123', 'admin', '$2y$10$Th3iJ4dLNuWOzXAJlUHgHOQS9gZzsSZALN2rsCk/nypeNX61yNmYO', '0', '', '', 'ENABLED', 'SUPER_ADMIN');
INSERT INTO `users` VALUES ('127', 'cscec1', '$2y$10$ZNgaSs3zuiPcOG.WLKC94OIga7YbbH9nR0T8H3EXWLHx6v68ksjZ2', '33', '胖墩儿', '12345654321', 'ENABLED', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('128', 'cscec', '$2y$10$dkvNkSzOZAzTCSn0d1BbvuULdINpbx3jez1ulW8rqV6m.0Xsdh/hC', '31', '邢彦玖', '13520091257', 'ENABLED', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('129', 'cscec2', '$2y$10$Mpwiag/sgvO.CHULNbIa9eLvpntegz.o.jZJS/QZHMLypVkcI9NiS', '33', '邢烟酒', '13012345678', 'ENABLED', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('130', 'xyj123', '$2y$10$DsiHLB/O1h.3DfQReGXUduq1ZHkESSmlU6cMDqsIDvuGWmX/iR0MK', '28', '邢研究', '13012345678', 'ENABLED', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('131', 'qinchong', '$2y$10$ZBdKzS.JY3IP.5ashrlwQeaAxm1nNM9mwfqC4nRZaH/JU3GFgL8rC', '33', '秦崇的测试账号', '13001030857', 'ENABLED', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('132', 'qinchong1', '$2y$10$kzlaCwdK2ZzhFa.MA/KTCOjOTZ8cbtanDZ4ntJ0VSBQ5BSrn9hEBC', '28', '秦崇测试号', '13001030857', 'ENABLED', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('133', 'xyj234', '$2y$10$6LbZzAKxUyFK4U8Jp7VgG.EZow8CKcLMps9/pt5BXU5qLlgu4eiji', '33', '邢烟酒', '13012345678', 'ENABLED', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('134', 'qinchong2', '$2y$10$Fx5YauaIulS/5I3PH0A6ruXH.khOfCxofwiGEbEpIvvC2z.hd8oX2', '33', '1', '13001030857', 'ENABLED', 'WORKYARD_GUARD');

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of workyards
-- ----------------------------
INSERT INTO `workyards` VALUES ('33', '望京2', '', '望京', '[[116.452441,40.013856],[116.508917,40.007282],[116.49261,39.975192],[116.458449,39.976902]]');
INSERT INTO `workyards` VALUES ('34', '是的范德萨是', '', '劲松', '[[116.451555,39.887564],[116.465416,39.887432],[116.465416,39.881702],[116.448765,39.884502],[116.454645,39.880352],[116.44825,39.886477],[116.463528,39.889474],[116.459065,39.881307],[116.465974,39.88358],[116.456104,39.890429],[116.450353,39.885522],[116.447818,39.880721],[116.44062,39.884561],[116.450576,39.88163],[116.445727,39.887129],[116.445684,39.890751],[116.442594,39.887129],[116.438732,39.888216],[116.444997,39.88489],[116.442251,39.880477]]');
