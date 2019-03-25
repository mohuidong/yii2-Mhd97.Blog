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

 Date: 25/03/2019 17:36:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for mhdwb_posts
-- ----------------------------
DROP TABLE IF EXISTS `mhdwb_posts`;
CREATE TABLE `mhdwb_posts`  (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `summary` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '摘要',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章',
  `label_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标签图',
  `cat_id` int(8) NOT NULL COMMENT '分类id',
  `user_id` int(8) NOT NULL COMMENT '用户id',
  `user_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '10正在审核 11审核通过 12审核失败',
  `likes` int(8) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章主表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of mhdwb_posts
-- ----------------------------
INSERT INTO `mhdwb_posts` VALUES (1, '这是第一篇文章', '这是一篇简短的说明', '<p><img src=\"http://mhd97-blog.oss-cn-shenzhen.aliyuncs.com/editor/2019/03/5c88a8c9e0c5c\" style=\"width: 300px;\" class=\"fr-fic fr-dib\"></p><p>php是世界上最好的语言 没有之一</p>', 'posts/2019/03/5c88a881edc3d', 4, 0, '官方唯一指定管理员', 11, 0, 1552460361, 1552462013);
INSERT INTO `mhdwb_posts` VALUES (2, '这是第二篇文章', '我就是测试测试这个摘要到底是要多长', '<p>现在是北京时间2019年3月25日13点24分</p><p>第一行</p><p>第二行</p><p>第三行</p><p>texttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttext</p><p><img src=\"http://mhd97-blog.oss-cn-shenzhen.aliyuncs.com/editor/2019/03/5c9866325ffe9\" style=\"width: 300px;\" class=\"fr-fic fr-dib\"></p>', 'posts/2019/03/5c88a881edc3d', 5, 0, '官方唯一指定管理员', 11, 0, 1553491536, 1553491536);
INSERT INTO `mhdwb_posts` VALUES (3, 'Yii2使用RESTful API及其认证问题', 'Yii 提供了一整套用来简化实现 RESTful 风格的 Web Service 服务的 API。', '<p>1.修改config/main.php文件，在components添加&nbsp;</p><p>&#39;urlManager&#39; =&gt; [ &nbsp; &nbsp;&#39;enablePrettyUrl&#39; =&gt; true, &nbsp; &nbsp;&#39;enableStrictParsing&#39; =&gt; true, &nbsp; &nbsp;&#39;showScriptName&#39; =&gt; false, &nbsp; &nbsp;&#39;rules&#39; =&gt; [ &nbsp; &nbsp; &nbsp; &nbsp;[&#39;class&#39; =&gt; &#39;yii\\rest\\UrlRule&#39;, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&#39;controller&#39; =&gt; [ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&#39;......&#39;, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&#39;product&#39;, //对应ProductController &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;] &nbsp; &nbsp; &nbsp; &nbsp;], &nbsp; &nbsp;], ],&nbsp;</p><p>2.修改common/models/User.PHP&nbsp;</p><p>public static function findIdentityByAccessToken($token, $type = null) { &nbsp; &nbsp;return static::findOne([&#39;access_token&#39; =&gt; $token]); } </p>', 'posts/2019/03/5c88a881edc3d', 4, 0, '官方唯一指定管理员', 11, 0, 1553491642, 1553491642);
INSERT INTO `mhdwb_posts` VALUES (4, 'Yii2.0 初识 RESTful Serializer', '当RESTful API响应中包含一个资源时，该资源需要序列化成一个字符串。', '<p>Yii将这个过程分成两步，首先，资源会被yii\\rest\\Serializer转换成数组， 然后，该数组会通过yii\\web\\ResponseFormatterInterface根据请求格式(如JSON, XML)被序列化成字符串。当开发一个资源类时应重点关注第一步。&nbsp;</p><p><img src=\"http://mhd97-blog.oss-cn-shenzhen.aliyuncs.com/editor/2019/03/5c98670a43ff1\" style=\"width: 300px;\" class=\"fr-fic fr-dib\"></p>', 'posts/2019/03/5c9866ff62700', 4, 0, '官方唯一指定管理员', 11, 0, 1553491725, 1553491837);
INSERT INTO `mhdwb_posts` VALUES (5, '响应格式', '当处理一个 RESTful API 请求时，一个应用程序通常需要如下步骤', '<ol><li>确定可能影响响应格式的各种因素，例如媒介类型，语言，版本，等等。 这个过程也被称为&nbsp;<a href=\"http://en.wikipedia.org/wiki/Content_negotiation\">content negotiation</a>。</li><li>资源对象转换为数组，如在&nbsp;<a href=\"https://www.yiichina.com/doc/guide/2.0/rest-resources\">Resources</a> 部分中所描述的。 通过&nbsp;<a href=\"https://www.yiichina.com/doc/api/2.0/yii-rest-serializer\">yii\\rest\\Serializer</a> 来完成。</li><li>通过内容协商步骤将数组转换成字符串。&nbsp;<a href=\"https://www.yiichina.com/doc/api/2.0/yii-web-responseformatterinterface\">response formatters</a> 通过&nbsp;<a href=\"https://www.yiichina.com/doc/api/2.0/yii-web-response#$formatters-detail\">response</a> 应用程序 组件来注册完成。</li></ol>', 'posts/2019/03/5c88a881edc3d', 4, 0, '官方唯一指定管理员', 11, 0, 1553491779, 1553491831);
INSERT INTO `mhdwb_posts` VALUES (6, '认证', '认证是鉴定用户身份的过程。', '<p>它通常使用一个标识符 （如用户名或电子邮件地址）和一个加密令牌（比如密码或者存取令牌）来 鉴别用户身份。认证是登录功能的基础。</p><p>Yii提供了一个认证框架，它连接了不同的组件以支持登录。欲使用这个框架， 你主要需要做以下工作：</p><ul><li>设置用户组件&nbsp;<a href=\"https://www.yiichina.com/doc/api/2.0/yii-web-user\">user</a> ;</li><li>创建一个类实现&nbsp;<a href=\"https://www.yiichina.com/doc/api/2.0/yii-web-identityinterface\">yii\\web\\IdentityInterface</a> 接口。</li></ul>', 'posts/2019/03/5c88a881edc3d', 4, 0, '官方唯一指定管理员', 11, 0, 1553491822, 1553491822);

SET FOREIGN_KEY_CHECKS = 1;
