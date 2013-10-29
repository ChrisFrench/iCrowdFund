-- -----------------------------------------------------
-- Table `#__messagebottle_emails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__messagebottle_emails` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `replyto` varchar(255) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `bcc` text NOT NULL,
  `cc` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `scope_id` int(11) NOT NULL DEFAULT '0',
  `template_id` int(11) NOT NULL DEFAULT '0',
  `parent_object_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL DEFAULT '0',
  `sent` tinyint(4) NOT NULL,
  `senddate` datetime NOT NULL,
  `sentdate` datetime NOT NULL,
  `datecreated` datetime NOT NULL,
  `datemodified` datetime NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `sendmethod` int(11) NOT NULL,
  `ishtml` tinyint(4) NOT NULL,
  `option` varchar(255) NOT NULL,
  `view` varchar(255) NOT NULL,
  `hasattachments` tinyint(4) NOT NULL,
	PRIMARY KEY (`email_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- --------------------------------------------------------
-- Table structure for table `#__messagebottle_scopes`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__messagebottle_scopes` (
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

-- --------------------------------------------------------
-- Table structure for table `#__messagebottle_events`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__messagebottle_events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `scope_id` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `datemodified` datetime NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `processed` tinyint(4) NOT NULL,
  `object_id` int(11) NOT NULL,
  `parent_object_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`)
) 
ENGINE=MyISAM  
DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for table `#__messagebottle_attachments`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__messagebottle_attachments` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `datecreated` datetime NOT NULL,
  `datemodified` datetime NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`attachment_id`)
) 
ENGINE=MyISAM  
DEFAULT CHARSET=utf8;

