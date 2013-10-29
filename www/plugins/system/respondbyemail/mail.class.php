<?php
/**
 * @package Billets
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class BilletsMail extends JObject
{
	var $mbox = null;
	var $imapParams = null;
	
	/**
	 * Contructor. For the parameters used here see the imap_open() declaration:
	 * http://php.net/manual/en/function.imap-open.php
	 * 
	 * @param $mailbox
	 * @param $username
	 * @param $password
	 * @param $options
	 * @param $n_retries
	 * @param $params
	 */
	function __construct( $mailbox, $username, $password, $options = 0, $n_retries = 0, $params = array())
	{
		// Save parameters. connect() function will initialize the connection to the email server
		
		$this->imapParams = new stdClass();
		
		$this->imapParams->mailbox = $mailbox;
		$this->imapParams->username = $username;
		$this->imapParams->password = $password;
		$this->imapParams->options = $options;
		$this->imapParams->n_retries = $n_retries;
		$this->imapParams->params = $params;
	}
	
    /**
     * Sets connection to mailbox
     * 
     * @param  int      $messageid
     * @return array 
     */
	function connect()
	{
	 	// Mailbox string is in this format: 
	 	// {localhost:995/pop3/ssl/novalidate-cert}Folder/subfolder
	 	
	    if (version_compare(PHP_VERSION, '5.3.2') >= 0) // PHP >= v5.3.2
	    {
	    	$this->mbox = @imap_open(
                                $this->imapParams->mailbox, $this->imapParams->username,
                                $this->imapParams->password, $this->imapParams->options,
                                $this->imapParams->n_retries, $this->imapParams->params);
	    }
	    	else if (version_compare(PHP_VERSION, '5.2.0') >= 0) // PHP >= v5.2.0
	    {
            $this->mbox = @imap_open(
                                $this->imapParams->mailbox, $this->imapParams->username,
                                $this->imapParams->password, $this->imapParams->options,
                                $this->imapParams->n_retries);
	    }
    	    else // PHP < v5.2.0
	    {
            
	        $this->mbox = @imap_open(
                                $this->imapParams->mailbox, $this->imapParams->username,
                                $this->imapParams->password, $this->imapParams->options);
	    }
		
		if (empty($this->mbox))
		{
			$this->setError( JText::_('MAIL READER CANNOT CONNECT') . " :: ".imap_last_error() );
			return false;
		}
		
		// uncomment this if you want to see connection details
		//echo "<pre>"; print_r( $this->getMailboxInfo() ); echo "</pre>";
		
		return true;
	}
	

	/**
     * Deletes a message
     * 
     * @param  int      $messageid
     * @return array 
     */
    function deleteMail($messageid)
    {
        if (!imap_delete($this->mbox, $messageid))
        {
            $this->setError( JText::_('MAIL READER CANNOT DELETE MAIL') . " $messageid :: ".imap_last_error() );
            return false;
        }
        return true;
    }
    
    /**
     * Mark emails as read
     * @param $messages array of messages' id or simple int
     */
    function markAsReadMail($messages)
    {
        if (is_array($messages))
            $mex = implode(",", $messages);
        else
            $mex = $messages;
            
        if (!imap_setflag_full($this->mbox, $mex, "\\Seen"))
        {
            $this->setError( JText::_('MAIL READER CANNOT MARK MAIL AS READ') . " $messageid :: ".imap_last_error() );
            return false;
        }
        return true;
    }
	
    /**
     * Opens connection to a mailbox
     * 
     * @param  str  
     * @return boolean 
     */
	function openMailBox($mailbox)
	{
        if (!imap_reopen($this->mbox, $mailbox ))
        {
            $this->setError( JText::_('MAIL READER CANNOT OPEN MAILBOX') . " $mailbox :: ".imap_last_error() );
            return false;
        }
        return true;
	}
	
    /**
     * Gets mailbox info
     * 
     * @return  
     */
	function getMailboxInfo()
	{
        $mc = imap_check($this->mbox);
        return $mc;
	}
	
	/**
	 * $date should be a string
	 * Example Formats Include:
	 * Fri, 5 Sep 2008 9:00:00
	 * Fri, 5 Sep 2008
	 * 5 Sep 2008
	 * I am sure other's work, just test them out.
	 */
	function getHeadersSince($date)
	{
        $uids = $this->getMessageIdsSinceDate($date);
        $messages = array();
        foreach( $uids as $k=>$uid )
        {
            $messages[] = $this->getHeader($uid);
        }
        return $messages;
	}
	
	/**
	 * $date should be a string
	 * Example Formats Include:
	 * Fri, 5 Sep 2008 9:00:00
	 * Fri, 5 Sep 2008
	 * 5 Sep 2008
	 * I am sure other's work, just test them out.
	 * 
	 * Edit:
	 * IMAP's format is 31-Mar-2010
	 */
	function getEmailSince( $date)
	{
		$uids = $this->getMessageIdsSinceDate( $date );
		$messages = array();
		foreach( $uids as $k=>$uid ) {
		     $messages[] = $this->getMessage( $uid );
		}
		return $messages;
	}
	
    /**
     * Gets all the message ids received since
     * a particular date
     * 
     * @param  int      $messageid
     * @return array 
     */
	function getMessageIdsSinceDate( $date )
	{
		if ( $date == '00-00-0000' ) {
			if ($messages = imap_search( $this->mbox, "RECENT UNDELETED")) {
				return $messages;
			}
		}
		elseif ($messages = imap_search( $this->mbox, "SINCE \"$date\" UNDELETED")) {
			return $messages;
		}
	    return array(); 
	}

    /**
     * Gets a message header
     * 
     * @param  int      $messageid
     * @return array 
     */
	function getHeader($messageid)
	{
	   $message = array();
	
	   $header = imap_header($this->mbox, $messageid);
	   $structure = imap_fetchstructure($this->mbox, $messageid);
	
       $message['subject']     = $this->decodeMIME( $header->subject );
       $message['fromaddress'] = $this->decodeMIME( $header->fromaddress );
       $message['fromemail']   = $header->from[0]->mailbox."@".$header->from[0]->host;
       // $message['date']        = $header->date; // E-mail send-date
       $message['date']        = $header->MailDate; // E-mail delivery date
       $message['unixdate']    = $header->udate;
	
	   return $message;
	}
	
	/**
	 * Gets a message w/ header
	 * 
     * @param  int      $messageid
     * @return array 
	 */
	function getMessage( $messageid )
	{
		$config 				= Billets::getInstance();
		$message_subtype		= 'PLAIN';
		$save_attachments		= $config->get( 'emails_save_attachments', 1 );
		$emails_encoding_debug	= $config->get( 'emails_encoding_debug', 0 );
		$message 				= array();
		$header					= imap_header( $this->mbox, $messageid );
		$structure 				= imap_fetchstructure($this->mbox, $messageid);
		if ( $save_attachments ) {
			$message['attachments']	= $this->extractAttachments($structure, $messageid);
		}

		$message['messageid']   = $messageid; // messageid is passed back in case we want to delete afterwards
		$message['subject']     = $this->decodeMIME( $header->subject );
		$message['fromaddress'] = $this->decodeMIME( $header->fromaddress );
		$message['fromemail']   = $header->from[0]->mailbox."@".$header->from[0]->host;
		// $message['date']        = $header->date; // Email's sent-time timestamp
		$message['date']        = $header->MailDate; // Email's delivery-time timestamp
		$message['unixdate']    = $header->udate; // Email's delivery-time unixtime
		
		if ( $this->isMultipart( $structure ) ) {
			$body_main_part_ref = "1";
			$structure = $this->getMainPart( $structure, $message_subtype, $body_main_part_ref );
			
            $message['body'] = imap_fetchbody($this->mbox,$messageid, $body_main_part_ref); ## GET THE BODY OF MULTI-PART MESSAGE
            if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]nn';}
        }
		else {
            $message['body'] = imap_body($this->mbox, $messageid);
            if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]nn';}
        }
		$message['bodytexttype'] = $structure->subtype;
		foreach ( $structure->parameters as $body_parameter )
		{
		    if ( $body_parameter->attribute == 'CHARSET' )
		    {
				$body_charset = $body_parameter->value;
			}
		}
		if ( $emails_encoding_debug ) {
			$message['body']	= "Character Set: {$body_charset}\nCharacter Encoding: {$structure->encoding}\n" . $message['body'];
		}
	    switch ( $structure->encoding ) {
			case 0:	//7-bit
				if ( strtolower( $body_charset ) != 'utf-8' ) {
			  		$message['body'] = $this->changeEncoding( $message['body'], $body_charset, 'UTF-8' );
				}
				break;
			case 1:	//8-bit
				if ( strtolower( $body_charset ) != 'utf-8' ) {
		  			//$message['body'] = $this->changeEncoding( imap_8bit( $message['body'] ), $body_charset, 'UTF-8' );
		  			$message['body'] = $this->changeEncoding( $message['body'], $body_charset, 'UTF-8' );
				}
				break;
			case 3:	// base-64
				if ( strtolower( $body_charset ) != 'utf-8' ) {
					$message['body'] = $this->changeEncoding( imap_base64( $message['body'] ), $body_charset, 'UTF-8' );
				} else {
					$message['body'] = imap_base64( $message['body'] );
				}
				break;
			case 4:	// qouote-printable
				if ( strtolower( $body_charset ) != 'utf-8' ) {
					$message['body'] = $this->changeEncoding( imap_qprint( $message['body'] ), $body_charset, 'UTF-8' );
					//$message['body'] = $this->changeEncoding( $message['body'], $body_charset, 'UTF-8' );
				} else {
					$message['body']	= imap_qprint( $message['body'] ) ;
				}
				break;
			default:
				if ( strtolower( $body_charset ) != 'utf-8' ) {
					$message['body'] = $this->changeEncoding( $message['body'], $body_charset, 'UTF-8' );
				}
	    }
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger( 'onBBCode_RenderText', array(&$message) );
		return $message;
	}


	/*
	 * Convert chacter set encoding according to input/output
	 * character sets
	 * 
	 * Modified function from libraries/simplepie/simplepie.php
	 * 
	 * @param string $data
	 * @param string $input Character set from
	 * @param string $output Character set to
	 * @return string converted string to $ouput character set
	 **/
	function changeEncoding($data, $input, $output)
	{
		// We fail to fail on non US-ASCII bytes
		if ($input === 'US-ASCII')
		{
			static $non_ascii_octects = '';
			if (!$non_ascii_octects)
			{
				for ($i = 0x80; $i <= 0xFF; $i++)
				{
					$non_ascii_octects .= chr($i);
				}
			}
			$data = substr($data, 0, strcspn($data, $non_ascii_octects));
		}

		// This is first, as behaviour of this is completely predictable
		if ($input === 'Windows-1252' && $output === 'UTF-8')
		{
			return $this->windows_1252_to_utf8($data);
		}
		// This is second, as behaviour of this varies only with PHP version (the middle part of this expression checks the encoding is supported).
		elseif (function_exists('mb_convert_encoding') && @mb_convert_encoding("\x80", 'UTF-16BE', $input) !== "\x00\x80" && ($return = mb_convert_encoding($data, $output, $input)))
		{
			return $return;
		}
		// This is last, as behaviour of this varies with OS userland and PHP version
		elseif (function_exists('iconv') && ($return = @iconv($input, $output, $data)))
		{
			return $return;
		}
		// If we can't do anything, just fail
		else
		{
			return $data;
		}
	}

	/**
	 * Determines if message is multi-part or not
	 * 
	 * @param  object      $structure
	 * @return boolean
	 */
	function isMultipart( $structure )
	{
		if( $structure->type == 1 ) {
			return(true);
		}
		return false;
	}

	/*
	 * Decodes email headers according to character set encoding
	 * @param object $data structure of headers 
	 */
	function decodeMIME( $data )
	{
		$return		= "";
		$oDataParts = imap_mime_header_decode($data); // It returns too many array items
		foreach ($oDataParts AS $oDataPart) {
			$return .= ($oDataPart->charset == 'default' ? rtrim( $oDataPart->text, "\t" ) : $this->changeEncoding( rtrim( $oDataPart->text, "\t" ), $oDataPart->charset, 'UTF-8' ) );
		}
		return $return;
	}
    
    /*
     * Iterative function to identify ticket message in an email
     * 
     * @param object $structure Structure of Email headers
     * @param string $message_subtype It can be only PLAIN or HTML
     * @param mixed $main_part_ref Reference to main part identified during 
     * 						body parsing for PLAIN or HTML subtypes
     * @return object structure of main-body part (PLAIN/HTML)
     */
	function getMainPart( $structure, $message_subtype, &$main_part_ref )
	{
		$part_id = 0;							// It will store the main part (wich will be added to the ticket) ID
		
		// If the message has parts
	    if ( $this->isMultipart($structure) )
		{
			$body_main_part = '';						// Drop default value of the message
			$i = 1;
			$no_body_exists_flag = 1;					// Flag meaning we have not find an appropriate body part yet

			// Find the first occurence of a part with type text/html or,
			// if such a part is not found, of a part with type text/plain
			foreach ( $structure->parts as $body_part ) {
				if ( $this->isMultipart( $body_part ) ) {
					$body_main_part = $this->getMainPart ($body_part, $message_subtype , $main_part_ref);
					if ( $main_part_ref != '' ) {
						$main_part_ref = $i . '.' . $main_part_ref;
					}
				}
				else {
					// ifdisposition==0 means this is not attachement
					// $body_part->type == 0: This is a text part (.txt attachments can also fall here)
					// has subtype
					if ( !$body_part->ifdisposition && $body_part->type == 0 && $body_part->ifsubtype && $body_part->subtype == $message_subtype ) {
						$body_main_part = $body_part;
						$main_part_ref = $part_id + 1 ;
						$no_body_exists = 0;
						$part_id = $i;
						break;
					}
					// In case we did not find a part of required subtype,
					// we get either HTML or PLAIN, depending what is present
					// in the message. This will help if only a part of opposite
					// subtype is present in the message. This is not a big problem,
					// because we will strip tags either way
					else if ( !$body_part->ifdisposition && $body_part->type == 0 && $body_part->ifsubtype && ( $body_part->subtype == 'PLAIN' || $body_part->subtype == 'HTML' ) && $no_body_exists_flag ) {
						$body_main_part = $body_part;					  // Actual subtype will be returned in the structure,
						$no_body_exists = 0;						  // so upper layers will not get confused
						$part_id = $i;
						$main_part_ref = $part_id;
					}
				} // No multipart
				// If we find neither text/plain nor text/html parts/subparts,
				// when message is multipart, empty string will be returned
				++$i;
			} // foreach part
		}
	    else {
			$body_main_part = $structure;					// No parts in the message
	    }
		return $body_main_part;
	}
	
	/*
	 * Extract Attachment store if all the conditions met
	 * 
	 * @param object $structure
	 * @param integer $message_number
	 * @return array Array containing attachment information
	 */
	function extractAttachments( $structure, $message_number )
	{
		$config 						= Billets::getInstance();
		$config_file_maxsize			= $config->get( 'files_maxsize', '3000' );
		$restricted_file_extensions		= trim ( strtolower( $config->get( 'restricted_file_extensions', 'php,html,asp,aspx,jsp,py,htm,shtml,shtm' ) ) );
		$auto_convert_file_extension	= $config->get( 'auto_convert_file_extension' );
		$attachments = array();  
		if(isset($structure->parts) && count($structure->parts)) {
	   
			for($i = 0; $i < count($structure->parts); $i++) {
				if($structure->parts[$i]->ifdparameters) {
					foreach($structure->parts[$i]->dparameters as $object) {
						if ( ( strtolower( $object->attribute ) == 'filename' ) && ( ( $structure->parts[$i]->bytes/1024 ) <= $config_file_maxsize ) && (  $structure->parts[$i]->bytes > 0  ) ) {
							$namebits = explode('.', $object->value);
							$extension = strtolower( $namebits[count($namebits)-1] );
							if ( $auto_convert_file_extension == 0 ) {
								if ( !preg_match( '/'.$extension.'/', $restricted_file_extensions ) ) {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
									$attachments[$i]['size'] = $structure->parts[$i]->bytes;
									$attachments[$i]['extension'] = $extension;
								}
							} else {
								$attachments[$i]['is_attachment'] = true;
								if ( preg_match( '/'.$extension.'/', $restricted_file_extensions ) ) {
									$attachments[$i]['filename'] = $object->value . '.txt';
									$attachments[$i]['extension'] = $extension . '.txt';
								} else {
									$attachments[$i]['filename'] = $object->value;
									$attachments[$i]['extension'] = $extension;
								}								
								$attachments[$i]['size'] = $structure->parts[$i]->bytes;
								
							}
						}
					}
				}
			   
				if($structure->parts[$i]->ifparameters) {
					foreach($structure->parts[$i]->parameters as $object) {
						if ( ( strtolower( $object->attribute ) == 'name' ) && ( ( $structure->parts[$i]->bytes/1024 ) <= $config_file_maxsize ) && (  $structure->parts[$i]->bytes > 0  ) ) {
							$namebits = explode('.', $object->value);
							$extension = strtolower( $namebits[count($namebits)-1] );
							if ( $auto_convert_file_extension == 0 ) {
								if ( !preg_match( '/'.$extension.'/', $restricted_file_extensions ) ) {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
									$attachments[$i]['size'] = $structure->parts[$i]->bytes;
									$attachments[$i]['extension'] = $extension;
								}
							} else {
								$attachments[$i]['is_attachment'] = true;
								if ( preg_match( '/'.$extension.'/', $restricted_file_extensions ) ) {
									$attachments[$i]['filename'] = $object->value . '.txt';
									$attachments[$i]['extension'] = $extension . '.txt';
								} else {
									$attachments[$i]['filename'] = $object->value;
									$attachments[$i]['extension'] = $extension;
								}								
								$attachments[$i]['size'] = $structure->parts[$i]->bytes;
							}
						}
					}
				}
			   
				if(!empty($attachments[$i]['is_attachment'])) {
					$attachments[$i]['attachment'] = imap_fetchbody($this->mbox, $message_number, $i+1);
					if( $structure->parts[$i]->encoding == 3 ) { // 3 = BASE64
						$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
					}
					elseif( $structure->parts[$i]->encoding == 4 ) { // 4 = QUOTED-PRINTABLE
						$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
					}
				}
			}
		}
		return $attachments;
	}
    
	/**
	 * Converts a Windows-1252 encoded string to a UTF-8 encoded string
	 *
	 * @static
	 * @access public
	 * @param string $string Windows-1252 encoded string
	 * @return string UTF-8 encoded string
	 */
	function windows_1252_to_utf8($string)
	{
		static $convert_table = array("\x80" => "\xE2\x82\xAC", "\x81" => "\xEF\xBF\xBD", "\x82" => "\xE2\x80\x9A", "\x83" => "\xC6\x92", "\x84" => "\xE2\x80\x9E", "\x85" => "\xE2\x80\xA6", "\x86" => "\xE2\x80\xA0", "\x87" => "\xE2\x80\xA1", "\x88" => "\xCB\x86", "\x89" => "\xE2\x80\xB0", "\x8A" => "\xC5\xA0", "\x8B" => "\xE2\x80\xB9", "\x8C" => "\xC5\x92", "\x8D" => "\xEF\xBF\xBD", "\x8E" => "\xC5\xBD", "\x8F" => "\xEF\xBF\xBD", "\x90" => "\xEF\xBF\xBD", "\x91" => "\xE2\x80\x98", "\x92" => "\xE2\x80\x99", "\x93" => "\xE2\x80\x9C", "\x94" => "\xE2\x80\x9D", "\x95" => "\xE2\x80\xA2", "\x96" => "\xE2\x80\x93", "\x97" => "\xE2\x80\x94", "\x98" => "\xCB\x9C", "\x99" => "\xE2\x84\xA2", "\x9A" => "\xC5\xA1", "\x9B" => "\xE2\x80\xBA", "\x9C" => "\xC5\x93", "\x9D" => "\xEF\xBF\xBD", "\x9E" => "\xC5\xBE", "\x9F" => "\xC5\xB8", "\xA0" => "\xC2\xA0", "\xA1" => "\xC2\xA1", "\xA2" => "\xC2\xA2", "\xA3" => "\xC2\xA3", "\xA4" => "\xC2\xA4", "\xA5" => "\xC2\xA5", "\xA6" => "\xC2\xA6", "\xA7" => "\xC2\xA7", "\xA8" => "\xC2\xA8", "\xA9" => "\xC2\xA9", "\xAA" => "\xC2\xAA", "\xAB" => "\xC2\xAB", "\xAC" => "\xC2\xAC", "\xAD" => "\xC2\xAD", "\xAE" => "\xC2\xAE", "\xAF" => "\xC2\xAF", "\xB0" => "\xC2\xB0", "\xB1" => "\xC2\xB1", "\xB2" => "\xC2\xB2", "\xB3" => "\xC2\xB3", "\xB4" => "\xC2\xB4", "\xB5" => "\xC2\xB5", "\xB6" => "\xC2\xB6", "\xB7" => "\xC2\xB7", "\xB8" => "\xC2\xB8", "\xB9" => "\xC2\xB9", "\xBA" => "\xC2\xBA", "\xBB" => "\xC2\xBB", "\xBC" => "\xC2\xBC", "\xBD" => "\xC2\xBD", "\xBE" => "\xC2\xBE", "\xBF" => "\xC2\xBF", "\xC0" => "\xC3\x80", "\xC1" => "\xC3\x81", "\xC2" => "\xC3\x82", "\xC3" => "\xC3\x83", "\xC4" => "\xC3\x84", "\xC5" => "\xC3\x85", "\xC6" => "\xC3\x86", "\xC7" => "\xC3\x87", "\xC8" => "\xC3\x88", "\xC9" => "\xC3\x89", "\xCA" => "\xC3\x8A", "\xCB" => "\xC3\x8B", "\xCC" => "\xC3\x8C", "\xCD" => "\xC3\x8D", "\xCE" => "\xC3\x8E", "\xCF" => "\xC3\x8F", "\xD0" => "\xC3\x90", "\xD1" => "\xC3\x91", "\xD2" => "\xC3\x92", "\xD3" => "\xC3\x93", "\xD4" => "\xC3\x94", "\xD5" => "\xC3\x95", "\xD6" => "\xC3\x96", "\xD7" => "\xC3\x97", "\xD8" => "\xC3\x98", "\xD9" => "\xC3\x99", "\xDA" => "\xC3\x9A", "\xDB" => "\xC3\x9B", "\xDC" => "\xC3\x9C", "\xDD" => "\xC3\x9D", "\xDE" => "\xC3\x9E", "\xDF" => "\xC3\x9F", "\xE0" => "\xC3\xA0", "\xE1" => "\xC3\xA1", "\xE2" => "\xC3\xA2", "\xE3" => "\xC3\xA3", "\xE4" => "\xC3\xA4", "\xE5" => "\xC3\xA5", "\xE6" => "\xC3\xA6", "\xE7" => "\xC3\xA7", "\xE8" => "\xC3\xA8", "\xE9" => "\xC3\xA9", "\xEA" => "\xC3\xAA", "\xEB" => "\xC3\xAB", "\xEC" => "\xC3\xAC", "\xED" => "\xC3\xAD", "\xEE" => "\xC3\xAE", "\xEF" => "\xC3\xAF", "\xF0" => "\xC3\xB0", "\xF1" => "\xC3\xB1", "\xF2" => "\xC3\xB2", "\xF3" => "\xC3\xB3", "\xF4" => "\xC3\xB4", "\xF5" => "\xC3\xB5", "\xF6" => "\xC3\xB6", "\xF7" => "\xC3\xB7", "\xF8" => "\xC3\xB8", "\xF9" => "\xC3\xB9", "\xFA" => "\xC3\xBA", "\xFB" => "\xC3\xBB", "\xFC" => "\xC3\xBC", "\xFD" => "\xC3\xBD", "\xFE" => "\xC3\xBE", "\xFF" => "\xC3\xBF");

		return strtr($string, $convert_table);
	}

}
?>