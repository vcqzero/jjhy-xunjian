/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : xunjian.com

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-10-24 17:42:23
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
  `note` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of points
-- ----------------------------
INSERT INTO `points` VALUES ('8', '第三个巡检点', 'public/images/workyard_qrcode/1/1_291539230123.png', '1', 'test1', 'sdf', '是v');
INSERT INTO `points` VALUES ('11', '沙发灯', 'public/images/workyard_qrcode/1/1_881539233006.png', '1', 'test1', 'sdf', '沙发灯');
INSERT INTO `points` VALUES ('12', 'sdf是', 'public/images/workyard_qrcode/1/1_721539233143.png', '1', 'test1', '地方', 'sdf');
INSERT INTO `points` VALUES ('13', '强无敌', 'public/images/workyard_qrcode/1/1_791539234516.png', '1', 'test1', '强无敌', '');
INSERT INTO `points` VALUES ('14', 'sdf', 'public/images/workyard_qrcode/1/1_851539234763.png', '1', 'test1', '水电费东窗事发', '');
INSERT INTO `points` VALUES ('15', '维吾尔GV热VR', 'public/images/workyard_qrcode/1/1_231539234824.png', '1', 'test1', '威风威风擦擦擦是的范德萨', '');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shifts
-- ----------------------------
INSERT INTO `shifts` VALUES ('8', '凌晨-测试', '1', 'dsfd ', '3', '1540346400', '1540432800');
INSERT INTO `shifts` VALUES ('9', '全天', '1', '12', '2', '1540555200', '1540303200');
INSERT INTO `shifts` VALUES ('13', '晚班', '1', '请仔细巡逻 共两个人', '10', '1540555200', '1540569600');

-- ----------------------------
-- Table structure for shift_guard
-- ----------------------------
DROP TABLE IF EXISTS `shift_guard`;
CREATE TABLE `shift_guard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL,
  `guard_id` int(255) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='规定巡检班次和保安人员的关系';

-- ----------------------------
-- Records of shift_guard
-- ----------------------------
INSERT INTO `shift_guard` VALUES ('1', '5', '79');
INSERT INTO `shift_guard` VALUES ('2', '5', '79');
INSERT INTO `shift_guard` VALUES ('3', '6', '79');
INSERT INTO `shift_guard` VALUES ('4', '7', '79');
INSERT INTO `shift_guard` VALUES ('7', '8', '79');
INSERT INTO `shift_guard` VALUES ('8', '9', '79');
INSERT INTO `shift_guard` VALUES ('9', '10', '79');
INSERT INTO `shift_guard` VALUES ('10', '11', '79');
INSERT INTO `shift_guard` VALUES ('11', '12', '81');
INSERT INTO `shift_guard` VALUES ('12', '13', '80');
INSERT INTO `shift_guard` VALUES ('13', '13', '81');

-- ----------------------------
-- Table structure for shift_time
-- ----------------------------
DROP TABLE IF EXISTS `shift_time`;
CREATE TABLE `shift_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL COMMENT '本次巡检归属人',
  `start_time-bin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本次巡检开始时间',
  `end_time-bin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本次巡检结束时间',
  `status` varchar(255) NOT NULL,
  `guard_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time
-- ----------------------------
INSERT INTO `shift_time` VALUES ('1', '9', '1', '1540260000', 'DONE', '79');
INSERT INTO `shift_time` VALUES ('2', '9', '2', '1540260000', 'WORKING', '0');
INSERT INTO `shift_time` VALUES ('3', '8', '0', '0', 'WORKING', '79');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shift_type
-- ----------------------------
INSERT INTO `shift_type` VALUES ('10', '午班', '12:00', '18:00', 'YES', '1');
INSERT INTO `shift_type` VALUES ('11', '晚班', '20:00', '00:00', 'NO', '1');
INSERT INTO `shift_type` VALUES ('12', '夜班', '22:00', '08:00', 'YES', '1');
INSERT INTO `shift_type` VALUES ('13', '早班', '08 : 20', '12 : 00', 'NO', '1');
INSERT INTO `shift_type` VALUES ('14', '夜班-2', '20:00', '08:00', 'YES', '1');

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
  `email` varchar(255) NOT NULL DEFAULT '',
  `initial_password` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('22', 'root', '$2y$10$c4anS.fBREG3/wkZMB.S9ecVkqxgaZheZMv8sLWNnx5d6Nw7S4kny', '1', '秦崇', '15313715524', 'ENABLED', '', 'ad', 'SUPER_ADMIN');
INSERT INTO `users` VALUES ('79', 'qinchong', '$2y$10$v5WY8/ihvSlVTjyFr0SsUuPpkny8DbKnSV9XBV1pj2XbV2Yz9TKBS', '1', '秦崇', '13001030857', 'ENABLED', '', 'DnSmNx', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('80', 'sfdsd', '$2y$10$kZml/IFZB81e1.BepBpF.ue9h.jS6c8u.SE/lUMbnJG3hxZF/Ix9O', '1', 'D', '13001030859', 'WAIT_CHANGE_PASSWORD', '', 'G0hagM', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('81', 'sdsd', '$2y$10$L56BdOnZtcRf8AtCJ17SDOg0uN3Pyj/9urY3DP6H6obeBSv8nXpCO', '1', 'sdf', '13001030857', 'WAIT_CHANGE_PASSWORD', '', 'VuKtdA', 'WORKYARD_GUARD');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of workyards
-- ----------------------------
INSERT INTO `workyards` VALUES ('1', 'first', '工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息', '北京市朝阳区劲松54号院11号楼', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('2', 'gsg', 'dfg', '2', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('3', 'df', 'sf', '3', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('4', 'sdfs', '', '4', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('7', '测试', '似懂非懂', '的', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
