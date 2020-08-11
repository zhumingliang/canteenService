DROP TABLE IF EXISTS `hl_manage_menu`;
CREATE TABLE `hl_manage_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '权限名',
  `shorttitle` varchar(100) DEFAULT NULL COMMENT '短名称',
  `path` varchar(100) DEFAULT NULL COMMENT '路径',
  `pid` int(11) NOT NULL COMMENT '父类ID',
  `deep` int(11) NOT NULL DEFAULT '1' COMMENT '等级',
  `class` varchar(255) DEFAULT NULL COMMENT '菜单样式',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否菜单显示',
  `sort` int(11) NOT NULL DEFAULT '0',
  `siteid` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_manage_menu` VALUES  ( '1','内容管理','内容管理','','0','1','','1','0','-1','fa  fa-file-word-o' ), ( '2','全局配置','全局配置','','0','1','','1','0','0','fa fa-cogs' ), ( '3','栏目管理','栏目管理','/manage/cate/cates','0','1','','1','0','-1','fa  fa-navicon' ), ( '5','站点管理','站点管理','/manage/site_config/sites','2','2','','1','0','0','fa fa-globe' ), ( '6','模型管理','模型管理','/manage/model_manage/models','2','2','','1','0','0','fa fa-tasks' ), ( '7','添加栏目','添加栏目','/manage/content/addcate','3','2','','0','0','-1','' ), ( '8','添加内容','添加内容','/manage/content/addcontent','1','2','','0','0','-1','' ), ( '9','推荐位','推荐位','/manage/position/positions','0','1','','1','0','-1','fa fa-flag' ), ( '10','内容回收站','内容回收站','','0','1','','1','-2','-1','fa fa-trash' ), ( '11','广告管理','广告管理','/manage/advert/adtype','0','1','','1','-1','-1','fa fa-code-fork' ) ;
