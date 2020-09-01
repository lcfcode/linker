/*
 Navicat Premium Data Transfer

 Source Server         : 本地mysql-53306
 Source Server Type    : MySQL
 Source Server Version : 80018
 Source Host           : 127.0.0.1:53306
 Source Schema         : linker_two

 Target Server Type    : MySQL
 Target Server Version : 80018
 File Encoding         : 65001

 Date: 19/01/2020 17:01:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee`  (
  `num` int(11) NULL DEFAULT NULL,
  `id` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `age` int(11) NULL DEFAULT NULL,
  `sex` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `homeaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用于测试分组函数的' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee
-- ----------------------------
INSERT INTO `employee` VALUES (1, '1001', '张三', 26, '男', 'beijinghdq');
INSERT INTO `employee` VALUES (2, '1002', '李四', 24, '女', 'basle');
INSERT INTO `employee` VALUES (3, '1003', '王五', 25, '男', 'khmnm');
INSERT INTO `employee` VALUES (4, '1004', '赵六', 15, '男', 'en');

-- ----------------------------
-- Table structure for json_tab
-- ----------------------------
DROP TABLE IF EXISTS `json_tab`;
CREATE TABLE `json_tab`  (
  `id` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '主键',
  `data` json NULL COMMENT 'json数据',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'json测试表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of json_tab
-- ----------------------------
INSERT INTO `json_tab` VALUES ('1', '{\"mail\": \"jiangchengyao@gmail.com\", \"name\": \"David\", \"address\": \"Shangahai\"}');
INSERT INTO `json_tab` VALUES ('2', '{\"mail\": \"amy@gmail.com\", \"name\": \"Amy\"}');
INSERT INTO `json_tab` VALUES ('3', '{\"age\": \"10\", \"name\": \"梁朝富\"}');
INSERT INTO `json_tab` VALUES ('4', '{\"age\": \"10\", \"name\": \"梁朝富\"}');
INSERT INTO `json_tab` VALUES ('5', '{\"age\": \"24\", \"name\": \"梁朝伟\"}');
INSERT INTO `json_tab` VALUES ('6', '{\"openid\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"meet_id\": 931, \"page_size\": 1000, \"page_index\": 1}');
INSERT INTO `json_tab` VALUES ('7', '{\"code\": 0, \"data\": [{\"hb_id\": \"30940afae4e4c3aea59c2f9c2527a966\", \"money\": 2032, \"status\": 1, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:36:58\"}, {\"hb_id\": \"0a9c0682b768dfd7438e29541086709e\", \"money\": 0, \"status\": 0, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:16:00\"}], \"total_money\": 2032}');

SET FOREIGN_KEY_CHECKS = 1;
