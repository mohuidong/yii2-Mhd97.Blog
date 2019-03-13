
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `mhdwb_system_setting`;
CREATE TABLE `mhdwb_system_setting`  (
  `key` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '键',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '值',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '系统设置' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
