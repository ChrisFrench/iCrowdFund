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

Billets::load('BilletsHelperBase','helpers._base' );

class BilletsHelperEmails extends BilletsHelperBase
{
 
	 /**
     * Processes a new ticket
     * 
     * @param $ticketId
     * @return unknown_type
     */
  public function processTicket( $ticket, $emailtype, $isManager=false ) 
    {
    	//get ticket
    	/*JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $model = JModel::getInstance( 'Tickets', 'BilletsModel' );
        $ticket = $model->getTable( 'tickets' );
		$ticket->load( $ticketId );*/
		$user = JFactory::getUser( $ticket->sender_userid );
				
		$this->_ticket = $ticket;
		$this->_user = $user;
		$this->_comment_user = (!empty($ticket->_comment_user)) ? $ticket->_comment_user : $user;
		Billets::load('BilletsHelperCategory','helpers.category' );
		
        $this->_category = BilletsHelperCategory::getTitle( $ticket->ticket_categoryid );
    	
        // get config settings
		$config = Billets::getInstance();
		// TODO Move these to parameters for the plugin
		$emails_includedescription 		= $config->get( 'emails_includedescription', '1' );
		$emails_descriptionmaxlength 	= $config->get( 'emails_descriptionmaxlength', '-1' );
		$emails_includecomments 		= $config->get( 'emails_includecomments', '1' );
		$emails_commentmaxlength		= $config->get( 'emails_commentmaxlength', '-1' );
		
		//Get first parent category
		
		Billets::load('BilletsHelperCategory','helpers.category' );
		$parentcategory = BilletsHelperCategory::getFirstParent($ticket->categoryid);
		$parentcategory = $parentcategory->title;
		
		// get the placeholders array here so the switch statement can add to it
        $placeholders = BilletsHelperEmails::getPlaceholderDefaults();
        $placeholders['user.id'] = $user->id;
        $placeholders['user.name'] = $user->name;
        $placeholders['user.username'] = $user->username;
        $placeholders['user.email'] = $user->email;
        $placeholders['comment_user.id'] = $this->_comment_user->id;
        $placeholders['comment_user.name'] = $this->_comment_user->name;
        $placeholders['comment_user.username'] = $this->_comment_user->username;
        $placeholders['comment_user.email'] = $this->_comment_user->email;
        $placeholders['ticket.id'] = $ticket->id;
        $placeholders['ticket.title'] = $ticket->title;
        $placeholders['ticket.parentcategory'] = $parentcategory;
        $linkSite = JURI::root()."index.php?option=com_billets&view=tickets&task=view&id=".$ticket->id;
		$linkAdmin = JURI::root()."administrator/index.php?option=com_billets&view=tickets&task=view&id=".$ticket->id;
		$linkManage = JURI::root()."index.php?option=com_billets&view=manage&task=view&id=".$ticket->id;
        $placeholders['ticket.linksite'] = JRoute::_( $linkSite, false );
        $placeholders['ticket.linkadmin'] = JRoute::_( $linkAdmin, false );
        $placeholders['ticket.linkmanage'] = JRoute::_( $linkManage, false );

        switch ( $emailtype )
        {
        	case "3": // addfile
			case "addfile":
				$this->_subject = BilletsHelperEmails::replacePlaceholders( JText::_('COM_BILLETS_BILLETS_EMAIL_NEW_ATTACHMENT_SUBJECT'), $placeholders );				
        		if( $isManager )
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_ATTACHMENT_BODY_MANAGER' ), $placeholders );
				}
				else
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_ATTACHMENT_BODY_USER'), $placeholders );
				}
								
			  break;
			case "2": // addcomment
			case "addcomment":
			case "comment":
				if ($emails_includecomments) {
					// if include, trim and set body
					if ($emails_commentmaxlength > 0)
					{
						$placeholders['ticket.comment'] = JString::substr( stripslashes( $ticket->comment ), 0, $emails_commentmaxlength );
					}
						else
					{
						$placeholders['ticket.comment'] = stripslashes( $ticket->comment );
					}
				}
				    else 
				{
					$placeholders['ticket.comment'] = "";	
				}
				
				$this->_subject = BilletsHelperEmails::replacePlaceholders( JText::_('COM_BILLETS_BILLETS_EMAIL_NEW_COMMENT_SUBJECT' ), $placeholders );
				if( $isManager )
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_COMMENT_BODY_MANAGER' ), $placeholders );
				}
				else
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_COMMENT_BODY_USER'), $placeholders );
				}
				
				
			  break;
			case "1": // new
			default:
				if ($emails_includedescription)
				{
					// if include, trim and set body
					if ($emails_descriptionmaxlength > 0)
					{
						$placeholders['ticket.description'] = JString::substr( stripslashes( $ticket->description ), 0, $emails_descriptionmaxlength );
					}
						else
					{
						$placeholders['ticket.description'] = stripslashes( $ticket->description );
					}
				}
				else 
				{
					$placeholders['ticket.description'] = '';
				}				
				
				$this->_subject = BilletsHelperEmails::replacePlaceholders( JText::_('COM_BILLETS_BILLETS_EMAIL_NEW_TICKET_SUBJECT' ), $placeholders );
       	 		if( $isManager )
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_TICKET_BODY_MANAGER' ), $placeholders );
				}
				else
				{					
					$this->_body = BilletsHelperEmails::replacePlaceholders( JText::_( 'COM_BILLETS_BILLETS_EMAIL_NEW_TICKET_BODY_USER'), $placeholders );
				}				
				
			  break;
        }
        
        $this->_placeholders = $placeholders;
        
        return $this;
    }
        
	/**
     * Creates the placeholder array with the default site values
     * 
     * @return unknown_type
     */
  public static   function getPlaceholderDefaults()
    {
        $mainframe = JFactory::getApplication();
        $config = Billets::getInstance();
        $site_name              = $config->get( 'sitename', $mainframe->getCfg('sitename') );
        $site_url               = $config->get( 'siteurl', JURI::root() );
        
        // default placeholders
        $placeholders = array(
            'site.name'                 => $site_name,
            'site.url'                  => $site_url
        );
        
        return $placeholders;
    }
    
	/**
     * Replaces placeholders with their values
     * 
     * @param string $text
     * @param array $placeholders
     * @return string
     * @access public
     */
  public static   function replacePlaceholders($text, $placeholders)
    {
        $plPattern = '{%key%}';
        
        $plKeys = array();
        $plValues = array();
        
        foreach ($placeholders as $placeholder => $value) {
            $plKeys[] = str_replace('key', $placeholder, $plPattern);
            $plValues[] = $value;
        }
        
        $text = str_replace($plKeys, $plValues, $text);     
        return $text;
    }
}