-- -----------------------------------------------------
-- Table `#__featureditems_config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__featureditems_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `config_name` VARCHAR(255) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`config_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table structure for table `#__featureditems_items`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__featureditems_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type` varchar(255) NOT NULL,
  `item_layout` varchar(255) NOT NULL,
  `item_label` varchar(255) NOT NULL,
  `item_category` varchar(255) NOT NULL,
  `item_long_title` mediumtext NOT NULL,
  `item_short_title` varchar(255) NOT NULL,
  `item_image_url` mediumtext NOT NULL,
  `item_image_local_filename` varchar(255) NOT NULL,
  `item_image_local_path` mediumtext NOT NULL,
  `item_description` text NOT NULL,
  `item_url` mediumtext NOT NULL,
  `item_url_target` tinyint(1) NOT NULL COMMENT '0=same, 1=new, 2=lightbox',
  `item_enabled` tinyint(1) NOT NULL,
  `category_id` int(11) NOT NULL,
  `fk_id` int(11) NOT NULL,
  `fk_table` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `publish_up` date NOT NULL,
  `publish_down` date NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE = MyISAM;

-- --------------------------------------------------------
--
-- Table structure for table `#__featureditems_categories`
--
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__featureditems_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL DEFAULT '',
  `category_alias` varchar(255) NOT NULL DEFAULT '',
  `category_description` text,
  `category_thumb_image` varchar(255) DEFAULT NULL,
  `category_full_image` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL COMMENT 'GMT',
  `modified_date` datetime NOT NULL COMMENT 'GMT',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `category_enabled` tinyint(1) NOT NULL,
  `isroot` tinyint(1) NOT NULL,
  `category_params` text,
  `category_layout` varchar(255) DEFAULT '' COMMENT 'The layout file for this category',
  `category_class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `idx_category_name` (`category_name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;