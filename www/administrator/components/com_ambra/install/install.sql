CREATE TABLE IF NOT EXISTS `#__ambra_config` (
  `config_id` int(11) NOT NULL auto_increment,
  `config_name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`config_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__ambra_roles` (
  `role_id` int(11) NOT NULL auto_increment,
  `role_name` varchar(255) NOT NULL,
  `role_description` text NOT NULL,
  `site_option` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `isroot` tinyint(1) NOT NULL,
  `role_enabled` tinyint(1) NOT NULL,
  `role_params` text NOT NULL,
  PRIMARY KEY  (`role_id`),
  KEY `parent_id` (`parent_id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_profiles` (
  `profile_id` int(11) NOT NULL auto_increment,
  `profile_name` varchar(255) NOT NULL,
  `profile_description` text NOT NULL,
  `profile_enabled` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `profile_params` text NOT NULL,
  `profile_max_points_per_day` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY  (`profile_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_enabled` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `category_params` text NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_fields` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_params` varchar(255) NOT NULL,
  `image_enabled` tinyint(1) NOT NULL,
  `field_description` text NOT NULL,
  `description_params` varchar(255) NOT NULL,
  `description_enabled` tinyint(1) NOT NULL,
  `field_enabled` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `access` tinyint(1) NOT NULL,
  `is_core` tinyint(1) NOT NULL,
  `field_params` text NOT NULL,
  `class` varchar(255) NOT NULL,
  `db_fieldname` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `maxlength` int(11) NOT NULL,
  `cols` int(11) NOT NULL,
  `rows` int(11) NOT NULL,
  `options` text NOT NULL,
  `default` text NOT NULL,
  `readonly` tinyint(1) NOT NULL,
  `integer` tinyint(1) NOT NULL,
  `list_displayed` tinyint(1) NOT NULL,
  `profile_displayed` tinyint(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY  (`field_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_roles2users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_roles2actions` (
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  `value` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `action_id` (`action_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_categories2profiles` (
  `category_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  KEY `category_id` (`category_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_fields2categories` (
  `field_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  KEY `field_id` (`field_id`),
  KEY `category_id` (`category_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_actions` (
  `action_id` int(11) NOT NULL auto_increment,
  `act_id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `view` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  PRIMARY KEY  (`action_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_acts` (
  `act_id` int(11) NOT NULL auto_increment,
  `act_name` varchar(255) NOT NULL,
  `act_params` text NOT NULL,
  PRIMARY KEY  (`act_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_userdata` (
  `userdata_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `is_manual_approval` int(1) NOT NULL,
  `points_total` int(11) NOT NULL,
  `points_current` int(11) NOT NULL,
  `points_maximum` varchar(11) NOT NULL DEFAULT '-1',
  `points_maximum_per_day` varchar(11) NOT NULL DEFAULT '',
  `profile_linkedin` varchar(255) NOT NULL DEFAULT '',
  `profile_facebook` varchar(255) NOT NULL DEFAULT '',
  `profile_twitter` varchar(255) NOT NULL DEFAULT '',
  `profile_youtube` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`userdata_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_userrelations` (
  `userrelation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_from` INT(11) NOT NULL DEFAULT '0' ,
  `user_id_to` INT(11) NOT NULL DEFAULT '0' ,
  `relation_type` VARCHAR(64) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`userrelation_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_pointhistory` (
  `pointhistory_id` int(11) NOT NULL auto_increment,
  `expired` int(11) NOT NULL,
  `pointrule_id` int(11) NOT NULL,
  `pointcoupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL COMMENT 'Can be negative to support converting points to something else',
  `points_updated` tinyint(1) NOT NULL COMMENT 'Did the user point total get recalculated?',
  `pointhistory_name` varchar(255) NOT NULL,
  `pointhistory_description` text NOT NULL,
  `pointhistory_enabled` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_by` int(11) NOT NULL ,
  `enabled_date` datetime NOT NULL COMMENT 'GMT Only',
  `enabled_by` int(11) NOT NULL ,
  `expire_date` datetime NOT NULL COMMENT 'GMT Only',
  PRIMARY KEY  (`pointhistory_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_pointcoupons` (
  `pointcoupon_id` int(11) NOT NULL auto_increment,
  `pointcoupon_value` varchar(11) NOT NULL,
  `pointtype_id` int(11) NOT NULL DEFAULT '0' COMMENT '0=Points, 1=Percentage',
  `pointcoupon_code` varchar(255) NOT NULL,
  `pointcoupon_name` varchar(255) NOT NULL,
  `pointcoupon_description` text NOT NULL,
  `pointcoupon_params` text NOT NULL,
  `pointcoupon_enabled` tinyint(1) NOT NULL,
  `pointcoupon_uses` int(11) NOT NULL,
  `pointcoupon_uses_max` int(11) NOT NULL DEFAULT '-1' COMMENT '-1 = Infinite',
  `pointcoupon_uses_per_user` int(11) NOT NULL DEFAULT '1' COMMENT '-1 = Infinite',
  `pointcoupon_uses_per_user_per_day` int(11) NOT NULL DEFAULT '1' COMMENT '-1 = Infinite',
  `created_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_by` int(11) NOT NULL ,
  `expire_date` datetime NOT NULL COMMENT 'GMT Only',
  `access` int(11) NOT NULL ,
  `profile_id` int(11) NOT NULL ,
  PRIMARY KEY  (`pointcoupon_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ambra_pointrules` (
  `pointrule_id` int(11) NOT NULL auto_increment,
  `pointrule_value` varchar(11) NOT NULL ,
  `pointtype_id` int(11) NOT NULL DEFAULT '0' COMMENT '0=Points, 1=Percentage',
  `pointrule_scope` varchar(255) NOT NULL COMMENT 'Generally the com_whatever of a component',
  `pointrule_event` varchar(255) NOT NULL COMMENT 'Generally a plugin event name',
  `pointrule_name` varchar(255) NOT NULL,
  `pointrule_description` text NOT NULL,
  `pointrule_params` text NOT NULL,
  `pointrule_enabled` tinyint(1) NOT NULL,
  `pointrule_auto_approve` tinyint(1) NOT NULL,
  `pointrule_uses` int(11) NOT NULL,
  `pointrule_uses_max` int(11) NOT NULL DEFAULT '-1' COMMENT '-1 = Infinite',
  `pointrule_uses_per_user` int(11) NOT NULL DEFAULT '-1' COMMENT '-1 = Infinite',
  `pointrule_uses_per_user_per_day` int(11) NOT NULL DEFAULT '1' COMMENT '-1 = Infinite',
  `created_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_date` datetime NOT NULL COMMENT 'GMT Only',
  `modified_by` int(11) NOT NULL ,
  `expire_date` datetime NOT NULL COMMENT 'GMT Only',
  `access` int(11) NOT NULL ,
  `profile_id` int(11) NOT NULL ,
  PRIMARY KEY  (`pointrule_id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;