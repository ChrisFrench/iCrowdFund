<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

//check for DSC Library
if (!class_exists('DSC')) {
	jimport('joomla.filesystem.file');
	if (!JFile::exists(JPATH_SITE . '/libraries/dioscouri/dioscouri.php')) {
		return false;
	}
	require_once JPATH_SITE . '/libraries/dioscouri/dioscouri.php';
}
DSC::loadLibrary();
// Check for our billets class
if (!class_exists('Billets')) {
	JLoader::register("Billets", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_billets" . DS . "defines.php");
}

/** Import library dependencies */
Billets::load('BilletsPluginBase', 'library.plugin.base');
jimport('joomla.application.component.model');
jimport('joomla.error.log');

/**
 * Billets Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgSystemRespondByEmail extends BilletsPluginBase {
	var $mail = null;

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this -> files = array();
	}

	/**
	 *
	 * Method is called onAfterInitialise
	 *
	 */
	function onAfterInitialise() {

		if (!$this -> _isInstalled())
			return null;
		if ($canCheck = $this -> canCheck()) {
			$success = $this -> checkEmail();
		}

		return null;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function _isInstalled() {
		$success = false;

		jimport('joomla.filesystem.file');
		if (JFile::exists(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'defines.php')) {
			require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'defines.php');
			$success = true;
		}
		return $success;
	}

	/**
	 *
	 * @param $name
	 * @param $default
	 * @return unknown_type
	 */
	function _getParameter($name, $default = null) {
		$return = $this -> params -> get($name, $default);
		return $return;
	}

	/**
	 *
	 * @return
	 * @param $message Object
	 */
	function errorMsg($message) {
		$response = '';
		$app = JFactory::getApplication();
		if ($app -> isAdmin()) {
			$responseMsg = JText::_('Billets Respond by Email Plugin') . " :: " . $message;
			JError::raiseNotice('errorMsg', $responseMsg);
			$options = array('format' => "{DATE}\t{TIME}\t{FUNCTION}\t{COMMENT}");
			$this -> log = JLog::getInstance('billets.log', $options);
			if ($this -> log) {
				$this -> log -> addEntry(array('function' => JText::_('Billets Respond by Email Plugin'), 'comment' => $message));
			}
		}
		return $response;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function canCheck() {
		$success = false;
		$config = Billets::getInstance();
		$email_check_interval = $this -> params -> get('email_check_interval', '10');
		$jdate = JFactory::getDate();
		$now = $jdate -> toMySQL();
		$then = $config -> get('respondbyemail_intervaldatetime', '0000-00-00 00:00:00');
		$database = JFactory::getDBO();
		$query = " SELECT '{$then}' + INTERVAL {$email_check_interval} MINUTE ";
		$database -> setQuery($query);
		$next = $database -> loadResult();

		if ($now >= $next) {
			$success = true;
			// update config
			JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'tables' . DS);
			unset($table);
			$table = JTable::getInstance('Config', 'BilletsTable');
			$table -> load('respondbyemail_intervaldatetime');
			$table -> title = 'respondbyemail_intervaldatetime';
			$table -> value = $now;
			$table -> store();
		}

		return $success;
	}

	/**
	 * Wrapper for the other specific checkEmail connectors
	 *
	 * @return
	 * @param $pop_server Object
	 * @param $pop_username Object
	 * @param $pop_password Object
	 * @param $delete_email_after_ticket_creation Object
	 */
	function checkEmail() {
		/*
		 * FETCH PLUGIN PARAMETERS
		 */
		$default_category = intval($this -> params -> get('default_category', 0));
		$query = "SELECT * FROM `#__billets_categories` WHERE `id`=$default_category";
		$db = JFactory::getDBO();
		$db -> setQuery($query);
		$obj = $db -> loadObject();
		if (empty($obj)) {
			$this -> errorMsg(JText::_('Specify an existing Default Category ID for the plugin to work!'));
			return false;
		}

		$email_type = strtolower($this -> params -> get('email_type', '0'));

		// using switch makes it easier to add preconfigured settings later
		switch ($email_type) {
			case "2" :
				// gmail
				$mailbox_type = 'imap';
				$server = 'imap.gmail.com';
				$port = '993';
				$use_ssl = '1';
				$use_tls = '1';
				$validate_certs = '0';
				break;
			case "1" :
			// pop3
			case "0" :
			// imap
			default :
				if ($email_type == '0')
					$mailbox_type = 'imap';
				// IMAP
				elseif ($email_type == '1')
					$mailbox_type = 'pop3';
				// POP3
				$server = $this -> params -> get('email_server', '');
				$port = $this -> params -> get('email_port', '');
				$use_ssl = $this -> params -> get('use_ssl', '0');
				$use_tls = $this -> params -> get('use_tls', '1');
				$validate_certs = $this -> params -> get('validate_certs', '0');
				break;
		}

		$username = $this -> params -> get('email_account_login', '');
		$password = $this -> params -> get('email_account_password', '');

		if (empty($server) || empty($username) || empty($password)) {
			$this -> errorMsg(JText::_('Missing Email Credentials'));
			return false;
		}

		if ($mailbox_type == 'pop3') {
			// POP3 account type
			$email_folder = '';

			if (intval($port) == 0) {
				if ($use_ssl == '0')
					$port = '110';
				// POP3 default port
				else
					$port = '995';
				// POP3S default port
			}
		} else {
			// IMAP and GMAIL account types
			$email_folder = $this -> params -> get('email_folder', 'INBOX');

			if (intval($port) == 0) {
				if ($use_ssl == '0')
					$port = '143';
				// IMAP default port
				else
					$port = '993';
				// IMAPS default port
			}
		}

		/*
		 * CONSTRUCT MAILBOX STRING
		 */
		$mailbox = '{' . $server . ':' . $port . '/' . $mailbox_type;

		// SSL
		if ($use_ssl == '1')
			$mailbox .= '/ssl';
		// Use SSL

		// TLS
		if ($use_tls == '0')
			$mailbox .= '/notls';
		// Don't use TLS even if available
		else if ($use_tls == '1')
			;
		// Use TLS if available. Default.
		else if ($use_tls == '2')
			$mailbox .= '/tls';
		// Use TLS. If not available, fails

		// Require Valid Certificates
		if ($use_tls != '0' || $use_ssl != '0') {
			if ($validate_certs == '0')
				$mailbox .= '/novalidate-cert';
			else if ($validate_certs == '1')
				$mailbox .= '/validate-cert';
		}

		// Folder
		$mailbox .= '}' . $email_folder;

		/*
		 * CONNECT AND CHECK FOR NEW E-MAILS
		 */

		if (version_compare(JVERSION, '1.6.0', 'ge')) {
			// Joomla! 1.6+ code here
			require_once (JPATH_SITE . DS . 'plugins' . DS . 'system' . DS . 'respondbyemail' . DS . 'respondbyemail' . DS . 'mail.class.php');
		} else {
			// Joomla! 1.5 code here
			require_once (JPATH_SITE . DS . 'plugins' . DS . 'system' . DS . 'respondbyemail' . DS . 'mail.class.php');
		}

		$this -> mail = new BilletsMail($mailbox, $username, $password);
		if (!$this -> mail -> connect()) {
			// do this to gracefully exit
			$this -> errorMsg($this -> mail -> getError());
			return false;
		}

		$lastchecked_timestamp = $this -> getLastCheckedDate();
		if (empty($lastchecked_timestamp))
			$lastchecked_timestamp = time() - 86400;

		// Convert the date into IMAP format: 31-Mar-2010
		// Go back one day in time to avoid losing e-mails sent just before midnight
		$since_date = date("d-M-Y", $lastchecked_timestamp - 86400);

		// get all emails received since the email that was last checked
		$emails = $this -> mail -> getEmailSince($since_date);

		// set the last checked date = now to prevent plugin from running again during script execution
		$jdate = JFactory::getDate();
		$date = $jdate -> toUnix();
		$this -> setLastCheckedDate($date);

		$maxdate = $lastchecked_timestamp;

		$config = Billets::getInstance();
		$storeasblob = false;
		if ($config -> get('files_storagemethod', '1') == '2') {
			$storeasblob = true;
		}

		// emails is now an array of email messages with
		// [subject], [fromaddress], [fromemail], [toaddress], [date], [body]
		foreach ($emails as $email) {
			// echo "<pre>"; print_r($email); echo "</pre>";

			// get the ticketid from the [body]
			$ticketid = $this -> getTicketId($email['body']);

			// get the fromname
			$from = $email['fromaddress'];

			// get the date in a unix timestamp (e-mail delivery time)
			$date = $email['unixdate'];
			// if the email is newer than the last email checked, process it
			$correctDate = false;
			if ($date > $lastchecked_timestamp) {
				$correctDate = true;
			}

			// Find the e-mails' maximum (delivery) date
			if ($date > $maxdate)
				$maxdate = $date;

			// get the subject
			$subject = $email['subject'];

			// get all of the [body] that is above the reply-above-line text
			$emailmessage = $this -> getMessage($email['body']);

			// does this email have the reply above line flag
			$hasReplyAboveLine = $this -> hasReplyAboveLine($email['body']);

			// get the user object from the email address
			$fromaddress = $email['fromemail'];
			$user = $this -> getUserFromEmail($fromaddress);

			$delete_email_after_ticket_creation = $this -> params -> get('delete_email_after_ticket_creation', '0');

			// if there's a ticketid & body && the email is valid, add it as a new comment
			if ($ticketid && !empty($user -> id) && $emailmessage && $hasReplyAboveLine && $correctDate) {
				if ($this -> addCommentFromEmail($ticketid, $from, $fromaddress, $date, $subject, $emailmessage, $email['attachments'], $storeasblob)) {
					// delete the email if requested
					if ($delete_email_after_ticket_creation) {
						$this -> mail -> deleteMail($email['messageid']);
					}
				}

			}
			// if there is no ticketid, but there's a body & the email is valid, create a new ticket
			elseif ($emailmessage && $correctDate) {
				if ($this -> createTicketFromEmail($from, $fromaddress, $date, $subject, $emailmessage, $email['attachments'], $storeasblob)) {
					// delete the email if requested
					if ($delete_email_after_ticket_creation) {
						$this -> mail -> deleteMail($email['messageid']);
					}
				}
			}
		}

		// Don't allow maxdate to be in the future
		if ($maxdate > time())
			$maxdate = time();

		// set the last checked date = the timestamp from the last email checked
		$this -> setLastCheckedDate($maxdate);

		return true;
	}

	/**
	 * Parses the text of an email and returns just the ticketid
	 *
	 * @param $fromline
	 * @return str
	 */
	function getTicketId($body) {
		if (preg_match('/\{ticketid\:(\d+)\}/ism', $body, $ticketmatches)) {
			return $ticketmatches[1];

		} else
			return false;
	}

	/**
	 * Parses the text of an email and returns just the message above the reply-above-line
	 *
	 * @param $fromline
	 * @return str
	 */
	function getMessage($body) {
		$split = explode('-!', $body);
		$message = $split[0];
		return $message;
	}

	/**
	 * Determines if a message has the reply above line
	 *
	 * @param $fromline
	 * @return boolean
	 */
	function hasReplyAboveLine($body) {
		$hasreplyline = false;
		//test to see if this email has the -! Reply Before line
		$regex1 = "#-!(.*?)!-#s";
		if (preg_match_all($regex1, $body, $replymatches)) {
			$hasreplyline = true;
		}
		return $hasreplyline;
	}

	/**
	 * Get the last checked date from the database, unix time stamp format
	 *
	 * @return
	 */
	function getLastCheckedDate() {
		$return = '';

		// Use BilletsConfig to store & retrieve lastchecked from the __billets_config table
		$config = Billets::getInstance();
		$lastchecked = $config -> get('respondbyemail_lastchecked');

		if (isset($lastchecked)) {
			$return = $lastchecked;
		}

		return $return;
	}

	/**
	 * take a date and see if it needs to be set to the last checked date, unix time stamp format
	 *
	 * @return
	 * @param $date Object
	 */
	function setLastCheckedDate($date) {
		// include the Billets table path
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'tables' . DS);

		unset($table);
		$table = JTable::getInstance('Config', 'BilletsTable');
		$table -> load('respondbyemail_lastchecked');
		$table -> title = 'respondbyemail_lastchecked';
		$table -> value = $date;
		$table -> save();

		unset($table);
		$table = JTable::getInstance('Config', 'BilletsTable');
		$table -> load('respondbyemail_lastchecked_human');
		$table -> title = 'respondbyemail_lastchecked_human';
		$table -> value = date("r", $date);
		$table -> save();
	}

	/**
	 *
	 * @param $address
	 * @return unknown_type
	 */
	function getUserFromEmail($address) {
		$database = JFactory::getDBO();
		// could do a smarter lookup
		//reverse lookup userid from email
		$query = "
			SELECT
				*
			FROM
				#__users
			WHERE
				`email` = '$address'
			LIMIT 1
		";
		$database -> setQuery($query);
		$data = $database -> loadObject();

		return $data;
	}

	/**
	 * adds a comment
	 * @return boolean yes/no on success/fail
	 */
	function addCommentFromEmail($ticketid, $from, $fromaddress, $date, $subject, $emailmessage, $attachments, $storeasblob) {

		$mainframe = JFactory::getApplication();
		// grab config settings for sender name and email
		$config = Billets::getInstance();
		$failFrom = $config -> get('emails_defaultemail', $mainframe -> getCfg('mailfrom'));
		$failFromName = $config -> get('emails_defaultname', $mainframe -> getCfg('fromname'));

		//prepare the message body
		$emailmessage = $emailmessage;

		// get a user object from the email address
		$emailUser = $this -> getUserFromEmail($fromaddress);

		// If email address not found or not associated with ticket, send message saying comment not added
		if (!isset($emailUser -> id) || empty($emailUser -> id)) {
			// break if the email address isnt registered in the joomla database
			$failSubject = JText::_('Comment not added to ticket');
			$failBody = JText::_('Email unrecognized');
			$sendFailMessage = $this -> _sendMail($failFrom, $failFromName, $fromaddress, $failSubject, $failBody);
			$this -> setError("Email Unrecognized");
			return false;
		} else if ($emailUser -> block) {
			// Account is blocked. So adding comments or tickets is not allowed
			return false;
		}

		// break if the user is not associated with ticket
		require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'helpers' . DS . 'ticket.php');
		if (!$canview = BilletsHelperTicket::canView($ticketid, $emailUser -> id)) {
			$failSubject = JText::_('Comment not added to ticket');
			$failBody = JText::_('Email not associated with ticket');
			$sendFailMessage = $this -> _sendMail($failFrom, $failFromName, $fromaddress, $failSubject, $failBody);
			$this -> setError("Email Not Associated with Ticket");
			return false;
		}

		// include the Billets table path
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'tables' . DS);
		unset($message);
		$message = JTable::getInstance('Messages', 'BilletsTable');

		// setup new message
		$message -> ticketid = $ticketid;
		$message -> userid_from = $emailUser -> id;
		$message -> username_from = $emailUser -> username;
		$message -> message = $emailmessage;
		$message -> priority = '0';

		// date = date + (gmtdate-now)
		$jdate = JFactory::getDate();
		$storedate = $date + ($jdate -> toUnix() - time());
		$message -> datetime = date("Y-m-d H:i:s", $storedate);

		//add the message to the database
		if (!$message -> save()) {
			$debugmsg = "message store failed::" . $message -> getError();
			$this -> setError($debugmsg);
			return false;
		}

		//update the ticket
		unset($ticket);
		$ticket = JTable::getInstance('Tickets', 'BilletsTable');
		$ticket -> load((int)$ticketid);
		// the ticket's new status depends on who emailed the comment, a manager or the user
		$new_state = $config -> get('state_new');
		if ($ticket -> sender_userid != $emailUser -> id) {
			$new_state = $config -> get('state_feedback');
		}
		$ticket -> stateid = $config -> get('state_new');
		// make pending again
		$ticket -> last_modified_datetime = date("Y-m-d H:i:s", $storedate);
		$ticket -> last_modified_by = $emailUser -> id;
		if (!$ticket -> save()) {
			$debugmsg = "ticket store failed::" . $ticket -> getError();
			$this -> setError($debugmsg);
			return false;
		}

		// handle attachments
		foreach ($attachments as $attachment) {
			if ($this -> _handleAttachments($ticket -> id, $emailUser -> id, $attachment, $storeasblob)) {
			}
		}

		// Attach files to Message
		$files_message = '';
		if (!empty($this -> files)) {
			BilletsHelperTicket::attachFilesToMessage($this -> files, $message -> id);
			$files_message .= "\n\n";
			$files_message .= JText::_('COM_BILLETS_FILES_ATTACHED') . ": ";
			$n = 0;
			foreach ($this->files as $file) {
				if ($n > 0) { $files_message .= ", ";
				}
				$files_message .= $file -> filename;
				$n++;
			}
		}

		// send emails to ppl associated with ticket
		// by firing plugin
		$ticket -> description = $emailmessage . $files_message;
		$ticket -> user = $emailUser;

		$data = clone $ticket;
		$data -> email_sender_userid = $emailUser -> id;
		$data -> _comment_user = $emailUser;

		// send email confirmation of email received
		$this -> _sendEmailConfirmation($data);

		JPluginHelper::importPlugin('billets');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher -> trigger('onAfterSaveComment', array($data));

		return $ticket;
	}

	/**
	 * adds a comment
	 * @return boolean yes/no on success/fail
	 */
	function createTicketFromEmail($from, $fromaddress, $date, $subject, $emailmessage, $attachments, $storeasblob) {
		$success = false;
		$mainframe = JFactory::getApplication();

		// Check param for whether/not creating tickets from recognized emails
		$create_tickets = $this -> _getParameter('create_tickets', '1');
		// Check param for whether/not creating tickets from unrecognized emails
		$create_tickets_unrecognized = $this -> _getParameter('create_tickets_unrecognized', '0');

		if (!$create_tickets && !$create_tickets_unrecognized) {
			$this -> setError('!create_tickets && !unrecognized');
			return $success;
		}

		// get a user object from the email address
		$emailUser = $this -> getUserFromEmail($fromaddress);

		if (empty($emailUser -> id) && !$create_tickets_unrecognized) {
			$this -> setError('!userid && !unrecognized');
			return false;

		} elseif (!empty($emailUser -> id) && !empty($emailUser -> block)) {
			// User is blocked. So creating new comments/tickets is not allowed
			$this -> setError('User Account is Blocked');
			return false;
		} elseif (empty($emailUser -> id) && $create_tickets_unrecognized) {
			// create the user object
			$newuser_email = $fromaddress;
			$newuser_username = $fromaddress;
			$newuser_name = $from;

			$msg = new stdClass();
			$msg -> type = '';
			$msg -> message = '';

			jimport('joomla.user.helper');
			$details = array();
			$details['name'] = $newuser_name;
			$details['username'] = $newuser_username;
			$details['email'] = $newuser_email;
			$details['password'] = JUserHelper::genRandomPassword();
			$details['password2'] = $details['password'];
			JLoader::import('com_billets.helpers.user', JPATH_ADMINISTRATOR . DS . 'components');
			if (!$emailUser = BilletsHelperUser::createNewUser($details, $msg)) {
				$this -> setError(JText::_('Could not Create User'));
				return false;
			}
			if (empty($emailUser -> id)) {
				// not cool
				$this -> setError(JText::_('User creation failed') . ": " . $msg -> message);
				return false;
			}
		}

		// grab config settings for sender name and email
		$config = Billets::getInstance();
		$failFrom = $config -> get('emails_defaultemail', $mainframe -> getCfg('mailfrom'));
		$failFromName = $config -> get('emails_defaultname', $mainframe -> getCfg('fromname'));

		//prepare the message body
		$emailmessage = strval(htmlspecialchars(strip_tags($emailmessage)));

		// setup the date
		$jdate = JFactory::getDate();
		$storedate = $date + ($jdate -> toUnix() - time());

		// create the ticket
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'tables' . DS);
		unset($ticket);
		$ticket = JTable::getInstance('Tickets', 'BilletsTable');
		$ticket -> stateid = $config -> get('state_new');
		$ticket -> categoryid = $this -> params -> get('default_category');
		$ticket -> subject = $subject;
		$ticket -> title = $subject;
		$ticket -> description = $emailmessage;
		$ticket -> last_modified_by = $emailUser -> id;
		$ticket -> last_modified_datetime = date("Y-m-d H:i:s", $storedate);
		$ticket -> created_datetime = date("Y-m-d H:i:s", $storedate);
		$ticket -> sender_email = $emailUser -> email;
		$ticket -> sender_userid = $emailUser -> id;

		// make sure userdata record exists
		$userdata = JTable::getInstance('Userdata', 'BilletsTable');
		$userdata -> load(array('user_id' => $emailUser -> id));
		if (empty($userdata -> user_id)) {
			JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'models');
			$config = Billets::getInstance();

			$userdata -> user_id = $emailUser -> id;
			$userdata -> limit_tickets = $config -> get('limit_tickets_globally');
			$userdata -> ticket_max = $config -> get('default_max_tickets');
			$userdata -> limit_hours = $config -> get('limit_hours_globally');
			$userdata -> hour_max = $config -> get('default_max_hours');

			$model = JModel::getInstance('Tickets', 'BilletsModel');
			$model -> setState('select', 'COUNT(tbl.id)');
			$model -> setState('filter_userid', $emailUser -> id);
			$userdata -> ticket_count = $model -> getResult();

			$model = JModel::getInstance('Tickets', 'BilletsModel');
			$model -> setState('select', 'SUM(tbl.hours_spent)');
			$model -> setState('filter_userid', $emailUser -> id);
			$userdata -> hour_count = $model -> getResult();
			$userdata -> store();
		}

		// if the user is not excluded from ticket limiting
		if (!$userdata -> limit_tickets_exclusion) {
			$limit_tickets_globally = $config -> get('limit_tickets_globally');
			// if there is a global limit OR if the user has a limit
			if ($limit_tickets_globally || $userdata -> limit_tickets) {
				// check if the user has crossed their ticket limit
				if ($userdata -> ticket_count >= $userdata -> ticket_max) {
					$this -> setError('User Has Exceeded Their Maximum Number of New Tickets');
					return false;
				}
			}
		}

		if (!$ticket -> save()) {
			$this -> setError('Could not store the ticket' . $ticket -> getError());
			return false;
		}

		// update userdata ticket_count
		$userdata -> ticket_count = $userdata -> ticket_count + 1;
		$userdata -> store();

		// handle attachments
		foreach ($attachments as $attachment) {
			if ($this -> _handleAttachments($ticket -> id, $emailUser -> id, $attachment, $storeasblob)) {
			}
		}

		// Attach files to Message
		$files_message = '';
		if (!empty($this -> files)) {
			$message = JTable::getInstance('Messages', 'BilletsTable');

			// setup new message
			$message -> ticketid = $ticket -> id;
			$message -> userid_from = $emailUser -> id;
			$message -> username_from = $emailUser -> username;
			$message -> priority = '0';
			$message -> datetime = $ticket -> created_datetime;

			$files_message .= "\n\n";
			$files_message .= JText::_('COM_BILLETS_FILES_ATTACHED') . ": ";
			$n = 0;
			foreach ($this->files as $file) {
				if ($n > 0) { $files_message .= ", ";
				}
				$files_message .= $file -> filename;
				$n++;
			}
			$message -> message = $files_message;

			//add the message to the database
			if (!$message -> store()) {
				$debugmsg = "message store failed::" . $message -> getError();
				$this -> setError($debugmsg);
			}

			require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'helpers' . DS . 'ticket.php');
			BilletsHelperTicket::attachFilesToMessage($this -> files, $message -> id);
		}

		// send emails to ppl associated with ticket
		// by firing plugin
		$ticket -> description = $emailmessage . $files_message;
		$ticket -> user = $emailUser;

		$data = clone $ticket;
		$data -> _isNew = true;
		$data -> email_sender_userid = $emailUser -> id;

		JPluginHelper::importPlugin('billets');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher -> trigger('onAfterSaveTickets', array($data));

		return $ticket;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function _sendMail($from, $fromname, $recipient, $subject, $body, $actions = NULL, $mode = NULL, $cc = NULL, $bcc = NULL, $attachment = NULL, $replyto = NULL, $replytoname = NULL) {
		$success = false;
		$message = JFactory::getMailer();
		$message -> addRecipient($recipient);
		$message -> setSubject($subject);

		// convert bbcode if present
		$fulltext = $body;
		$dispatcher = JDispatcher::getInstance();
		$dispatcher -> trigger('onBBCode_RenderText', array(&$fulltext));
		$body = $fulltext;

		$message -> IsHTML(true);
		$message -> setBody(nl2br($body));

		$sender = array($from, $fromname);
		$message -> setSender($sender);
		$sent = $message -> send();
		if ($sent == '1') {
			$success = true;
		}
		return $success;
	}

	/**
	 * Sends a confirmation to the user that their comment was received
	 *
	 * @param $ticket
	 * @return unknown_type
	 */
	function _sendEmailConfirmation($ticket) {
		$message = JFactory::getMailer();
		$message -> addRecipient($ticket -> user -> email);

		$message -> setSubject(sprintf(JText::_('Your ticket has been updated'), $ticket -> id));

		$message -> IsHTML(false);

		$link = JURI::root() . "index.php?option=com_billets&view=tickets&task=view&id=" . $ticket -> id;
		$link = JRoute::_($link, false);

		$mainframe = JFactory::getApplication();
		$config = Billets::getInstance();
		$mailfrom = $config -> get('emails_defaultemail', $mainframe -> getCfg('mailfrom'));
		$fromname = $config -> get('emails_defaultname', $mainframe -> getCfg('fromname'));
		$sitename = $config -> get('sitename', $mainframe -> getCfg('sitename'));
		$siteurl = $config -> get('siteurl', JURI::root());

		$dispatcher = JDispatcher::getInstance();
		$dispatcher -> trigger('onBBCode_RenderText', array(&$ticket -> description));

		$separater = str_repeat("-", 15);

		// $fulltext = htmlspecialchars_decode( sprintf ( JText::_('Thank you for submitting ticket'), ($user->name?$user->name:JText::_('COM_BILLETS_USER')), $ticket->id, $sitename, $separater, $ticket->description, $separater, $link ) );
		$fulltext = htmlspecialchars_decode(sprintf(JText::_('Your comments have been received')));

		$message -> setBody($fulltext);
		// only do this if HTML, otherwise take out the nl2br

		$sender = array($mailfrom, $fromname);
		$message -> setSender($sender);

		if ($mainframe -> getCfg('mailfrom') != $mailfrom) {
			$message -> addReplyTo(array($mailfrom, $fromname));
		}
		return $message -> send();
	}

	/**
	 * We will save attachments from an email when user creates new ticket
	 * or replies to email as comment
	 *
	 * @param integer $ticketid
	 * @param integer $userid
	 * @param array $attachment
	 * @return boolean true if file is saved, otherwise false
	 */
	function _handleAttachments($ticketid, $userid, $attachment, $storeasblob) {
		$date = JFactory::getDate();

		$file = JTable::getInstance('Files', 'BilletsTable');
		$file -> physicalname = JUtility::getHash(time()) . '.' . $attachment['extension'];
		$file -> filename = $attachment['filename'];
		$file -> fileextension = $attachment['extension'];
		$file -> filesize = number_format(($attachment['size'] / 1024), 2) . ' Kb';
		$file -> fileisblob = ( empty($storeasblob)) ? 0 : 1;
		$file -> datetime = $date -> toMysql();
		$file -> userid = $userid;
		$file -> ticketid = $ticketid;
		if (!$file -> save()) {
			return false;
		}
		$this -> files[] = $file;

		if ($storeasblob) {
			$database = JFactory::getDBO();
			$data = $database -> Quote($attachment['attachment']);
			$query = " INSERT INTO #__billets_fileblobs " . " SET `fileid` = '" . $file -> id . "', " . " `fileblob` = {$data} ";
			$database -> setQuery($query);
			$database -> query();
		} else {
			$directory = JPATH_SITE . DS . 'images' . DS . 'attachmentsbillets' . DS;
			if (!$exists = JFolder::exists($directory)) {
				$create = JFolder::create($directory);
				if (!is_writable($directory)) {
					$change = JPath::setPermissions($directory);
				}
			} else {
				if (!is_writable($directory)) {
					$change = JPath::setPermissions($directory);
				}
			}

			// then confirms existence of htaccess file
			$htaccess = $directory . DS . '.htaccess';
			if (!$fileexists = JFile::exists($htaccess)) {

				$destination = $htaccess;
				$text = "deny from all";
				if (!JFile::write($destination, $text)) {
					$this -> setError(JText::_('STORAGE DIRECTORY IS UNPROTECTED'));
				}

			}

			$destination = $directory . $file -> physicalname;
			if (!JFile::write($destination, $attachment['attachment'])) {
				$this -> setError(JText::_('COULD NOT SAVE FILE'));
			}
		}
	}

}
