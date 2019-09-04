-- --------------------------------------------------------
-- 主机:                           10.0.0.8
-- 服务器版本:                        5.7.18-20170830-log - 20170531
-- 服务器操作系统:                      Linux
-- HeidiSQL 版本:                  9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 bt.host 结构
CREATE TABLE IF NOT EXISTS `host` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `ftppass` varchar(50) DEFAULT NULL,
  `sqlpass` varchar(50) DEFAULT NULL,
  `dir` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT 'all',
  `opentime` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT '1',
  `子目录` varchar(50) DEFAULT '0',
  `域名数量` varchar(50) DEFAULT '1',
  `网站空间大小` varchar(50) DEFAULT '100',
  `链接数` varchar(50) DEFAULT '0',
  `数据库空间大小` varchar(50) DEFAULT '100',
  `子数据库` varchar(50) NOT NULL DEFAULT '0',
  `带宽限制` varchar(50) DEFAULT '0',
  `流量限制` varchar(50) DEFAULT '0',
  `ftp链接数` varchar(50) DEFAULT '0',
  `ftp上下传速度` varchar(50) DEFAULT '0',
  `快速登陆key` varchar(50) DEFAULT NULL,
  `数据库id` varchar(50) DEFAULT NULL,
  `ftpid` varchar(50) DEFAULT NULL,
  `siteid` varchar(50) DEFAULT NULL,
  `是否到期` varchar(50) DEFAULT '0',
  `是否被暂停` varchar(50) DEFAULT '0',
  `addtime` varchar(50) DEFAULT '0',
  `path` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- 数据导出被取消选择。
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
