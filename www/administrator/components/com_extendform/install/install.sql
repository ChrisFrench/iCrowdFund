-- -----------------------------------------------------
-- Table `#__extendform_config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__extendform_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `config_name` VARCHAR(255) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`config_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `#__extendform`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__extendform` (
	`id` int NOT NULL AUTO_INCREMENT,
	`title` varchar(255),
	`form` varchar(255),
	`component` varchar(255),
	`xmlfile` varchar(255),
	`published` tinyint NOT NULL,
	PRIMARY KEY (`id`))
ENGINE=`MyISAM`
DEFAULT CHARACTER SET = utf8;