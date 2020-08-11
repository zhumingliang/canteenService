DROP TABLE IF EXISTS `hl_content_cate`;
CREATE TABLE `hl_content_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  `mid` int(11) NOT NULL COMMENT '模型ID',
  `keyword` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `list` varchar(100) DEFAULT NULL,
  `news` varchar(100) DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `menushow` tinyint(4) DEFAULT '0',
  `isleaf` tinyint(4) DEFAULT '1',
  `siteid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_content_cate` VALUES  ( '1','公司资讯','','0','1','公司资讯','公司资讯','category.php','list.php','page.php','','0','1','0','1' ), ( '2','公司要闻','Important News','1','1','公司要闻','公司要闻','category.php','list.php','news.php','','0','1','1','1' ), ( '3','生产技术','','0','1','生产技术','生产技术','category.php','list.php','news.php','','0','1','0','1' ), ( '4','生产工艺','','3','8','生产工艺','生产工艺','category.php','list.php','news.php','page_scgy.php','0','1','1','1' ), ( '12','关于鹤林','About Helin','0','8','关于鹤林','关于鹤林','category.php','list.php','news.php','page.php','99','1','0','1' ), ( '13','公司简介','About Us','12','8','公司简介','公司简介','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '14','公司荣誉','','12','1','公司荣誉','公司荣誉','category.php','list_pic.php','news.php','page.php','0','1','1','1' ), ( '15','组织架构','','12','8','组织架构','组织架构','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '16','鹤林剪影','','12','8','鹤林剪影','鹤林剪影','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '17','行业资讯','','1','1','行业资讯','行业资讯','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '18','余热发电','','3','1','余热发电','余热发电','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '20','环境保护','','3','8','环境保护','环境保护','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '21','业务产品','','0','1','业务产品','业务产品','category.php','list.php','news.php','page.php','0','1','0','1' ), ( '22','产品介绍','','21','8','产品介绍','产品介绍','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '23','产品品质','','21','8','产品品质','产品品质','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '24','重点工程展示','Key projects','21','1','重点工程展示','重点工程展示','category.php','list_pic.php','news.php','page.php','0','1','1','1' ), ( '25','销售服务','Sales Service','21','8','销售服务','销售服务','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '26','新闻资讯','','0','1','新闻资讯','新闻资讯','category.php','list.php','news.php','page.php','0','1','0','1' ), ( '27','员工风采','','26','1','员工风采','员工风采','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '28','招聘信息','','26','1','招聘信息','招聘信息','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '29','联系我们','','0','8','联系我们','联系我们','category.php','list.php','news.php','page.php','0','1','0','1' ), ( '30','联系方式','','29','8','联系方式','联系方式','category.php','list.php','news.php','page.php','0','1','1','1' ), ( '31','地理位置','','29','8','地理位置','地理位置','category.php','list.php','news.php','page_local.php','0','1','1','1' ), ( '32','年度总产值','','0','9','年度总产值','年度总产值','category.php','list.php','news.php','page.php','0','0','0','1' ), ( '38','公司文化','Corporate culture','0','8','公司文化','公司文化','category.php','list.php','news.php','page.php','0','0','1','1' ), ( '39','发展历史','Development history','0','8','发展历史','发展历史','category.php','list.php','news.php','page.php','0','0','1','1' ), ( '40','荣誉资质','Honorary qualification','0','8','荣誉资质','荣誉资质','category.php','list.php','news.php','page.php','0','0','1','1' ) ;
