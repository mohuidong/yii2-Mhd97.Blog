/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.1.177
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : 192.168.1.177:3306
 Source Schema         : MHD97

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 23/04/2019 18:29:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_reply
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_reply`;
CREATE TABLE `mhdwb_reply`  (
  `reply_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '回复id',
  `posts_id` int(10) NOT NULL COMMENT '文章id',
  `user_id` int(10) NOT NULL COMMENT '用户名',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '回复内容',
  `created_at` int(10) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`reply_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
