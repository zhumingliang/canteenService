DROP TABLE IF EXISTS `hl_site`;
CREATE TABLE `hl_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `template` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `tel` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `copyright` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `beianhao` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC;
insert into `hl_site` VALUES  ( '1','鹤林水泥','helin','鹤林水泥','鹤林水泥','江苏省镇江市丹徒区高资镇 ','0511-85756999 0511-85757049','0511-85757095 0511-85757799','','Copyright © 2013 www.helin.com All rights reserved. 江苏鹤林水泥 版权所有','苏ICP备13063158号-1','1' ), ( '2','船港物流','logic','船港物流','船港物流','','','','','','','1' ) ;
