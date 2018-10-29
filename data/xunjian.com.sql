/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : xunjian.com

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-10-29 17:37:29
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of points
-- ----------------------------
INSERT INTO `points` VALUES ('16', '巡检点', 'public/images/workyard_qrcode/1/1_701540648693.png', '1', 'qinchong', '水电费', '0');
INSERT INTO `points` VALUES ('17', '第一个工地-巡检点', 'public/images/workyard_qrcode/1/1_931540648722.png', '1', 'qinchong', '大沙发上反复的发生发的发的发生的发的发的是爱的发的', '0');
INSERT INTO `points` VALUES ('18', '巡检点-1', 'public/images/workyard_qrcode/1/1_841540650262.png', '1', 'qinchong', '发的发的撒', '0');
INSERT INTO `points` VALUES ('19', '巡检点-2', 'public/images/workyard_qrcode/1/1_981540694837.png', '1', 'qinchong', '水电费', '0');
INSERT INTO `points` VALUES ('20', '巡检点-3', 'public/images/workyard_qrcode/1/1_971540696940.png', '1', 'qinchong', '巡检点第三个', '0');
INSERT INTO `points` VALUES ('21', '乐扣乐扣', 'public/images/workyard_qrcode/1/1_561540697429.png', '1', 'qinchong', '212', '0');
INSERT INTO `points` VALUES ('22', '巡检点-09', 'public/images/workyard_qrcode/1/1_91540697543.png', '1', 'qinchong', '是否', '0');
INSERT INTO `points` VALUES ('23', '巡检点ew', 'public/images/workyard_qrcode/1/1_761540697803.png', '1', 'qinchong', 'd', '0');
INSERT INTO `points` VALUES ('24', '带时间的巡检点', 'public/images/workyard_qrcode/1/1_371540795229.png', '1', 'qinchong', '12额2额2额额', '1540795229');
INSERT INTO `points` VALUES ('25', '又一个巡检点', 'data/qrcode/1/1_441540803768.png', '1', 'qinchong', '地方大幅度发', '1540803768');

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
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shifts
-- ----------------------------
INSERT INTO `shifts` VALUES ('163', '早班', '1', '早班 巡逻一圈即可', '1', '1540864800', '1540980000');
INSERT INTO `shifts` VALUES ('164', '早班', '1', '早班 巡逻一圈即可', '1', '1540951200', '1541066400');
INSERT INTO `shifts` VALUES ('165', '早班', '1', '早班 巡逻一圈即可', '1', '1541037600', '1541152800');
INSERT INTO `shifts` VALUES ('166', '早班', '1', '早班 巡逻一圈即可', '1', '1541124000', '1541239200');
INSERT INTO `shifts` VALUES ('167', '早班', '1', '早班 巡逻一圈即可', '1', '1541210400', '1541325600');
INSERT INTO `shifts` VALUES ('168', '早班', '1', '早班 巡逻一圈即可', '1', '1541296800', '1541412000');
INSERT INTO `shifts` VALUES ('169', '早班', '1', '早班 巡逻一圈即可', '1', '1541383200', '1541498400');
INSERT INTO `shifts` VALUES ('170', '早班', '1', '早班 巡逻一圈即可', '1', '1541469600', '1541584800');
INSERT INTO `shifts` VALUES ('171', '早班', '1', '早班 巡逻一圈即可', '1', '1541556000', '1541671200');
INSERT INTO `shifts` VALUES ('172', '早班', '1', '早班 巡逻一圈即可', '1', '1541642400', '1541757600');
INSERT INTO `shifts` VALUES ('173', '早班', '1', '早班 巡逻一圈即可', '1', '1541728800', '1541844000');
INSERT INTO `shifts` VALUES ('174', '早班', '1', '早班 巡逻一圈即可', '1', '1541815200', '1541930400');

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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8 COMMENT='规定巡检班次和保安人员的关系';

-- ----------------------------
-- Records of shift_guard
-- ----------------------------
INSERT INTO `shift_guard` VALUES ('195', '156', '91');
INSERT INTO `shift_guard` VALUES ('196', '156', '92');
INSERT INTO `shift_guard` VALUES ('197', '156', '93');
INSERT INTO `shift_guard` VALUES ('210', '161', '91');
INSERT INTO `shift_guard` VALUES ('211', '161', '92');
INSERT INTO `shift_guard` VALUES ('212', '161', '93');
INSERT INTO `shift_guard` VALUES ('213', '161', '94');
INSERT INTO `shift_guard` VALUES ('214', '162', '91');
INSERT INTO `shift_guard` VALUES ('215', '162', '92');
INSERT INTO `shift_guard` VALUES ('216', '163', '91');
INSERT INTO `shift_guard` VALUES ('217', '163', '92');
INSERT INTO `shift_guard` VALUES ('218', '164', '91');
INSERT INTO `shift_guard` VALUES ('219', '164', '92');
INSERT INTO `shift_guard` VALUES ('220', '165', '91');
INSERT INTO `shift_guard` VALUES ('221', '165', '92');
INSERT INTO `shift_guard` VALUES ('222', '166', '91');
INSERT INTO `shift_guard` VALUES ('223', '166', '92');
INSERT INTO `shift_guard` VALUES ('224', '167', '91');
INSERT INTO `shift_guard` VALUES ('225', '167', '92');
INSERT INTO `shift_guard` VALUES ('226', '168', '91');
INSERT INTO `shift_guard` VALUES ('227', '168', '92');
INSERT INTO `shift_guard` VALUES ('228', '169', '91');
INSERT INTO `shift_guard` VALUES ('229', '169', '92');
INSERT INTO `shift_guard` VALUES ('230', '170', '91');
INSERT INTO `shift_guard` VALUES ('231', '170', '92');
INSERT INTO `shift_guard` VALUES ('232', '171', '91');
INSERT INTO `shift_guard` VALUES ('233', '171', '92');
INSERT INTO `shift_guard` VALUES ('234', '172', '91');
INSERT INTO `shift_guard` VALUES ('235', '172', '92');
INSERT INTO `shift_guard` VALUES ('236', '173', '91');
INSERT INTO `shift_guard` VALUES ('237', '173', '92');
INSERT INTO `shift_guard` VALUES ('238', '174', '91');
INSERT INTO `shift_guard` VALUES ('239', '174', '92');

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
INSERT INTO `shift_time` VALUES ('1', '161', 'DONE', '94');
INSERT INTO `shift_time` VALUES ('7', '161', 'DONE', '94');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shift_type
-- ----------------------------
INSERT INTO `shift_type` VALUES ('10', '午班', '12:00', '18:00', 'YES', '112');
INSERT INTO `shift_type` VALUES ('11', '晚班', '20:00', '00:00', 'NO', '12');
INSERT INTO `shift_type` VALUES ('15', 'zoab', '00:00', '20:52', 'NO', '12');
INSERT INTO `shift_type` VALUES ('16', 'c次日', '20:00', '08:00', 'YES', '2');
INSERT INTO `shift_type` VALUES ('23', '早班', '10:00', '18:00', 'NO', '1');
INSERT INTO `shift_type` VALUES ('24', '夜班', '18:00', '02:00', 'YES', '1');
INSERT INTO `shift_type` VALUES ('25', '凌晨', '02:00', '10:00', 'NO', '1');

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
INSERT INTO `users` VALUES ('91', 'guard_1', '$2y$10$sHk68rVa2pgSKpEK1akf6ubj.HxBDH0lRstxiq0Q/U.A3xYZ51QUu', '1', 's', '13001030857', 'ENABLED', '', '', 'WORKYARD_GUARD');
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
