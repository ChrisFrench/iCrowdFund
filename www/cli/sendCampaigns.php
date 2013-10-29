<?php
/**
 * @package   Joomla.Cli
 *
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

// Make sure we're being called from the command line, not a web interface
if (array_key_exists('REQUEST_METHOD', $_SERVER)) die();

/**
 * This is a CRON script which should be called from the command-line, not the
 * web. For example something like:
 * /usr/bin/php /path/to/site/cli/sendCampaigns.php
 */

// Set flag that this is a parent file.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);

// Load system defines
if (file_exists(dirname(dirname(__FILE__)) . '/defines.php'))
{
  require_once dirname(dirname(__FILE__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
  define('JPATH_BASE', dirname(dirname(__FILE__)));
  require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_LIBRARIES . '/import.php';
require_once JPATH_LIBRARIES . '/cms.php';

// Force library to be in JError legacy mode
JError::$legacy = true;

// Load the configuration
require_once JPATH_CONFIGURATION . '/configuration.php';


class SendCampaignEmails extends JApplicationCli
{
  

  function getEmailstoSend() {
    $db = JFactory::getDBO();
    $sql = "SELECT * FROM #__messagebottle_emails as tbl WHERE tbl.sent = '0' AND tbl.scope_id = '1'";
    $db->setQuery($sql);
    $list = $db->loadObjectList();
    return $list;
  }

  function getFundersofCampaigns($object_id) {
    $db = JFactory::getDBO();
    $sql = "SELECT * FROM #__favorites_items as tbl WHERE tbl.object_id =  '{$object_id}'";
    $db->setQuery($sql);
    $list = $db->loadObjectList();
    return $list;
  }
  function sendMail() {


  }

  function doSend() {

  }

  function updateEmailRecord() {

  }
  /**
   * Entry point for the script
   *
   * @return  void
   *
   * @since   2.5
   */
  public function doExecute()
  {
    // Purge all old records
    $db = JFactory::getDBO();

    $list = $this->getEmailstoSend();
    // Find all emails
    $this->out('FINDING EMAILS');
    $count = count($list);
    $this->out('FOUND: '. $count);
    foreach($list as $email) {
     
       $this->out('PARENT_OBJECT_ID: '. $email->parent_object_id);
    }

   
    $this->out('Finished');
  }
}

JApplicationCli::getInstance('SendCampaignEmails')->execute();
