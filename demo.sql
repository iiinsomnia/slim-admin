# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.16)
# Database: slim
# Generation Time: 2017-06-18 11:55:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table slim_assign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `slim_assign`;

CREATE TABLE `slim_assign` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `route` varchar(50) NOT NULL DEFAULT '' COMMENT '路由',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COMMENT='角色路由分配表';

LOCK TABLES `slim_assign` WRITE;
/*!40000 ALTER TABLE `slim_assign` DISABLE KEYS */;

INSERT INTO `slim_assign` (`id`, `role_id`, `route`, `created_at`, `updated_at`)
VALUES
	(1,1,'register','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(2,1,'captcha','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(3,1,'login','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(4,1,'logout','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(5,1,'home','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(6,1,'profile','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(7,1,'password','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(8,1,'menu.index','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(9,1,'menu.add','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(10,1,'menu.submenu','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(11,1,'menu.edit','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(12,1,'menu.delete','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(13,1,'auth.index','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(14,1,'auth.add','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(15,1,'auth.edit','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(16,1,'auth.delete','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(17,1,'role.index','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(18,1,'role.add','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(19,1,'role.edit','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(20,1,'role.delete','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(21,1,'role.assign','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(22,1,'user.index','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(23,1,'user.add','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(24,1,'user.edit','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(25,1,'password.reset','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(26,1,'user.delete','2017-06-18 19:41:21','2017-06-18 19:41:21'),
	(27,2,'register','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(28,2,'captcha','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(29,2,'login','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(30,2,'logout','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(31,2,'home','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(32,2,'profile','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(33,2,'password','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(34,2,'menu.index','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(35,2,'menu.add','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(36,2,'menu.submenu','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(37,2,'menu.edit','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(38,2,'auth.index','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(39,2,'auth.add','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(40,2,'auth.edit','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(41,2,'role.index','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(42,2,'role.add','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(43,2,'role.edit','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(44,2,'user.index','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(45,2,'user.add','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(46,2,'user.edit','2017-06-18 19:42:20','2017-06-18 19:42:20'),
	(47,3,'register','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(48,3,'captcha','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(49,3,'login','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(50,3,'logout','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(51,3,'home','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(52,3,'profile','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(53,3,'password','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(54,3,'menu.index','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(55,3,'auth.index','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(56,3,'role.index','2017-06-18 19:42:47','2017-06-18 19:42:47'),
	(57,3,'user.index','2017-06-18 19:42:47','2017-06-18 19:42:47');

/*!40000 ALTER TABLE `slim_assign` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table slim_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `slim_menu`;

CREATE TABLE `slim_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标 [Font Awesome]',
  `color` varchar(50) NOT NULL DEFAULT '' COMMENT '颜色',
  `route` varchar(50) NOT NULL DEFAULT '' COMMENT '路由',
  `args` varchar(255) NOT NULL DEFAULT '' COMMENT '路由参数',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'PID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='菜单表';

LOCK TABLES `slim_menu` WRITE;
/*!40000 ALTER TABLE `slim_menu` DISABLE KEYS */;

INSERT INTO `slim_menu` (`id`, `name`, `icon`, `color`, `route`, `args`, `pid`, `created_at`, `updated_at`)
VALUES
	(1,'RBAC','leaf','orange','','',0,'2017-06-16 16:28:42','2017-06-17 10:35:47'),
	(2,'菜单管理','','','menu.index','',1,'2017-06-16 16:52:32','2017-06-17 09:13:44'),
	(3,'角色管理','','','role.index','',1,'2017-06-16 16:56:50','2017-06-17 09:13:58'),
	(4,'用户管理','','','user.index','',1,'2017-06-16 16:57:36','2017-06-17 09:14:11');

/*!40000 ALTER TABLE `slim_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table slim_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `slim_role`;

CREATE TABLE `slim_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

LOCK TABLES `slim_role` WRITE;
/*!40000 ALTER TABLE `slim_role` DISABLE KEYS */;

INSERT INTO `slim_role` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'超级管理员','2017-06-11 17:54:09','2017-06-14 11:17:24'),
	(2,'高级管理员','2017-06-11 17:54:27','2017-06-14 11:17:26'),
	(3,'普通管理员','2017-06-13 14:33:03','2017-06-14 11:17:27');

/*!40000 ALTER TABLE `slim_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table slim_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `slim_user`;

CREATE TABLE `slim_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(20) NOT NULL DEFAULT '' COMMENT '加密盐',
  `role` int(11) NOT NULL COMMENT '角色',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '最近登录IP',
  `last_login_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '最近登录时间',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_username` (`username`),
  UNIQUE KEY `index_email` (`email`),
  UNIQUE KEY `index_phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

LOCK TABLES `slim_user` WRITE;
/*!40000 ALTER TABLE `slim_user` DISABLE KEYS */;

INSERT INTO `slim_user` (`id`, `username`, `phone`, `email`, `password`, `salt`, `role`, `last_login_ip`, `last_login_time`, `created_at`, `updated_at`)
VALUES
	(1,'admin','13912999999','admin@qq.com','7734ab9d47e56189d2bbb384be7483b1','dQP!6Bn#^y79Aw3t',1,'127.0.0.1','2017-06-17 18:02:10','2017-06-04 21:03:19','2017-06-17 18:02:10'),
	(2,'slim','13913999999','slim@qq.com','62192e6af1d05ab3945b16161194ba63','I2NEi!tyi7#0!FVa',2,'127.0.0.1','2017-06-17 11:33:29','2017-06-09 09:22:45','2017-06-17 11:33:29'),
	(3,'demo','13914999999','demo@qq.com','027a94619ce748fac471a905af271894','QAfY0TJDhHHmm%8R',3,'127.0.0.1','2017-06-17 10:40:10','2017-06-13 15:14:49','2017-06-17 10:40:10');

/*!40000 ALTER TABLE `slim_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
