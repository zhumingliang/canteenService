DROP TABLE IF EXISTS `hl_adtype`;
CREATE TABLE `hl_adtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `siteid` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_adtype` VALUES  ( '2','幻灯片','1' ) ;
