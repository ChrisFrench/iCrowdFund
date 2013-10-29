<?php
/**
 * @version 1.5
 * @package Tienda
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


/** Import library dependencies */
jimport('joomla.utilities.string');

class MessagebottleSenderBase extends DSCPlugin 
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	protected $_element = '';

	var $_log_file = '';
  var $email = null;
  var $report = array();
	/**
	 * Make the standard Tienda Tables avaiable in the plugin
	 */
	protected function includeMessagebottleTables() {
		// Include Tienda Tables Classes
		JTable::addIncludePath(JPATH_ADMINISTRATOR .  '/components/com_messagebottle/tables');
	}

	protected function includeMessagebottleModel($name) {
		if (strtolower($name) != 'base')
			Messagebottle::load('MessagebottleModel' . ucfirst(strtolower($name)), 'models.' . strtolower($name));
		else
			Messagebottle::load('MessagebottleModelBase', 'models.base');
	}
  /**
  * This Method is to get the emails for this sending type.
  * @todo make tables have sendMethod data, and  make this function support it with config option for default
  * @example $this->getEmails();
  * @since 0.1
  * @return ObjectList
  */

	protected function getEmails() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__messagebottle_emails AS tbl');
        $query->where('tbl.sent = '.$db->Quote(0));
        
        $where = array();
        $where[] = 'LOWER(tbl.sendmethod) = '. $db->Quote(strtolower($this->sendmethod)) ;
        if($this->defaultsender) {
        $where[] = 'LOWER(tbl.sendmethod) = '. $db->Quote( 'default' );
        $where[] = "LOWER(tbl.sendmethod) = ''";
        $where[] = "LOWER(tbl.sendmethod) = NULL";
        }
        $query->where('('.implode(' OR ', $where).')');
                

        $query->where('tbl.senddate <= NOW()');   
       
        $db->setQuery($query);
        
        $items = $db->loadObjectList();
        return $items;
  }


/**
  * This Method prepares the email for sending, since data in our tables is often going to be different depending on  what tried to send it, we prepare the email getting additional 
  * values from user tables, to fill in blank records, in this case it als sets the message variable $this->message that is used in doSend()
  * @todo 
  * @example $this->prepareEmail($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return Object
  */
function prepareEmail($email) {
    $this->email = $email;
    $this->getSender();
    $this->getReceiver();
    
/*
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `hasattachments` tinyint(4) NOT NULL,
  */


}


/**
  * This Method gets the Sender Information from Juser, if the sender infor is blank it should return the default options, By allowing  the Sender ID we can  store the ID of a user in the user table, than on email send we can check the users table if they have a new email, or name.
  * @todo 
  * @example $this->getSender($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return Object
  * @uses @this->finduserFromEmail 
  */
function getSender(){
    // the most common situation is a system email send an email with blank user info, so lets get it from the config
    if(empty($this->email->sender_id) && empty($this->email->sender_email) ) {
      $this->email->sender_name = Messagebottle::getInstance()->get('default_name', 'Website Name');
      $this->email->sender_email = Messagebottle::getInstance()->get('default_email', 'default@site.com');
       return true;
    }

    //if we have a user ID lets get the updated information from #_users
    if($this->email->sender_id) {
      $sender = JFactory::getUser($this->email->sender_id);
      if($sender->id === $this->email->sender_id) {
        $this->email->sender_name = $sender->name;
        $this->email->sender_email = $sender->email;
         return true;
      }
    }
    //if sender is is empty, and the email is set lets try to get the user object from the email
    if(empty($this->email->sender_id) && $this->email->sender_email ) {
       $sender = $this->findUserFromEmail($this->email->sender_email); 
      if($sender->id){
        $this->email->sender_id = $sender->id;
         $this->email->sender_name = $sender->name;
         $this->email->sender_email = $sender->email;
         return true;
      }
    }
    return false;
}


/**
  * tries to find a user row by the email address
  * @todo 
  * @example $this->getSender($row);
  * @param Object is a Row from the table #__users
  * @since 0.1
  * @return Object
  * @used-by $this->getSender ,$this->getSender getReceiver
  */

function findUserFromEmail($email){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__users AS tbl');
        $query->where('tbl.email = '.$db->Quote($email));
        $db->setQuery($query);
        $user = $db->loadObject();
        return $user;
}



/**
  * This Method gets the receiver Information from Juser, if the sender infor is blank it should return the default options, By allowing  the Sender ID we can  store the ID of a user in the user table, than on email send we can check the users table if they have a new email, or name.
  * @todo 
  * @example $this->getReceiver($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return Object
  * @uses @this->finduserFromEmail 
  */

function getReceiver(){
    //if we have a user ID lets get the updated information from #_users
    if($this->email->receiver_id) {
      $receiver = JFactory::getUser($this->email->sender_id);
      if($receiver->id === $this->email->receiver_id) {
        $this->email->receiver_name = $receiver->name;
        $this->email->receiver_email = $receiver->email;
         return true;
      }
    }
    //if receiver is is empty, and the email is set lets try to get the user object from the email
    if(empty($this->email->receiver_id) && $this->email->receiver_email ) {
       $receiver = $this->findUserFromEmail($this->email->receiver_email); 
      if($receiver->id){
        $this->email->receiver_id = $receiver->id;
         $this->email->receiver_name = $receiver->name;
         $this->email->receiver_email = $receiver->email;
         return true;
      }
    }
}

/**
  * Makes the row of receivers into an assoc array
  * @todo 
  * @example $this->makeReceiverArray($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return Assoc Array
  * 
  */
function makeReceiverArray(){
    $array = array();
    if($this->email->receiver_email) {
        $array['email'] = $this->email->receiver_email;
    }

    if($this->email->receiver_name) {
        $array['name'] = $this->email->receiver_name;
    }
    return $array;
}


/**
  * Does the sending for the sending Type
  * @todo 
  * @example $this->doSend($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return object
  * 
  */

function doSend() {


if(isset($response->code)) {
  $this->sendFailed($response );
  return false;
} else {
 $this->sendSuccess($response );  
 return true;
}


}

/**
  * Updates the row  if the email is sent
  * @todo 
  * @example $this->sendSuccess($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return object
  * 
  */
function sendSuccess() {
  $this->email->sent = 1;
  $date = JFactory::getDate( );
  $this->email->sentdate = $date->toMysql( );
  
  $this->report[] = $this->email;
}


/**
  * Updates the row  if the email not sent
  * @todo make this method do something useful
  * @example $this->sendSuccess($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return object
  * 
  */
function sendFailed() {

  $this->report[] = $this->email;

}

/**
  * After we are all done we update the email row with row we been managing through out script
  * @todo 
  * @example $this->updateRecord($row);
  * @param Object is a Row from the table #__messagebottle_emails
  * @since 0.1
  * @return object
  * 
  */

function updateRecord() {
  JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messagebottle/tables');
  $table = JTable::getInstance('Emails','MessagebottleTable');
  $table->load($this->email->email_id);
  $table->bind($this->email);
  if($table->store()) {
    return true;
  } else {
    return false;
  }
 
}

/**
  * Events are something that will most like only be for advanced integration features, so for example of you need to send a bunch emails to a bunch of users about a specific  event, like say a new product
  * or a campaign, or something that happened, and you want to be able to track those events and if and when the emails  went out and who it went to.
  * @todo I think we need to make emails send via a list of emails grouped by events
  * @example $this->updateEventRecords($row);
  * @param array of event ids emailed via the queue
  * @since 0.1
  * @return object
  * 
  */


function updateEventRecords($events) {
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messagebottle/tables');
  foreach($events as $key => $value) {
  $table = JTable::getInstance('Events','MessagebottleTable');
  $table->load($key);
  $table->processed = 1;
  $table->store();
  }
}


function checkSingleSendRules($email) {
    $return = false;
    //warning deleting these will result in infinity sending loop
    if($email->sent == 1) {
    return false;
    }

    if(trim($email->senddate) == '0000-00-00 00:00:00' || empty($email->senddate)) {
    $return = true;
    }
    return $return;
  }
/**
  * single Mail sent 
  *
  * @todo Something better with events
  * @example $this->processQueue();
  * @param 
  * @since 0.1
  * @return 
  * 
  */

function processSingle($row) {
    
    $events = array();
    
     
     $this->prepareEmail($row);

     $this->doSend();
     $this->updateRecord();

     //TODO Change this, emails should probably be sent to the process queue, grouped by event.
     if($row->event_id) {
     $events[$row->event_id] = $row->event_id;
     $this->updateEventRecords($events);
      }

}



/**
  * The is the main function, and should be the only function called directly when running this class.  it starts all the other functions 
  *
  * @todo Something better with events
  * @example $this->processQueue();
  * @param 
  * @since 0.1
  * @return 
  * 
  */

function processQueue() {
    $list = $this->getEmails();
    $events = array();
    foreach($list as $email) {
     
     $this->prepareEmail($email);
     $this->doSend();
     $this->updateRecord();

     //TODO Change this, emails should probably be sent to the process queue, grouped by event.
     $events[$email->event_id] = $email->event_id;
    }

    $this->updateEventRecords($events);

}

	protected function writeToLog($text) {
		static $first = true;
		jimport('joomla.filesystem.file');

		$dump = '';
		if (JFile::exists($this -> _log_file)) {
			$dump = JFile::read($this -> _log_file);
		}
		if ($first) {
			$dump = "\n\n" . Date('d.n.Y - H:i.s ', time()) . $text;
			$first = false;
		} else {
			// Dump at the head of the file
			$dump = "\n\n" . Date('d.n.Y - H:i.s ', time()) . $text . $dump;
		}
		JFile::write($this -> _log_file, $dump);
	}

	public function clearLog() {
		if (JFile::exists($this -> _log_file)) {
			JFile::write($this -> _log_file, '');
		}
	}

	/**
	 * Override this to avoid overwriting of other constants
	 * (we have custom language file for that)
	 * @see JPlugin::loadLanguage()
	 */
	function loadLanguage($extension = '', $basePath = JPATH_BASE, $overwrite = false) {

		if (version_compare(JVERSION, '1.6.0', 'ge')) {

			if (empty($extension)) {
				$extension = 'plg_' . $this -> _type . '_' . $this -> _name;
			}

			$language = JFactory::getLanguage();
			$lang = $language -> getTag();

			$path = JLanguage::getLanguagePath($basePath, $lang);

			if (!strlen($extension)) {
				$extension = 'joomla';
			}
			$filename = ($extension == 'joomla') ? $lang : $lang . '.' . $extension;
			$filename = $path . DS . $filename;

			$result = false;
			if (isset($language -> _paths[$extension][$filename])) {
				// Strings for this file have already been loaded
				$result = true;
			} else {
				// Load the language file
				$result = $language -> load($extension, $basePath, null, $overwrite);

				// Check if there was a problem with loading the file
				if ($result === false) {
					// No strings, which probably means that the language file does not exist
					$path = JLanguage::getLanguagePath($basePath, $language -> getDefault());
					$filename = ($extension == 'joomla') ? $language -> getDefault() : $language -> getDefault() . '.' . $extension;
					$filename = $path . DS . $filename . '.ini';

					//				$result = $language->load( $filename, $extension, $overwrite );
				}

			}

		} else {

			if (empty($extension)) {
				$extension = 'plg_' . $this -> _type . '_' . $this -> _name;
			}

			$language = JFactory::getLanguage();
			$lang = $language -> _lang;

			$path = JLanguage::getLanguagePath($basePath, $lang);

			if (!strlen($extension)) {
				$extension = 'joomla';
			}

			$filename = ($extension == 'joomla') ? $lang : $lang . '.' . $extension;
			$filename = $path . DS . $filename . '.ini';

			$result = false;
			if (isset($language -> _paths[$extension][$filename])) {
				// Strings for this file have already been loaded
				$result = true;
			} else {
				// Load the language file
				$result = $language -> _load($filename, $extension, $overwrite);

				// Check if there was a problem with loading the file
				if ($result === false) {
					// No strings, which probably means that the language file does not exist
					$path = JLanguage::getLanguagePath($basePath, $language -> _default);
					$filename = ($extension == 'joomla') ? $language -> _default : $language -> _default . '.' . $extension;
					$filename = $path . DS . $filename . '.ini';

					$result = $language -> _load($filename, $extension, $overwrite);
				}

			}

		}

		return $result;

	}

}
