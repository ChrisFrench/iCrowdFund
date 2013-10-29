<?php
/**
 * @version	1.5
 * @package	Messagebottle
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Messagebottle::load('MessagebottleSenderBase', 'library.plugins.sender');

require_once ('Mandrill.php');

class plgMessagebottleSender_mandrill extends MessagebottleSenderBase {
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element = 'sender_mandrill';
	
	
	var $Mandrill = null;
	var $row = null;
	var $message = null;
	var $ping = null;
	var $sendmethod = 'mandrill';
	var $defaultsender = true;


	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$language = JFactory::getLanguage();
		$language -> load('plg_messagebottle_' . $this -> _element, JPATH_ADMINISTRATOR, 'en-GB', true);
		$language -> load('plg_messagebottle_' . $this -> _element, JPATH_ADMINISTRATOR, null, true);
		
		$apiKey = Messagebottle::getInstance()->get('md_smtp_api_key','');

		if(empty($apiKey)) {
			// Get a handle to the Joomla! application object
			$application = JFactory::getApplication();
			 
			// Add a message to the message queue
			$application->enqueueMessage(JText::_('COM_MESSAGEBOTTLE_MANDRILL_APIKEY_EMPTY'), 'warning');

			return;
		}

  		$this->Mandrill = new Mandrill($apiKey);
  		

	}

	function doSend() {

	$response = $this->Mandrill->messages->send($this->message, true);

		if(isset($response->code)) {
		 $this->sendFailed($row,$response->code );
		} else {
		  $this->sendSuccess( );  
		}
		

	}

	function applyTemplate() {


		$header = $this->_getLayout( 'header', null, '', 'messagebottle');
		$footer = $this->_getLayout( 'footer', null, '', 'messagebottle');
		$body  = $header .$this->email->body. $footer;
		return $body;
	}

	function getAttachments($email) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__messagebottle_attachments AS tbl');
        $query->where('tbl.email_id = '.$email->email_id);
        
        $db->setQuery($query);
        
        $items = $db->loadObjectList();
        return $items;
  
	}

	function prepareAttachment($path, $name = null) {
		$array = array();
		//TODO support http://www.php.net/manual/en/ref.fileinfo.php instead of 	mime_content_type
		$array['type'] = mime_content_type($path);
		//TODO smarter way to get the name?
		if($name) {
			$array['name'] = $name;
		} else {
			$array['name'] = end(explode ( '/' , $path ));
		}
		$data = file_get_contents($path);
		$array['content'] = base64_encode($data);
		
		return $array;

	}


	function prepareEmail($email) {
   
   parent::prepareEmail($email);
   $to = $this->makeReceiverArray();

   
   $body = $this->applyTemplate();

   $attachments = $this->getAttachments($email);
   $processedAttachments = array();
   foreach ($attachments as $attachment) {

   	$processedAttachments[] = $this->prepareAttachment($attachment->path);
   }
   


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

/* https://mandrillapp.com/api/docs/messages.html#method=send */
 $params = array(
   
        "html" => $body,
        "text" => null,
        "from_email" => $this->email->sender_email,
        "from_name" => $this->email->sender_name,
        "subject" => $this->email->title,
     //   "to" => array($to),
        "to" => array($to),

        "track_opens" => true,
        "track_clicks" => true,
        "auto_text" => true 
    );
	 
	 if(count($processedAttachments)) {
	 $params['attachments'] = array($processedAttachments);
	 }
 

  /*  $params = array(
    'message' => array(
        "html" => $this->email->body,
        "text" => null,
        "from_email" => $this->email->sender_email,
        "from_name" => $this->email->sender_name,
        "subject" => $this->email->title,
     //   "to" => array($to),
        "to" => array($to),
        "track_opens" => true,
        "track_clicks" => true,
        "auto_text" => true )
    );*/

$this->message = $params;

}	
	function AfterMessageBottleQueueEmail($email) {
		
		$rules = $this->checkSingleSendRules($email);
		
		if($rules) {
		
		$this->processSingle($email);	
		} 

	}
	
	function onAfterStoreEmails($email) {
		
		$rules = $this->checkSingleSendRules($email);
		if($rules) {
		$this->processSingle($email);	
		}

	}
	

	function OnMessageBottleRunCron() {
	
		$this->processQueue();

		return $this->formatReport();
	}

	function OnMessageBottleRunCronMandrill() {
		$this->processQueue();
	}

	//todo redo this to something better
	function formatReport() {
		$success = 0;
		$failed = 0;

		foreach($this->report as $email) {
			if($email->sent) {
				$success++;
			} else {
				$failed++;
			}
		}

		$html ='REPORT OF MANDRILL SENDER' . '<br>';
		$html .= 'Sent: ' .$success . '<br>';
		$html .= 'Failed: ' .$failed . '<br>';
		$html .= '===========================================================';

		return $html;

	}
}
