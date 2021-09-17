# ************************************************************
# Sequel Pro SQL dump
# Version 481
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 192.168.0.109 (MySQL 5.7.24-0ubuntu0.16.04.1)
# Database: larvue
# Generation Time: 2020-10-20 02:22:07 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_config`;

CREATE TABLE `admin_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '属性',
  `value` varchar(255) DEFAULT NULL COMMENT '属性值',
  `description` varchar(255) DEFAULT NULL COMMENT '属性说明',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `admin_config` WRITE;
/*!40000 ALTER TABLE `admin_config` DISABLE KEYS */;

INSERT INTO `admin_config` (`id`, `name`, `value`, `description`, `created_at`, `updated_at`)
VALUES
	(1,'website_keywords','zly','关键字','2017-09-05 23:40:09','2018-01-19 12:39:57'),
	(2,'company_address',NULL,'公司地址','2017-09-09 15:17:10','2017-09-09 15:17:10'),
	(3,'website_title','后台管理系统','网站标题','2017-09-09 15:17:16','2018-01-19 12:39:57'),
	(4,'company_telephone','0510-88888888','公司电话','2017-09-09 15:17:23','2018-01-19 12:39:57'),
	(5,'company_full_name','无锡智凌云物联网科技有限公司','公司全称','2017-09-09 15:17:30','2018-01-19 12:39:57'),
	(6,'website_icp','xxxxxx','ICP备案号','2017-09-09 15:17:38','2018-01-19 12:39:57'),
	(7,'system_version','0.5.1','系统版本','2017-09-09 15:17:45','2018-01-19 12:39:57'),
	(8,'company_short_name','智凌云','公司简称','2017-09-09 15:18:14','2018-01-19 12:39:57'),
	(9,'system_author','zly','系统所属','2017-09-09 15:18:21','2018-01-19 12:39:57'),
	(10,'system_author_website','http://www.wisdomyun.xin','网站地址','2017-09-09 15:18:27','2018-01-19 12:39:57');

/*!40000 ALTER TABLE `admin_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_menu`;

CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单标识',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;

INSERT INTO `admin_menu` (`id`, `parent_id`, `title`, `slug`, `sort`, `created_at`, `updated_at`)
VALUES
	(1,0,'系统设置','setting',6,'2020-09-27 14:43:39','2020-10-19 16:22:19'),
	(2,1,'用户管理','setting.user',5,'2020-09-27 14:45:37','2020-10-19 16:22:19'),
	(3,1,'角色管理','setting.role',3,'2020-09-27 14:46:45','2020-10-19 16:22:19'),
	(4,1,'环境设置','setting.option',2,'2020-09-27 15:01:02','2020-10-19 16:22:19'),
	(5,1,'页面管理','setting.menu',0,'2020-09-27 15:05:16','2020-10-19 16:22:19'),
	(6,1,'权限管理','setting.permission',1,'2020-09-27 15:14:07','2020-10-19 16:22:19'),
	(7,1,'用户编辑','setting.user:id',4,'2020-09-28 15:00:05','2020-10-19 16:22:19'),
	(8,0,'会员管理','member',29,'2020-10-09 10:30:50','2020-10-19 16:22:21');

/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_operation_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_operation_log`;

CREATE TABLE `admin_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求地址',
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方式',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户IP地址',
  `sql` text COLLATE utf8mb4_unicode_ci COMMENT '执行的sql语句',
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求内容',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_operation_log_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table admin_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permissions`;

CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限标记',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permissions_name_unique` (`name`),
  UNIQUE KEY `admin_permissions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,'超级权限','suppppper','2020-09-25 09:25:00','2020-09-25 09:25:00'),
	(2,'权限管理','permission','2020-09-25 09:25:00','2020-09-25 09:25:00'),
	(3,'菜单管理','menu','2020-09-25 09:25:00','2020-09-25 09:25:00'),
	(4,'系统配置','config','2020-09-25 10:33:56','2020-09-25 10:33:56'),
	(5,'角色管理','role','2020-09-25 10:36:29','2020-09-25 10:36:29'),
	(6,'后台用户管理','user','2020-09-27 15:36:09','2020-09-27 15:36:09');

/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_menu`;

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`)
VALUES
	(1,1),
	(1,2),
	(1,3),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(1,8);

/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_permissions`;

CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
VALUES
	(3,4,'2020-09-29 13:38:48','2020-09-29 13:38:48'),
	(3,5,'2020-09-29 13:38:48','2020-09-29 13:38:48'),
	(2,6,'2020-10-09 17:49:26','2020-10-09 17:49:26'),
	(1,1,'2020-10-14 14:01:06','2020-10-14 14:01:06'),
	(1,2,'2020-10-14 14:01:06','2020-10-14 14:01:06'),
	(1,3,'2020-10-14 14:01:06','2020-10-14 14:01:06'),
	(1,4,'2020-10-14 14:01:06','2020-10-14 14:01:06'),
	(1,5,'2020-10-14 14:01:06','2020-10-14 14:01:06'),
	(1,6,'2020-10-14 14:01:06','2020-10-14 14:01:06');

/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_users`;

CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,1,'2020-09-29 09:52:28','2020-10-15 12:51:21');

/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`),
  UNIQUE KEY `admin_roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,'超级管理员','admin','2020-09-24 11:27:49','2020-09-24 11:27:49'),
	(2,'临时账号','temporary','2020-09-24 11:27:49','2020-09-24 11:27:49'),
	(3,'运营','Operation','2020-09-28 14:03:42','2020-09-28 14:03:42');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'admin','$2y$10$prNSSNdsyv.BnOffiSdd1efNjHFoWPw3fBIQwdCM5/d0xj6KDOHQO','超级管理员','74b9698dcb0ea6c97837403c45690ad4',NULL,'2020-12-20 20:20:20','2020-10-15 12:51:21');

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table error_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `error_logs`;

CREATE TABLE `error_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `error` text COMMENT '错误信息',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` varchar(255) DEFAULT '' COMMENT '文件ID 文件的md5',
  `mime_type` varchar(20) DEFAULT NULL COMMENT '文件类型',
  `size` text COMMENT '文件大小',
  `file_name` text COMMENT '文件名称',
  `client_file_name` text COMMENT '客户上传文件名',
  `file_path` text COMMENT '文件路径',
  `disk` varchar(200) DEFAULT NULL COMMENT '存储器',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `unionid` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `superior_id` int(11) DEFAULT NULL COMMENT '销售员id',
  `nickname` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '微信昵称',
  `real_name` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '姓名',
  `avatar` text COLLATE utf8mb4_bin COMMENT '头像',
  `gender` int(11) DEFAULT NULL COMMENT '性别 1男 2女 0未知',
  `province` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '省份',
  `city` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '城市',
  `phone` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '用户手机号',
  `type` tinyint(1) DEFAULT '1' COMMENT '用户类型 1 普通用户 2 销售员 3送水员',
  `integral` int(11) DEFAULT '0' COMMENT '积分余额',
  `water_ticket` int(11) DEFAULT '0' COMMENT '水票余额',
  `deposit` decimal(9,2) DEFAULT '0.00' COMMENT '已缴纳押金',
  `company_id` int(11) DEFAULT NULL COMMENT '公司id',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
