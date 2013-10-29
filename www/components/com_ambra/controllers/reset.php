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

class AmbraControllerReset extends AmbraController 
{
    /**
     * constructor
     */
    function __construct() 
    {
        parent::__construct();
        $this->set('suffix', 'reset');
    }
    
    /**
     * Request Password Reset
     * 
     * @return unknown_type
     */
    function request()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $email      = JRequest::getVar('email', null, 'post', 'string','string');

        // Request a reset
        jimport('joomla.mail.helper');
        jimport('joomla.user.helper');

        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();
        $redirect = JRoute::_('index.php?option=com_ambra&view=reset');
        
        // Make sure the e-mail address is valid
        if (!JMailHelper::isEmailAddress($email))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "INVALID_EMAIL_ADDRESS" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Build a query to find the user
        $query  = 'SELECT id FROM #__users'
                . ' WHERE email = '.$db->Quote($email)
                . ' AND block = 0';

        $db->setQuery($query);

        // Check the results
        if (!($id = $db->loadResult()))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "COULD_NOT_FIND_USER" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Generate a new token
        $token = JUtility::getHash(JUserHelper::genRandomPassword());

        $query  = 'UPDATE #__users'
                . ' SET activation = '.$db->Quote($token)
                . ' WHERE id = '.(int) $id
                . ' AND block = 0';

        $db->setQuery($query);

        // Save the token
        if (!$db->query())
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "DATABASE_ERROR" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Send the token to the user via e-mail
        $config     = JFactory::getConfig();
        $uri        = JFactory::getURI();
        $url        = JURI::base().'index.php?option=com_ambra&view=reset&task=confirm';
        $sitename   = $config->getValue('sitename');

        // Set the e-mail parameters
        $from       = $config->getValue('mailfrom');
        $fromname   = $config->getValue('fromname');
        $subject    = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $sitename);
        $body       = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $sitename, $token, $url);

        // Send the e-mail
        if (!JUtility::sendMail($from, $fromname, $email, $subject, $body))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "ERROR_SENDING_CONFIRMATION_EMAIL" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        $this->setRedirect('index.php?option=com_ambra&view=reset&task=confirm');        
        return true;
    }
    
    /**
     * Displays the form to confirm token
     * 
     * @return unknown_type
     */
    function confirm()
    {
        JRequest::setVar( 'view', 'reset' );
        JRequest::setVar( 'layout', 'confirm' );
        parent::display();
    }
    
    /**
     * Password Reset Confirmation Method
     *
     * @access  public
     */
    function confirmreset()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $token = JRequest::getVar('token', null, 'post', 'alnum');

        // Verify the token
        $mainframe = JFactory::getApplication();
        $redirect = JRoute::_('index.php?option=com_ambra&view=reset');

        if (strlen($token) != 32) 
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "INVALID_TOKEN" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        $db = JFactory::getDBO();
        $db->setQuery('SELECT id FROM #__users WHERE block = 0 AND activation = '.$db->Quote($token));

        // Verify the token
        if (!($id = $db->loadResult()))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "INVALID_TOKEN" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Push the token and user id into the session
        $mainframe->setUserState($this->_namespace.'token', $token);
        $mainframe->setUserState($this->_namespace.'id',    $id);
        
        $this->setRedirect('index.php?option=com_ambra&view=reset&task=complete');
        return;
    }

    /**
     * Displays the form to confirm token
     * 
     * @return unknown_type
     */
    function complete()
    {
        JRequest::setVar( 'view', 'reset' );
        JRequest::setVar( 'layout', 'complete' );
        parent::display();
    }
    
    /**
     * Password Reset Completion Method
     *
     * @access  public
     */
    function completereset()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $password1 = JRequest::getVar('password1', null, 'post', 'string', JREQUEST_ALLOWRAW);
        $password2 = JRequest::getVar('password2', null, 'post', 'string', JREQUEST_ALLOWRAW);

        // Reset the password
        jimport('joomla.user.helper');
        $mainframe = JFactory::getApplication();
        $redirect = JRoute::_('index.php?option=com_ambra&view=reset');
        
        // Make sure that we have a pasword
        if ( ! $password1 )
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "MUST_SUPPLY_PASSWORD" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Verify that the passwords match
        if ($password1 != $password2)
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "PASSWORDS_DO_NOT_MATCH_LOW" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Get the necessary variables
        $db         = JFactory::getDBO();
        $id         = $mainframe->getUserState($this->_namespace.'id');
        $token      = $mainframe->getUserState($this->_namespace.'token');
        $salt       = JUserHelper::genRandomPassword(32);
        $crypt      = JUserHelper::getCryptedPassword($password1, $salt);
        $password   = $crypt.':'.$salt;

        // Get the user object
        $user = new JUser($id);

        // Fire the onBeforeStoreUser trigger
        JPluginHelper::importPlugin('user');
        $dispatcher =& JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeStoreUser', array($user->getProperties(), false));

        // Build the query
        $query  = 'UPDATE #__users'
                . ' SET password = '.$db->Quote($password)
                . ' , activation = ""'
                . ' WHERE id = '.(int) $id
                . ' AND activation = '.$db->Quote($token)
                . ' AND block = 0';

        $db->setQuery($query);

        // Save the password
        if (!$result = $db->query())
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "DATABASE_ERROR" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Update the user object with the new values.
        $user->password         = $password;
        $user->activation       = '';
        $user->password_clear   = $password1;

        // Fire the onAfterStoreUser trigger
        $dispatcher->trigger('onAfterStoreUser', array($user->getProperties(), false, $result, $this->getError()));

        // Flush the variables from the session
        $mainframe->setUserState($this->_namespace.'id',    null);
        $mainframe->setUserState($this->_namespace.'token', null);

        $redirect = JRoute::_('index.php?option=com_ambra&view=login');
        
        $this->message      = JText::_( "PASSWORD_RESET_SUCCESS" );
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
        
        return;
    }
}