-- -----------------------------------------------------
-- Table `#__tos_config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__tos_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `config_name` VARCHAR(255) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`config_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `#__tos_terms` (
	`terms_id` int AUTO_INCREMENT,
	`terms_title` varchar(255),
	`terms` MEDIUMTEXT,
	`scope_id` int,
	`enabled` int,
	`optional` int,
	`created_date` datetime,
	`modified_date` datetime,
	`expires_date` datetime,
	PRIMARY KEY (`terms_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `#__tos_accepts` (
	`accept_id` int NOT NULL AUTO_INCREMENT,
	`user_id` int(255) NOT NULL,
	`terms_id` int NOT NULL,
	`scope_id` int,
	`ip_address` varchar(255),
	`browser` varchar(255),
	`geoloc` varchar(255),
	`created_date` datetime,
	`modified_date` datetime,
	`expires_date` datetime,
	`enabled` int,
	PRIMARY KEY (`accept_id`)
);

-- --------------------------------------------------------
-- Table structure for table `#__wepay_scopes`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__tos_scopes` (
  `scope_id` int(11) NOT NULL AUTO_INCREMENT,
  `scope_name` varchar(255) NOT NULL COMMENT 'Plain English name for the scope',
  `scope_identifier` varchar(255) NOT NULL COMMENT 'String unique ID for the scope',
  `scope_url` varchar(255) NOT NULL COMMENT 'URL for the scope item',
  `scope_table` varchar(255) NOT NULL COMMENT 'The DB table to perform the JOIN',
  `scope_table_field` varchar(255) NOT NULL COMMENT 'The DB table field to use for the JOIN',
  `scope_table_name_field` varchar(255) NOT NULL COMMENT 'The DB table field to use for the item name',
  `scope_params` text NOT NULL COMMENT 'JSON-encoded object with any other information you want to store about the scope',
  PRIMARY KEY (`scope_id`),
  KEY `scope_identifier` (`scope_identifier`)
) 
ENGINE=MyISAM  
DEFAULT CHARSET=utf8;


insert into `#__tos_scopes` ( `scope_id`, `scope_table`, `scope_url`, `scope_identifier`, `scope_table_field`, `scope_table_name_field`, `scope_name`) values ( '1', '#__content', 'index.php?option=com_content&view=article&id=', 'com_content.article', 'id', 'title', 'Content Article')