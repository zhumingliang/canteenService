DROP TABLE IF EXISTS `hl_admin`;
CREATE TABLE `hl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `pwd` varchar(100) DEFAULT NULL COMMENT '密码',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `sex` varchar(10) NOT NULL COMMENT '性别',
  `face` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='职员表';
insert into `hl_admin` VALUES  ( '1','admin','$2y$10$Qle1zC0LDykA4XdY8qwAr.KldRxN5sZSd6JIjEbG150la54Ttgjr2','admin','','','1' ) ;
