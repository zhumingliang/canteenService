DROP TABLE IF EXISTS `hl_content_outvalue`;
CREATE TABLE `hl_content_outvalue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `content` text,
  `addtime` int(11) NOT NULL,
  `sort` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '1',
  `annual` int(11) NOT NULL,
  `electric` decimal(9,2) NOT NULL,
  `emplayee` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `siteid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_content_outvalue` VALUES  ( '1','32','2019年年度总产值','','\r\n                                    ','1574146642','0','1','900','1.60','800','2019','1' ) ;
