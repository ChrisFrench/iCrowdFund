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

/** Import library dependencies */
Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

class plgBilletsEmails extends BilletsPluginBase {

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		$this->loadLanguage( 'com_billets', JPATH_BASE );
		$this->loadLanguage( 'com_billets', JPATH_ADMINISTRATOR );
	}

	/**
	 * Method is called
	 * after a new message is saved
	 *
	 * @return
	 * @param $data Object
	 * @param $user Object
	 * @param $msg Object $msg->type and $msg->message are available
	 */
	function onAfterSaveTickets( $data )
	{
		$success = false;

		if (!empty($data->_isNew))
		{
			//	email users involved
			$data->comment = $data->description;
			if (empty($data->user))
			{
				$data->user = JFactory::getUser();
			}
			$success = $this->sendEmailNotices( $data, $data->user, 'new' );
		}

		return $success;
	}

	/**
	 * Method is called
	 * after a new comment is added
	 *
	 * @return
	 * @param $data Object
	 * @param $user Object
	 * @param $msg Object $msg->type and $msg->message are available
	 */
	function onAfterSaveComment( $data )
	{
		$success = false;
		$data->comment = $data->description;
		if (empty($data->user))
		{
			$data->user = JFactory::getUser();
		}
		//	email users involved
			$success = $this->sendEmailNotices( $data, $data->user, 'addcomment' );

		return $success;
	}

	/**
	 * Method is called
	 * after a new file is added
	 *
	 * @return
	 * @param $data Object
	 * @param $user Object
	 * @param $msg Object $msg->type and $msg->message are available
	 */
	function onAfterSaveAttachment( $data )
	{
		$success = false;

		//	email users involved
			$send = $this->sendEmailNotices( $data, JFactory::getUser(), 'addfile' );

		if ($send) {
			// save success
			$msg->type = 'message';
			$msg->message .= " - ".JText::_('Email Sent');
		} else {
			// save but no email
			$msg->type = 'notice';
			$msg->message .= " - ".JText::_('Email Failed');
		}

		return $success;
	}

	/**
	 * Returns an array of user objects
	 *
	 * @param $data Object
	 * @return array
	 */
	function getEmailRecipients( $data, $type )
	{
		$user = JFactory::getUser();
		
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'helpers'.DS.'category.php' );
		$recipients = BilletsHelperCategory::getEmailManagers( $data->categoryid );
		
		
		// Send a message to the creator of the ticket (frontend/backend/respondbyemail-plugin)
		if ( isset( $data->sender_userid ) ) {
			$sender = JFactory::getUser( (int) $data->sender_userid );
			$recipients[] = $sender;
		}

		$config = Billets::getInstance();
		$emails_sendselfnotification	= $config->get( 'emails_sendselfnotification', '0' );
		
		// Exlude the currently logged in user (fronted/backend comment/ticket)
		// or the sender of the e-mail (respondbyemail plugin comment/ticket)
		// from notifications 
		$return = array();
		if ($emails_sendselfnotification==0) {
			for ($i=0; $i<count($recipients); $i++) {
				$r = $recipients[$i];
				
				if (isset($data->email_sender_userid)){
					// RespondByEmail plugin (exlude e-mail sender)
					if($data->email_sender_userid != $r->id) $return[] = $r;
	
				} else {
					// Frontend/Backend (exclude logged in user)
					if($user->id != $r->id) $return[] = $r;
				}
			}
		} else {
			$return = $recipients;
		}

		return $return;
	}

	/**
	 * Returns
	 *
	 * @param object
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function getEmailContent( $data, $isManager, $type ) 
	{
        $lang =& JFactory::getLanguage();
        $lang->load('com_billets', JPATH_ADMINISTRATOR);
        
		$type = strtolower($type);
		
		$return = new stdClass();
		$return->body = '';
		$return->subject = '';
		Billets::load( 'BilletsHelperEmails', 'helpers.emails' );		
		$helper = new BilletsHelperEmails();
		$helper = $helper->processTicket( $data, $type, $isManager );
		$return->subject = $helper->_subject;
		$return->body = $helper->_body;

		if ($this->enabledRBE())
		{
$return->body = '========================================================
-! '.JText::_('REPLY ABOVE THIS LINE TO ADD A COMMENT TO THE TICKET').' !-  
--
' . $helper->_body . '
--
{ticketid:'.$data->id.'}
';
		}

		return $return;
	}
	
	/**
	 * Checks if respond by email is enabled
	 * @return unknown_type
	 */
	function enabledRBE()
	{
	    $query = "SELECT `published` FROM #__plugins WHERE `element` = 'respondbyemail' AND `folder` = 'system' LIMIT 1; ";
	    $db = JFactory::getDBO();
	    $db->setQuery( $query );
	    $result = $db->loadResult();
	    return $result;
	}

	/**
	 * Returns
	 * @param mixed Boolean
	 * @param array Email content
	 * @return boolean
	 */
	function sendEmailNotices( $data, $user, $type='1' )
	{
		$mainframe = JFactory::getApplication();
		$success = false;
		$done = array();

		// grab config settings for sender name and email
		$config 	= Billets::getInstance();
		$mailfrom 	= $config->get( 'emails_defaultemail', $mainframe->getCfg('mailfrom') );
		$fromname 	= $config->get( 'emails_defaultname', $mainframe->getCfg('fromname') );
		$sitename 	= $config->get( 'sitename', $mainframe->getCfg('sitename') );
		$siteurl 	= $config->get( 'siteurl', JURI::root() );

		$recipients = $this->getEmailRecipients( $data, $type );
		if (empty($recipients))
		{
			$success = true;
			return $success;
		}
		
		for ($i=0; $i<count($recipients); $i++) {
			$recipient = $recipients[$i];
			if (!isset($done[$recipient->email])) {
				
				Billets::load( 'BilletsHelperManager', 'helpers.manager' );
				if( BilletsHelperManager::isUser( $recipient->id ) )
				{
					$content = $this->getEmailContent( $data, true, $type );
				}
				else 
				{
					$content = $this->getEmailContent( $data, false, $type );					
				}
				
				// trigger event onAfterGetEmailContent
				$dispatcher= JDispatcher::getInstance();
				$dispatcher->trigger('onAfterGetEmailContent', array( $data, &$content) );
				
				// Use the internal _sendMail function rather than helper function
				if ( $send = $this->_sendMail( $mailfrom, $fromname, $recipient->email, $content->subject, $content->body ) ) {
					$success = true;
					$done[$recipient->email] = $recipient->email;
				}
			}
		}

		return $success;
	}

	/**
	 *
	 * @return unknown_type
	 */
	function _sendMail( $from, $fromname, $recipient, $subject, $body, $actions=NULL, $mode=NULL, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyto=NULL, $replytoname=NULL )
	{
		$success = false;
		// use this code instead of the BilletsHelperEmail
		$message =& JFactory::getMailer();
		$message->addRecipient( $recipient );
		$message->setSubject( $subject );

		// TODO Make this a setting that the user can change
		$message->IsHTML(true);

		$fulltext = htmlspecialchars_decode( $body );
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );

		$message->setBody( nl2br( $fulltext ) ); // only do this if HTML, otherwise take out the nl2br

		$sender = array( $from, $fromname );
		$message->setSender($sender);
		$mainframe =& JFactory::getApplication();
		if ($mainframe->getCfg('mailfrom') != $from)
		{
            $message->addReplyTo( array($from, $fromname) );		    
		}
		$sent = $message->send();
		if ($sent == '1') {
			$success = true;
		}
		return $success;
	}

}
