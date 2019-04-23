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

 Date: 23/04/2019 18:28:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_question
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_question`;
CREATE TABLE `mhdwb_question`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '问题id',
  `user_id` int(10) NOT NULL DEFAULT 0 COMMENT '发布者id',
  `question` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `best_id` int(10) NOT NULL DEFAULT 0 COMMENT '最优秀回答id',
  `best_user_id` int(10) NOT NULL DEFAULT 0 COMMENT '最优秀回复id',
  `created_at` int(10) NOT NULL DEFAULT 0 COMMENT '发布时间',
  `updated_at` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态 0未审核 1审核通过 2审核失败 3已解决',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
