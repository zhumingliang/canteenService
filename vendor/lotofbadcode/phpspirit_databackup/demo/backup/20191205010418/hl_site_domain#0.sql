DROP TABLE IF EXISTS `hl_site_domain`;
CREATE TABLE `hl_site_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `siteid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC;
insert into `hl_site_domain` VALUES  ( '8','www.helin.com','1' ) ;
