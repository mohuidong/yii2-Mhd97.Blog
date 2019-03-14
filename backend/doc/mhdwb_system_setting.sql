/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.1.96
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : 192.168.1.96:3306
 Source Schema         : MHD97

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 14/03/2019 11:48:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_system_setting
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_system_setting`;
CREATE TABLE `mhdwb_system_setting`  (
  `key` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '键',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '值',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '系统设置' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
