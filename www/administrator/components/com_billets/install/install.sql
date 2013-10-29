CREATE TABLE IF NOT EXISTS `#__billets_comments` (
  `id` int(11) NOT NULL auto_increment,
  `ticketid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__billets_labels` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__billets_u2t` (
  `userid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__billets_t2a` (
  `id` int(11) NOT NULL auto_increment,
  `ticketid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `articleid` (`articleid`),
  KEY `ticketid` (`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__billets_ticketstates` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `parentid` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__billets_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parentid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created_datetime` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `isroot` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_config` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_f2c` (
  `fieldid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY  (`fieldid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_fields` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `typeid` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_params` varchar(255) NOT NULL,
  `image_published` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  `description_params` varchar(255) NOT NULL,
  `description_published` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  `access` tinyint(1) NOT NULL,
  `iscore` tinyint(1) NOT NULL,
  `params` text NOT NULL,
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
  `listdisplayed` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_fileblobs` (
  `fileid` int(11) NOT NULL,
  `fileblob` longblob NOT NULL,
  PRIMARY KEY  (`fileid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__billets_files` (
  `id` int(11) NOT NULL auto_increment,
  `physicalname` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `fileextension` varchar(255) NOT NULL,
  `filesize` varchar(255) NOT NULL,
  `fileisblob` tinyint(1) NOT NULL,
  `datetime` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_frequents` (
  `id` int(11) NOT NULL auto_increment,
  `parentid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,
  `created_datetime` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_messages` (
  `id` int(11) NOT NULL auto_increment,
  `ticketid` int(11) NOT NULL,
  `userid_from` int(11) NOT NULL,
  `username_from` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `priority` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__billets_ticketdata` (
  `ticketdata_id` int(11) NOT NULL auto_increment,
  `ticketid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ticketdata_id`),
  UNIQUE KEY `ticketid` (`ticketid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__billets_tickets` (
  `id` int(11) NOT NULL auto_increment,
  `sender_email` varchar(255) NOT NULL,
  `sender_userid` int(11) NOT NULL,
  `sender_read_datetime` datetime NOT NULL,
  `sender_flag_read` tinyint(1) NOT NULL,
  `type` varchar(255) NOT NULL,
  `entrytype` tinyint(1) NOT NULL,
  `entryid` int(11) NOT NULL,
  `target_datetime` datetime NOT NULL,
  `manager_userid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `feedback_by` int(11) NOT NULL,
  `feedback_datetime` datetime NOT NULL,
  `feedback_rating` varchar(11) NOT NULL,
  `closed_by` int(11) NOT NULL,
  `closed_datetime` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `last_modified_by` int(11) NOT NULL,
  `last_modified_datetime` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `followup` tinyint(1) NOT NULL,
  `firstresponse_by` int(11) NOT NULL,
  `firstresponse_datetime` datetime NOT NULL,
  `categoryid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `hours_spent` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL,  
  `ticket_params` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_u2c` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `emails` tinyint(1) NOT NULL,
  PRIMARY KEY  (`userid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__billets_userdata` (
  `userdata_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `limit_tickets` tinyint(1) NOT NULL COMMENT 'Can this user only open a limited number of tickets?',
  `limit_tickets_exclusion` tinyint(1) NOT NULL COMMENT 'Is this user excluded from the global ticket limiting?',
  `ticket_max` int(11) NOT NULL COMMENT 'Max number of tickets user may open',
  `ticket_count` int(11) NOT NULL COMMENT 'Number of tickets the user has opened',
  `limit_hours` tinyint(1) NOT NULL COMMENT 'Can this user only open a limited number of hours?',
  `hour_max` int(11) NOT NULL COMMENT 'Max number of hours of support user has',
  `hour_count` int(11) NOT NULL COMMENT 'Number of hours of support the user has',
  `limit_hours_exclusion` tinyint(1) NOT NULL COMMENT 'Is this user excluded from the global hour limiting?',
  PRIMARY KEY  (`userdata_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__billets_logs` (
  `log_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(255) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `value_from` text NOT NULL,
  `value_to` text NOT NULL,
  `datetime` datetime NOT NULL, 
  `log_description` text NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;