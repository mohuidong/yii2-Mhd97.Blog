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

 Date: 23/04/2019 18:29:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_answer
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_answer`;
CREATE TABLE `mhdwb_answer`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '答案id',
  `user_id` int(10) NOT NULL DEFAULT 0 COMMENT '用户id',
  `answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '答案内容',
  `created_at` int(10) NOT NULL DEFAULT 0 COMMENT '发布时间',
  `question_id` int(10) NOT NULL DEFAULT 0 COMMENT '问题id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
