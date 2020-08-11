DROP TABLE IF EXISTS `hl_cmodel_field`;
CREATE TABLE `hl_cmodel_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldtitle` varchar(100) NOT NULL,
  `fieldname` varchar(100) NOT NULL,
  `fieldval` text,
  `sqlfieldtype` varchar(100) NOT NULL,
  `formeml` varchar(100) DEFAULT NULL,
  `require` tinyint(4) NOT NULL DEFAULT '0',
  `fieldreg` varchar(255) DEFAULT NULL,
  `otherparam` varchar(255) DEFAULT NULL COMMENT '其他参数 样式等',
  `mid` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `issys` tinyint(4) NOT NULL DEFAULT '0',
  `formshow` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_cmodel_field` VALUES  ( '1','栏目','cid','','int','cate','1','','','1','999','1','0' ), ( '2','添加时间','addtime','','int','','1','','','1','0','1','0' ), ( '3','排序','sort','','int','','1','','','1','0','1','0' ), ( '4','标题','title','','varchar','text','1','','','1','99','1','1' ), ( '5','内容','content','','text','editor','0','','','1','97','1','1' ), ( '6','推荐位','position','','int','position','0','','','1','-1','1','0' ), ( '7','缩略图','thumb','','varchar','thumb','0','','','1','98','1','1' ), ( '40','栏目','cid','','int','cate','1','','','8','999','1','0' ), ( '41','添加时间','addtime','','int','','1','','','8','0','1','0' ), ( '42','排序','sort','','int','','1','','','8','0','1','0' ), ( '43','标题','title','','varchar','text','1','','','8','99','1','1' ), ( '44','内容','content','','text','editor','0','','','8','97','1','1' ), ( '48','发布时间','fbdate','','int','datetime','1','','','1','0','0','1' ), ( '49','栏目','cid','','int','cate','1','','','9','999','1','0' ), ( '50','标题','title','','varchar','text','1','','','9','99','1','1' ), ( '51','内容','content','','text','editor','0','','','9','1','1','1' ), ( '52','添加时间','addtime','','int','','1','','','9','0','1','0' ), ( '53','排序','sort','','int','','1','','','9','0','1','0' ), ( '54','状态','status','','int','','0','','','9','0','1','1' ), ( '55','推荐位','position','','int','position','0','','','9','-1','1','0' ), ( '56','缩略图','thumb','','varchar','thumb','0','','','9','2','1','1' ), ( '58','年产量(万)','annual','','int','text','1','','','9','97','0','1' ), ( '59','发电量(亿度)','electric','','numeric','text','1','','','9','96','0','1' ), ( '60','员工数','emplayee','','int','text','1','','','9','95','0','1' ), ( '61','年份','year','','int','text','1','','','9','98','0','1' ), ( '62','文章出处','original','','varchar','text','0','','','1','0','0','1' ) ;
