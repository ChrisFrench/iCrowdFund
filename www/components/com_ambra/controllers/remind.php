<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerRemind extends AmbraController 
{
    /**
     * constructor
     */
    function __construct() 
    {
        parent::__construct();
        $this->set('suffix', 'remind');
    }
    
    /**
     * Sends the username to the registered email address
     * 
     * @return unknown_type
     */
    function username()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $mainframe = JFactory::getApplication();
        $redirect = JRoute::_('index.php?option=com_ambra&view=remind');
        
        // Get the input
        $email = JRequest::getVar('email', null, 'post', 'string','string');

        // Send the reminder
        jimport('joomla.mail.helper');
        
        // Validate the e-mail address
        if (!JMailHelper::isEmailAddress($email))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "INVALID_EMAIL_ADDRESS" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        $db = JFactory::getDBO();
        $db->setQuery('SELECT username FROM #__users WHERE email = '.$db->Quote($email), 0, 1);

        // Get the username
        if (!($username = $db->loadResult()))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "COULD_NOT_FIND_EMAIL" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Send the reminder email
        $config     = JFactory::getConfig();
        $uri        = JFactory::getURI();
        $url        = $uri->toString( array('scheme', 'host', 'port')).JRoute::_('index.php?option=com_ambra&view=login', false);

        $from       = $config->getValue('mailfrom');
        $fromname   = $config->getValue('fromname');
        $subject    = JText::sprintf('USERNAME_REMINDER_EMAIL_TITLE', $config->getValue('sitename'));
        $body       = JText::sprintf('USERNAME_REMINDER_EMAIL_TEXT', $config->getValue('sitename'), $username, $url);

        if (!JUtility::sendMail($from, $fromname, $email, $subject, $body))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "ERROR_SENDING_REMINDER_EMAIL" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        $message = JText::sprintf('USERNAME_REMINDER_SUCCESS', $email);
        $this->setRedirect('index.php?option=com_ambra&view=login', $message);
        return;

    }

}