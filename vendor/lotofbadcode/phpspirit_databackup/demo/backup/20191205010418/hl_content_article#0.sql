DROP TABLE IF EXISTS `hl_content_article`;
CREATE TABLE `hl_content_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `content` text,
  `addtime` int(11) NOT NULL,
  `sort` int(11) DEFAULT '0',
  `siteid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `fbdate` int(11) NOT NULL,
  `original` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into `hl_content_article` VALUES  ( '40','2','水泥窑协同处置一般固废技术改造项目环评公示','storage/content/20191121\\36b0f7fb97f2752e96db4ec8eb8e8e1d.jpg','                                        <p><br /></p><blockquote><img src=\"/storage/content/20191121%5C5e5094596d29ab534ef650c3b088a889.jpg\" alt=\"5e5094596d29ab534ef650c3b088a889.jpg\" /></blockquote><p><img src=\"/storage/content/20191121/01429c7b4da28fd6344108011a18062a.jpg\" alt=\"01429c7b4da28fd6344108011a18062a.jpg\" /></p>                                    ','1574308023','0','1','1','1574265600','鹤林集团' ), ( '41','14','产品认证书','storage/content/20191121/43eb9c277ee60f7bc3225debabcf2ada.jpg','<p><br /><img src=\"/storage/content/20191121/8db5b53db2c96434e4adebea19336393.jpg\" alt=\"8db5b53db2c96434e4adebea19336393.jpg\" /></p>','1574314100','0','1','1','1574265600','鹤林水泥' ), ( '42','24','南京地铁','storage/content/20191121/bb0eaebe1a225b80f7f68ec681ea7bbd.jpg','\r\n                                    ','1574321523','0','1','1','1574265600','鹤林水泥' ), ( '43','17','河北京兰、燕东等水泥企业因环保问题受罚','','<p>\r\n</p><p>河北省环境保护厅于5月23日透露，该部门近日对2017年底发现的部分环境违法行为集中进行了行政处罚。</p><p>处罚企业共84家，涉水泥、钢铁、化工等行业。其中，水泥行业公示了河北京兰水泥、唐山燕东水泥、滦县磐石水泥的行政处罚决定书，罚款金额分别为20万、30万、100万。</p><p><span style=\"font-size:22px;\"><span style=\"color:rgb(51,51,51);font-family:SimSun, Tahoma, Helvetica, Arial, sans-serif;background-color:rgb(247,247,247);\"></span></span>\r\n\r\n</p>','1574324948','0','1','1','1574265600','鹤林水泥' ) ;
