DROP TABLE IF EXISTS `hl_content_position`;
CREATE TABLE `hl_content_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `siteid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_content_position` VALUES  ( '1','热门资讯','1' ), ( '2','推荐','1' ) ;
