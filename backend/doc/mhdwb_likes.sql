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

 Date: 23/04/2019 18:28:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_likes
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_likes`;
CREATE TABLE `mhdwb_likes`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '点赞表id',
  `posts_id` int(10) NOT NULL DEFAULT 0 COMMENT '文章id',
  `user_id` int(10) NOT NULL DEFAULT 0 COMMENT '用户id',
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `created_at` int(11) NOT NULL DEFAULT 0 COMMENT '点赞时间',
  `status` tinyint(1) NOT NULL COMMENT '状态 0 取消 1点赞',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
