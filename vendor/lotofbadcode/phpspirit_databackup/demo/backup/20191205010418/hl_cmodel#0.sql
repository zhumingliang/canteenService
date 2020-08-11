DROP TABLE IF EXISTS `hl_cmodel`;
CREATE TABLE `hl_cmodel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '模型名',
  `tablename` varchar(50) NOT NULL COMMENT '表名',
  `type` tinyint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_cmodel` VALUES  ( '1','普通资讯','content_article','0' ), ( '8','通用单页面','content_page','1' ), ( '9','年度总产值','content_outvalue','0' ) ;
