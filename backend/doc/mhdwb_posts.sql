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

 Date: 13/03/2019 16:56:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_posts
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_posts`;
CREATE TABLE `mhdwb_posts`  (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `summary` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '摘要',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章',
  `label_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标签图',
  `cat_id` int(8) NOT NULL COMMENT '分类id',
  `user_id` int(8) NOT NULL COMMENT '用户id',
  `user_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '10正在审核 11审核中 12已审核',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章主表' ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
