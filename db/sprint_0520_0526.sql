--
--2016/05/23
--Ares
CREATE TABLE `standard_case` (
`id`  int(11) NOT NULL ,
`country_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=36
ROW_FORMAT=COMPACT
;

INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('1', '全部')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('2', '東亞')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('3', '東南亞')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('4', '中亞')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('5', '南亞')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('6', '非洲')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('7', '南美洲')
INSERT INTO `standard_case` (`id`, `country_name`) VALUES ('8', '歐洲')

--
--2016/05/23
--Ares
CREATE TABLE `standard_case_page` (
`id`  int(11) NOT NULL ,
`create_datetime`  datetime NOT NULL ,
`title`  varchar(45) NOT NULL ,
`pdf_file_name`  varchar(50) NOT NULL ,
`classification`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=36
ROW_FORMAT=COMPACT
;