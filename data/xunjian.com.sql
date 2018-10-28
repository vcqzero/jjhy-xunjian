/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : 127.0.0.1:3306
Source Database       : xunjian.com

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-10-28 22:24:28
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
  `note-bin` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`workyard_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of points
-- ----------------------------
INSERT INTO `points` VALUES ('16', '巡检点', 'public/images/workyard_qrcode/1/1_701540648693.png', '1', 'qinchong', '水电费', '');
INSERT INTO `points` VALUES ('17', '第一个工地-巡检点', 'public/images/workyard_qrcode/1/1_931540648722.png', '1', 'qinchong', '大沙发上反复的发生发的发的发生的发的发的是爱的发的', '');
INSERT INTO `points` VALUES ('18', '巡检点-1', 'public/images/workyard_qrcode/1/1_841540650262.png', '1', 'qinchong', '发的发的撒', '');
INSERT INTO `points` VALUES ('19', '巡检点-2', 'public/images/workyard_qrcode/1/1_981540694837.png', '1', 'qinchong', '水电费', '');
INSERT INTO `points` VALUES ('20', '巡检点-3', 'public/images/workyard_qrcode/1/1_971540696940.png', '1', 'qinchong', '巡检点第三个', '');
INSERT INTO `points` VALUES ('21', '乐扣乐扣', 'public/images/workyard_qrcode/1/1_561540697429.png', '1', 'qinchong', '212', '');
INSERT INTO `points` VALUES ('22', '巡检点-09', 'public/images/workyard_qrcode/1/1_91540697543.png', '1', 'qinchong', '是否', '');
INSERT INTO `points` VALUES ('23', '巡检点ew', 'public/images/workyard_qrcode/1/1_761540697803.png', '1', 'qinchong', 'd', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shifts
-- ----------------------------
INSERT INTO `shifts` VALUES ('118', '下午班', '1', '', '1', '1540699200', '1540814400');
INSERT INTO `shifts` VALUES ('119', '下午班', '1', '', '1', '1540785600', '1540900800');
INSERT INTO `shifts` VALUES ('120', '下午班', '1', '', '1', '1540872000', '1540987200');
INSERT INTO `shifts` VALUES ('121', '下午班', '1', '', '1', '1540958400', '1541073600');
INSERT INTO `shifts` VALUES ('122', '夜班', '1', 'sf', '1', '1540728000', '1540771200');
INSERT INTO `shifts` VALUES ('123', '夜班', '1', 'sf', '1', '1540814400', '1540857600');
INSERT INTO `shifts` VALUES ('125', '夜班', '1', 'sf', '1', '1540987200', '1541030400');
INSERT INTO `shifts` VALUES ('126', '夜班', '1', 'sf', '1', '1541073600', '1541116800');
INSERT INTO `shifts` VALUES ('127', '夜班', '1', 'sf', '1', '1541160000', '1541203200');
INSERT INTO `shifts` VALUES ('128', '夜班', '1', 'sf', '1', '1541246400', '1541289600');
INSERT INTO `shifts` VALUES ('129', '夜班', '1', 'sf', '1', '1541332800', '1541376000');
INSERT INTO `shifts` VALUES ('131', '夜班', '1', 'sf', '1', '1541505600', '1541548800');
INSERT INTO `shifts` VALUES ('132', '夜班', '1', 'sf', '1', '1541592000', '1541635200');
INSERT INTO `shifts` VALUES ('133', '夜班', '1', 'sf', '1', '1541678400', '1541721600');
INSERT INTO `shifts` VALUES ('134', '夜班', '1', 'sf', '1', '1541764800', '1541808000');
INSERT INTO `shifts` VALUES ('135', '夜班', '1', 'sf', '1', '1541851200', '1541894400');
INSERT INTO `shifts` VALUES ('136', '夜班', '1', 'sf', '1', '1541937600', '1541980800');
INSERT INTO `shifts` VALUES ('137', '夜班', '1', 'sf', '1', '1542024000', '1542067200');
INSERT INTO `shifts` VALUES ('138', '夜班', '1', 'sf', '1', '1542110400', '1542153600');
INSERT INTO `shifts` VALUES ('139', '夜班', '1', 'sf', '1', '1542196800', '1542240000');
INSERT INTO `shifts` VALUES ('140', '夜班', '1', 'sf', '1', '1542283200', '1542326400');
INSERT INTO `shifts` VALUES ('141', '夜班', '1', 'sf', '1', '1542369600', '1542412800');
INSERT INTO `shifts` VALUES ('142', '夜班', '1', 'sf', '1', '1542456000', '1542499200');
INSERT INTO `shifts` VALUES ('143', '夜班', '1', 'sf', '1', '1542542400', '1542585600');
INSERT INTO `shifts` VALUES ('144', '夜班', '1', 'sf', '1', '1542628800', '1542672000');
INSERT INTO `shifts` VALUES ('145', '夜班', '1', 'sf', '1', '1542715200', '1542758400');
INSERT INTO `shifts` VALUES ('146', '夜班', '1', 'sf', '1', '1542801600', '1542844800');
INSERT INTO `shifts` VALUES ('147', '夜班', '1', 'sf', '1', '1542888000', '1542931200');
INSERT INTO `shifts` VALUES ('148', '夜班', '1', 'sf', '1', '1542974400', '1543017600');
INSERT INTO `shifts` VALUES ('149', '夜班', '1', 'sf', '1', '1543060800', '1543104000');
INSERT INTO `shifts` VALUES ('150', '夜班', '1', 'sf', '1', '1543147200', '1543190400');
INSERT INTO `shifts` VALUES ('151', '夜班', '1', 'sf', '1', '1543233600', '1543276800');
INSERT INTO `shifts` VALUES ('152', '夜班', '1', 'sf', '1', '1543320000', '1543363200');
INSERT INTO `shifts` VALUES ('153', '夜班', '1', 'sf', '1', '1543406400', '1543449600');
INSERT INTO `shifts` VALUES ('154', '夜班', '1', 'sf', '1', '1543492800', '1543536000');
INSERT INTO `shifts` VALUES ('155', '夜班', '1', 'sf', '1', '1543579200', '1543622400');

-- ----------------------------
-- Table structure for shift_guard
-- ----------------------------
DROP TABLE IF EXISTS `shift_guard`;
CREATE TABLE `shift_guard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL,
  `guard_id` int(255) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8 COMMENT='规定巡检班次和保安人员的关系';

-- ----------------------------
-- Records of shift_guard
-- ----------------------------
INSERT INTO `shift_guard` VALUES ('23', '23', '90');
INSERT INTO `shift_guard` VALUES ('24', '23', '91');
INSERT INTO `shift_guard` VALUES ('25', '24', '91');
INSERT INTO `shift_guard` VALUES ('26', '25', '90');
INSERT INTO `shift_guard` VALUES ('27', '26', '91');
INSERT INTO `shift_guard` VALUES ('28', '27', '91');
INSERT INTO `shift_guard` VALUES ('29', '28', '94');
INSERT INTO `shift_guard` VALUES ('30', '29', '92');
INSERT INTO `shift_guard` VALUES ('31', '29', '93');
INSERT INTO `shift_guard` VALUES ('32', '29', '94');
INSERT INTO `shift_guard` VALUES ('33', '29', '95');
INSERT INTO `shift_guard` VALUES ('34', '29', '99');
INSERT INTO `shift_guard` VALUES ('35', '29', '100');
INSERT INTO `shift_guard` VALUES ('36', '30', '91');
INSERT INTO `shift_guard` VALUES ('37', '30', '92');
INSERT INTO `shift_guard` VALUES ('38', '31', '91');
INSERT INTO `shift_guard` VALUES ('39', '31', '92');
INSERT INTO `shift_guard` VALUES ('40', '32', '91');
INSERT INTO `shift_guard` VALUES ('41', '32', '92');
INSERT INTO `shift_guard` VALUES ('42', '33', '91');
INSERT INTO `shift_guard` VALUES ('43', '33', '92');
INSERT INTO `shift_guard` VALUES ('44', '34', '91');
INSERT INTO `shift_guard` VALUES ('45', '34', '92');
INSERT INTO `shift_guard` VALUES ('46', '35', '91');
INSERT INTO `shift_guard` VALUES ('47', '35', '92');
INSERT INTO `shift_guard` VALUES ('48', '36', '91');
INSERT INTO `shift_guard` VALUES ('49', '36', '92');
INSERT INTO `shift_guard` VALUES ('50', '37', '91');
INSERT INTO `shift_guard` VALUES ('51', '37', '92');
INSERT INTO `shift_guard` VALUES ('52', '38', '91');
INSERT INTO `shift_guard` VALUES ('53', '38', '92');
INSERT INTO `shift_guard` VALUES ('54', '39', '91');
INSERT INTO `shift_guard` VALUES ('55', '39', '92');
INSERT INTO `shift_guard` VALUES ('56', '40', '91');
INSERT INTO `shift_guard` VALUES ('57', '40', '92');
INSERT INTO `shift_guard` VALUES ('58', '41', '91');
INSERT INTO `shift_guard` VALUES ('59', '41', '92');
INSERT INTO `shift_guard` VALUES ('60', '42', '91');
INSERT INTO `shift_guard` VALUES ('61', '42', '92');
INSERT INTO `shift_guard` VALUES ('62', '43', '91');
INSERT INTO `shift_guard` VALUES ('63', '44', '91');
INSERT INTO `shift_guard` VALUES ('64', '45', '91');
INSERT INTO `shift_guard` VALUES ('65', '46', '91');
INSERT INTO `shift_guard` VALUES ('66', '46', '92');
INSERT INTO `shift_guard` VALUES ('67', '46', '93');
INSERT INTO `shift_guard` VALUES ('68', '47', '91');
INSERT INTO `shift_guard` VALUES ('69', '47', '92');
INSERT INTO `shift_guard` VALUES ('70', '47', '93');
INSERT INTO `shift_guard` VALUES ('71', '48', '91');
INSERT INTO `shift_guard` VALUES ('72', '48', '92');
INSERT INTO `shift_guard` VALUES ('73', '48', '93');
INSERT INTO `shift_guard` VALUES ('74', '49', '91');
INSERT INTO `shift_guard` VALUES ('75', '49', '92');
INSERT INTO `shift_guard` VALUES ('76', '49', '93');
INSERT INTO `shift_guard` VALUES ('77', '50', '91');
INSERT INTO `shift_guard` VALUES ('78', '50', '93');
INSERT INTO `shift_guard` VALUES ('79', '51', '91');
INSERT INTO `shift_guard` VALUES ('80', '51', '93');
INSERT INTO `shift_guard` VALUES ('81', '52', '91');
INSERT INTO `shift_guard` VALUES ('82', '52', '93');
INSERT INTO `shift_guard` VALUES ('83', '53', '91');
INSERT INTO `shift_guard` VALUES ('84', '53', '93');
INSERT INTO `shift_guard` VALUES ('85', '54', '90');
INSERT INTO `shift_guard` VALUES ('86', '55', '90');
INSERT INTO `shift_guard` VALUES ('87', '56', '90');
INSERT INTO `shift_guard` VALUES ('88', '57', '91');
INSERT INTO `shift_guard` VALUES ('89', '57', '93');
INSERT INTO `shift_guard` VALUES ('90', '58', '91');
INSERT INTO `shift_guard` VALUES ('91', '58', '93');
INSERT INTO `shift_guard` VALUES ('92', '59', '91');
INSERT INTO `shift_guard` VALUES ('93', '59', '93');
INSERT INTO `shift_guard` VALUES ('94', '60', '91');
INSERT INTO `shift_guard` VALUES ('95', '61', '91');
INSERT INTO `shift_guard` VALUES ('96', '62', '91');
INSERT INTO `shift_guard` VALUES ('99', '65', '95');
INSERT INTO `shift_guard` VALUES ('100', '66', '95');
INSERT INTO `shift_guard` VALUES ('101', '67', '95');
INSERT INTO `shift_guard` VALUES ('102', '68', '92');
INSERT INTO `shift_guard` VALUES ('103', '69', '92');
INSERT INTO `shift_guard` VALUES ('104', '70', '90');
INSERT INTO `shift_guard` VALUES ('105', '71', '90');
INSERT INTO `shift_guard` VALUES ('106', '72', '91');
INSERT INTO `shift_guard` VALUES ('107', '73', '91');
INSERT INTO `shift_guard` VALUES ('108', '74', '91');
INSERT INTO `shift_guard` VALUES ('109', '75', '91');
INSERT INTO `shift_guard` VALUES ('110', '76', '90');
INSERT INTO `shift_guard` VALUES ('111', '77', '90');
INSERT INTO `shift_guard` VALUES ('112', '78', '90');
INSERT INTO `shift_guard` VALUES ('113', '79', '93');
INSERT INTO `shift_guard` VALUES ('114', '80', '95');
INSERT INTO `shift_guard` VALUES ('115', '81', '95');
INSERT INTO `shift_guard` VALUES ('116', '82', '95');
INSERT INTO `shift_guard` VALUES ('117', '83', '95');
INSERT INTO `shift_guard` VALUES ('118', '84', '91');
INSERT INTO `shift_guard` VALUES ('119', '85', '91');
INSERT INTO `shift_guard` VALUES ('120', '86', '91');
INSERT INTO `shift_guard` VALUES ('121', '87', '91');
INSERT INTO `shift_guard` VALUES ('122', '118', '90');
INSERT INTO `shift_guard` VALUES ('123', '119', '90');
INSERT INTO `shift_guard` VALUES ('124', '120', '90');
INSERT INTO `shift_guard` VALUES ('125', '121', '90');
INSERT INTO `shift_guard` VALUES ('126', '122', '90');
INSERT INTO `shift_guard` VALUES ('127', '122', '92');
INSERT INTO `shift_guard` VALUES ('128', '123', '90');
INSERT INTO `shift_guard` VALUES ('129', '123', '92');
INSERT INTO `shift_guard` VALUES ('130', '124', '90');
INSERT INTO `shift_guard` VALUES ('131', '124', '92');
INSERT INTO `shift_guard` VALUES ('132', '125', '90');
INSERT INTO `shift_guard` VALUES ('133', '125', '92');
INSERT INTO `shift_guard` VALUES ('134', '126', '90');
INSERT INTO `shift_guard` VALUES ('135', '126', '92');
INSERT INTO `shift_guard` VALUES ('136', '127', '90');
INSERT INTO `shift_guard` VALUES ('137', '127', '92');
INSERT INTO `shift_guard` VALUES ('138', '128', '90');
INSERT INTO `shift_guard` VALUES ('139', '128', '92');
INSERT INTO `shift_guard` VALUES ('140', '129', '90');
INSERT INTO `shift_guard` VALUES ('141', '129', '92');
INSERT INTO `shift_guard` VALUES ('142', '130', '90');
INSERT INTO `shift_guard` VALUES ('143', '130', '92');
INSERT INTO `shift_guard` VALUES ('144', '131', '90');
INSERT INTO `shift_guard` VALUES ('145', '131', '92');
INSERT INTO `shift_guard` VALUES ('146', '132', '90');
INSERT INTO `shift_guard` VALUES ('147', '132', '92');
INSERT INTO `shift_guard` VALUES ('148', '133', '90');
INSERT INTO `shift_guard` VALUES ('149', '133', '92');
INSERT INTO `shift_guard` VALUES ('150', '134', '90');
INSERT INTO `shift_guard` VALUES ('151', '134', '92');
INSERT INTO `shift_guard` VALUES ('152', '135', '90');
INSERT INTO `shift_guard` VALUES ('153', '135', '92');
INSERT INTO `shift_guard` VALUES ('154', '136', '90');
INSERT INTO `shift_guard` VALUES ('155', '136', '92');
INSERT INTO `shift_guard` VALUES ('156', '137', '90');
INSERT INTO `shift_guard` VALUES ('157', '137', '92');
INSERT INTO `shift_guard` VALUES ('158', '138', '90');
INSERT INTO `shift_guard` VALUES ('159', '138', '92');
INSERT INTO `shift_guard` VALUES ('160', '139', '90');
INSERT INTO `shift_guard` VALUES ('161', '139', '92');
INSERT INTO `shift_guard` VALUES ('162', '140', '90');
INSERT INTO `shift_guard` VALUES ('163', '140', '92');
INSERT INTO `shift_guard` VALUES ('164', '141', '90');
INSERT INTO `shift_guard` VALUES ('165', '141', '92');
INSERT INTO `shift_guard` VALUES ('166', '142', '90');
INSERT INTO `shift_guard` VALUES ('167', '142', '92');
INSERT INTO `shift_guard` VALUES ('168', '143', '90');
INSERT INTO `shift_guard` VALUES ('169', '143', '92');
INSERT INTO `shift_guard` VALUES ('170', '144', '90');
INSERT INTO `shift_guard` VALUES ('171', '144', '92');
INSERT INTO `shift_guard` VALUES ('172', '145', '90');
INSERT INTO `shift_guard` VALUES ('173', '145', '92');
INSERT INTO `shift_guard` VALUES ('174', '146', '90');
INSERT INTO `shift_guard` VALUES ('175', '146', '92');
INSERT INTO `shift_guard` VALUES ('176', '147', '90');
INSERT INTO `shift_guard` VALUES ('177', '147', '92');
INSERT INTO `shift_guard` VALUES ('178', '148', '90');
INSERT INTO `shift_guard` VALUES ('179', '148', '92');
INSERT INTO `shift_guard` VALUES ('180', '149', '90');
INSERT INTO `shift_guard` VALUES ('181', '149', '92');
INSERT INTO `shift_guard` VALUES ('182', '150', '90');
INSERT INTO `shift_guard` VALUES ('183', '150', '92');
INSERT INTO `shift_guard` VALUES ('184', '151', '90');
INSERT INTO `shift_guard` VALUES ('185', '151', '92');
INSERT INTO `shift_guard` VALUES ('186', '152', '90');
INSERT INTO `shift_guard` VALUES ('187', '152', '92');
INSERT INTO `shift_guard` VALUES ('188', '153', '90');
INSERT INTO `shift_guard` VALUES ('189', '153', '92');
INSERT INTO `shift_guard` VALUES ('190', '154', '90');
INSERT INTO `shift_guard` VALUES ('191', '154', '92');
INSERT INTO `shift_guard` VALUES ('192', '155', '90');
INSERT INTO `shift_guard` VALUES ('193', '155', '92');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time
-- ----------------------------
INSERT INTO `shift_time` VALUES ('1', '9', 'DONE', '79');
INSERT INTO `shift_time` VALUES ('7', '13', 'DONE', '79');
INSERT INTO `shift_time` VALUES ('9', '13', 'WORKING', '79');
INSERT INTO `shift_time` VALUES ('10', '15', 'WORKING', '79');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='定义保安巡检任务和该次巡检次数的关系';

-- ----------------------------
-- Records of shift_time_point
-- ----------------------------
INSERT INTO `shift_time_point` VALUES ('4', '7', '8', '是大V', '1540529981', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('5', '7', '11', '各个8', '1540530575', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('6', '7', '13', '', '1540532373', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('7', '7', '12', '', '1540535689', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('8', '7', '14', '', '1540535714', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('9', '7', '15', '', '1540535764', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('10', '8', '15', '', '1540535832', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('11', '8', '8', '', '1540535938', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('14', '8', '13', '', '1540536013', '[116.40717,39.90469]');
INSERT INTO `shift_time_point` VALUES ('15', '8', '14', '', '1540536039', '[116.40717,39.90469]');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shift_type
-- ----------------------------
INSERT INTO `shift_type` VALUES ('10', '午班', '12:00', '18:00', 'YES', '112');
INSERT INTO `shift_type` VALUES ('11', '晚班', '20:00', '00:00', 'NO', '12');
INSERT INTO `shift_type` VALUES ('15', 'zoab', '00:00', '20:52', 'NO', '12');
INSERT INTO `shift_type` VALUES ('16', 'c次日', '20:00', '08:00', 'YES', '2');
INSERT INTO `shift_type` VALUES ('19', '下午班', '12:00', '20:00', 'NO', '1');
INSERT INTO `shift_type` VALUES ('20', '夜班', '20:00', '08:00', 'YES', '1');
INSERT INTO `shift_type` VALUES ('21', '早班', '08:00', '12:00', 'NO', '1');
INSERT INTO `shift_type` VALUES ('22', '巡检点', '00:00', '12:00', 'NO', '1');

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
  `email-bin` varchar(255) NOT NULL DEFAULT '',
  `initial_password_bin` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'root', '$2y$10$ppSI1IiDKMBCwoBkpsfXG.0GKHQqZVD4rElKUCj4DNRV3hmY89ODW', '0', '', '', 'ENABLED', '', '', 'SUPER_ADMIN');
INSERT INTO `users` VALUES ('79', 'qinchong', '$2y$10$xzKBQNHVC7kdt4zuOk6sDuyZ//lMtXR33gxF25Vm6f5/4h8zxP3Gi', '1', '秦崇', '13001030857', 'ENABLED', '', 'DnSmNx', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('83', 'root_test', '$2y$10$dgRR3BKvijVPkv0NgQmt0ukGfMB8cQel0YEASICM.pqsXgBRHxCDC', '1', 'test_real_name_first', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('84', 'user_1', '$2y$10$s6c12qWoJf98wgzqPXJRjegWCHnySuv10OjcjW32RB94nfV89.wj6', '2', '1', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('85', 'user_2', '$2y$10$.k2KCbxrNhamo7kZPeftMOc99NaEhhYJdKM49ZO6yxn2joeuShKOm', '7', 'user_2', '13001030857', 'WAIT_CHANGE_PASSWORD_RESET_PASSWORD', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('86', 'user_3', '$2y$10$wpkfltTADp9q4DhEZglIGeufyoSsCc5eb.Ig/4Rkc4E.V6t76722e', '4', 'user_3', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('87', 'user_4', '$2y$10$l20GdoNzRNY3dIxfopeove2MAV16H85qEo/PgakLyJiw.i6uyjtsi', '7', 'w', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('88', 'test', '$2y$10$rupzraUBhJOq9BAGYo5mZu6VF5YhirLLMoT1E2z10UiyQvRhSqetS', '1', 'sf', '13001030857', 'WAIT_CHANGE_PASSWORD_RESET_PASSWORD', '', '', 'WORKYARD_ADMIN');
INSERT INTO `users` VALUES ('89', 'q_xun_1', '$2y$10$gLPpycRmRoTiXIOm.buYkObrRudlI8bvn0sIl.ShQXyufn0Yv3HnG', '11', '1585', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('90', 'qinchongq', '$2y$10$98pZEbExxsjzq27e6NEXHuyNpJaidb78QP6L53TlKUwJU2.3K0xpy', '1', '145645', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('91', 'guard_1', '$2y$10$sKQAL9/NhURSJ/Tja3zMneeGz0ZEGCJDwtTQahAYTVMrE5KMxifeW', '1', 's', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('92', 'guard_2', '$2y$10$qvW0EKaC3cDVgNyjI/H44e8NalS8d.Oz93KtyyklIq5K4esryPOBi', '1', '12', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('93', 'guard_3', '$2y$10$APM7tJqAdczDd1WaT1f7i.BVP/HkRtJ/VVfQP.BDOTUmmUZVuS1ui', '1', '12', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('94', 'guard_4', '$2y$10$AxBJA8aXc.Nmo7Wt9fMECeAsHY5a1.1K3ngOBs1uyzsZp9IWh6B3K', '1', '2', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('95', 'guard_5', '$2y$10$ZdSiuN27BM0luA7Kp8LoJ.yNyGfOdO9JajdmLZ0CzLUjleoJNtE8G', '1', '3', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('96', 'guard_6', '$2y$10$8Zd0RC3sSspGJsg3t4cwOO4EUgnW.2Mf.ogxEEPePxOlsRhjtfzNG', '1', '1', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('97', 'guard_7', '$2y$10$jsoUPQdpcXos1YwwK2OhZuQopUwG.Bi2JtryRRviiLlFznsNIDv1C', '1', 'f', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('98', 'guard_8', '$2y$10$xLefBaj982XNF2TIVALeKObaXakZAQhCJ.bAW8M6ljjBL.Dmi5qbC', '1', 'guard_8', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('99', 'guard_9', '$2y$10$o/btSvGG7xBPMRmotaF.MehQ3dEp8ynwmmK3Dscpi8cv0piYyv.km', '1', 'guard_9', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('100', 'guard_10', '$2y$10$khN1gTPbo8/F4wUrcFom6eYLXsDpz09H4gxno4RtsmcILkCly.3NS', '1', '1', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');
INSERT INTO `users` VALUES ('101', 'guard_11', '$2y$10$zkTp3yeE35yLOk9glM/wH.tqS9PC1c5W4tb3axDRUxROS5FA96Poi', '1', '1', '13001030857', 'WAIT_CHANGE_PASSWORD_NEW_CREATED', '', '', 'WORKYARD_GUARD');

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
INSERT INTO `workyards` VALUES ('1', '第一个工地', '工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息工地备注信息', '北京市朝阳区劲松54号院11号楼', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('2', 'gsg', 'dfg', '2', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('3', 'df', 'sf', '3', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('4', 'sdfs', '', '4', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
INSERT INTO `workyards` VALUES ('7', '测试', '似懂非懂', '的', '[[116.403053,39.862376],[116.39859,39.865605],[116.400478,39.870348],[116.41361,39.868239]]');
