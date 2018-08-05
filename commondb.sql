/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50714
 Source Host           : localhost:3306
 Source Schema         : commondb

 Target Server Type    : MySQL
 Target Server Version : 50714
 File Encoding         : 65001

 Date: 26/07/2018 16:32:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cm_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin_group`;
CREATE TABLE `cm_admin_group`  (
  `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `group_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '小组名称',
  `visible` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效（1：有效；0：删除）',
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台用户分组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_admin_group
-- ----------------------------
INSERT INTO `cm_admin_group` VALUES (1, '管理组', 1);

-- ----------------------------
-- Table structure for cm_admin_group_menu
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin_group_menu`;
CREATE TABLE `cm_admin_group_menu`  (
  `gm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '小组ID',
  `item_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID',
  `visible` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效（1：有效；0：删除）',
  PRIMARY KEY (`gm_id`) USING BTREE,
  UNIQUE INDEX `group_id_2`(`group_id`, `item_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `item_id`(`item_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台用户分组权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_admin_group_menu
-- ----------------------------
INSERT INTO `cm_admin_group_menu` VALUES (1, 1, 1, 1);
INSERT INTO `cm_admin_group_menu` VALUES (2, 1, 2, 1);
INSERT INTO `cm_admin_group_menu` VALUES (3, 1, 3, 1);

-- ----------------------------
-- Table structure for cm_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin_menu`;
CREATE TABLE `cm_admin_menu`  (
  `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `menu_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fa-cubes' COMMENT '图标编号',
  `sortid` int(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `visible` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效（1：有效；0：删除）',
  PRIMARY KEY (`menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台主菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_admin_menu
-- ----------------------------
INSERT INTO `cm_admin_menu` VALUES (1, '系统管理', 'fa-cubes', 10, 1);

-- ----------------------------
-- Table structure for cm_admin_menu_item
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin_menu_item`;
CREATE TABLE `cm_admin_menu_item`  (
  `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '主菜单ID',
  `item_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '&#xe63c;' COMMENT '图标编号',
  `purview_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限码',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'URL地址',
  `sortid` int(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `visible` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效（1：有效；0：删除）',
  PRIMARY KEY (`item_id`) USING BTREE,
  INDEX `menu_id`(`menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台子菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_admin_menu_item
-- ----------------------------
INSERT INTO `cm_admin_menu_item` VALUES (1, 1, '菜单管理', '&#xe63c;', '', '/menu/lists', 1, 1);
INSERT INTO `cm_admin_menu_item` VALUES (2, 1, '分组管理', '&#xe63c;', '', '/group/index', 2, 1);
INSERT INTO `cm_admin_menu_item` VALUES (3, 1, '成员管理', '&#xe63c;', '', '/user/index', 3, 1);

-- ----------------------------
-- Table structure for cm_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin_user`;
CREATE TABLE `cm_admin_user`  (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '小组ID',
  `openid` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信openid',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机',
  `visible` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效（1：有效；0：删除）',
  `logintime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id`, `group_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_admin_user
-- ----------------------------
INSERT INTO `cm_admin_user` VALUES (1, 1, '', 'admin', 'bdf9f0864d4840bd62648ee644029b4e', '管理员', '13428705844', 1, 1532590541);

-- ----------------------------
-- Table structure for cm_migrations
-- ----------------------------
DROP TABLE IF EXISTS `cm_migrations`;
CREATE TABLE `cm_migrations`  (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `start_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cm_migrations
-- ----------------------------
INSERT INTO `cm_migrations` VALUES (20180725065557, 'AdminUsers', '2018-07-25 16:41:19', '2018-07-25 16:41:20', 0);
INSERT INTO `cm_migrations` VALUES (20180725085852, 'AdminGroup', '2018-07-25 17:13:22', '2018-07-25 17:13:22', 0);
INSERT INTO `cm_migrations` VALUES (20180725091528, 'AdminGroupMenu', '2018-07-25 17:44:41', '2018-07-25 17:44:42', 0);
INSERT INTO `cm_migrations` VALUES (20180725094615, 'AdminMenu', '2018-07-25 18:00:28', '2018-07-25 18:00:28', 0);
INSERT INTO `cm_migrations` VALUES (20180725095248, 'AdminMenuItem', '2018-07-25 18:00:28', '2018-07-25 18:00:28', 0);

SET FOREIGN_KEY_CHECKS = 1;
