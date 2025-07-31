/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : noti_db

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 31/07/2025 16:47:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for push_subscribers
-- ----------------------------
DROP TABLE IF EXISTS `push_subscribers`;
CREATE TABLE `push_subscribers`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `endpoint` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `p256dh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `authKey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of push_subscribers
-- ----------------------------

-- ----------------------------
-- Table structure for refresh_tokens
-- ----------------------------
DROP TABLE IF EXISTS `refresh_tokens`;
CREATE TABLE `refresh_tokens`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `device_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT 0,
  `revoked_at` datetime NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of refresh_tokens
-- ----------------------------
INSERT INTO `refresh_tokens` VALUES (22, '2630065', 'TK688b32353cc5a0.80955118', '$argon2i$v=19$m=65536,t=4,p=1$dExSR0pxNlFVamY5VTJNaA$SO/1sSUAHvKnfYaGg4BegerwcZEvXTid/0VAfJCaMvM', '2025-07-31 16:07:01', 'Windows PC', '::1', '2025-08-07 11:07:01', 1, '2025-07-31 16:08:23', 'Logout');
INSERT INTO `refresh_tokens` VALUES (23, '2630065', 'TK688b32922a6544.27329403', '$argon2i$v=19$m=65536,t=4,p=1$VUNiaGJ5LlZiMU9jcFlpbQ$WIPebPggqNUZe2ENIJjagjPdifm4J8fTneeUwR6a+F8', '2025-07-31 16:08:34', 'Windows PC', '::1', '2025-08-07 11:08:34', 1, '2025-07-31 16:18:32', 'Logout');
INSERT INTO `refresh_tokens` VALUES (24, '2630065', 'TK688b36eb948777.51813416', '$argon2i$v=19$m=65536,t=4,p=1$S2hESjVKYmdTcG0vNy9vbg$Qghtnrrg2piG6KQbbPdHsGllzqbuzTAKvoEobtPLl0I', '2025-07-31 16:27:08', 'Windows PC', '::1', '2025-08-07 11:27:07', 1, '2025-07-31 16:33:13', 'Logout');
INSERT INTO `refresh_tokens` VALUES (25, '2630065', 'TK688b38505ff6e0.57552957', '$argon2i$v=19$m=65536,t=4,p=1$bnc1QW9PaTBvNmQ5elpGNA$Tp6/pT+AvDYNDoAXKeZtkxU6YF04SeeenvrTbS+qRoA', '2025-07-31 16:33:04', 'Windows PC', '::1', '2025-08-07 11:33:04', 0, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_login
-- ----------------------------
DROP TABLE IF EXISTS `tbl_login`;
CREATE TABLE `tbl_login`  (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `reset_expires` datetime NULL DEFAULT NULL,
  `reset_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_login
-- ----------------------------
INSERT INTO `tbl_login` VALUES (3, '2630065', 'nomad', '123456', NULL, 'ณัฐพงษ์ ธิเชื้อ', NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
