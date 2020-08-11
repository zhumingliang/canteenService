DROP TABLE IF EXISTS `hl_adlist`;
CREATE TABLE `hl_adlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `siteid` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_adlist` VALUES  ( '1','2','宣传1','storage/cd/20191205/518545ad5d8f5f92d22f71a420d3c29e.jpg','','','1' ) ;
