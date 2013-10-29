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

class AmbraControllerLogout extends AmbraController 
{
    /**
     * constructor
     */
    function __construct() 
    {
        DSCAcl::validateUser(JText::_('COM_AMBRA_REDIRECT_LOGIN'), 'index.php?option=com_ambra&view=login');

        parent::__construct();
        $this->set('suffix', 'logout');
    }
    
    /**
     * Displays the logout form
     * @see ambra/site/AmbraController#display($cachable)
     */
    function display($cachable=false, $urlparams = false)
    {
        $model  = $this->getModel( $this->get('suffix') );
        
        $view = $this->getView( 'logout', 'html' );
        $view->set( '_controller', 'logout' );
        $view->set( '_view', 'logout' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        $view->setLayout( 'default' );
        
        // get the article
        $article_id = Ambra::getInstance()->get('article_logout', '0');
        $article_html = Ambra::getClass('AmbraArticle', 'library.article')->display( $article_id );
        $view->assign('logoutArticle', $article_html );
        
        // if there is a return URL base64encoded, then set it for the form to use
        $return = JRequest::getVar('return', '', 'method', 'base64'); 
        $view->assign('return', $return );
        
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $item   = $menu->getActive();
        if ($item) {
            $params = $menu->getParams($item->id);
        } else {
            $params = $menu->getParams(null);
        }
        $view->assign('params', $params);
        
        $dispatcher = JDispatcher::getInstance();
        ob_start();
        $dispatcher->trigger( 'onBeforeDisplayLogoutForm', array() );
        $view->assign( 'onBeforeDisplayLogoutForm', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onDisplayLogoutFormRightColumn', array() );
        $view->assign( 'onDisplayLogoutFormRightColumn', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterDisplayLogoutForm', array() );
        $view->assign( 'onAfterDisplayLogoutForm', ob_get_contents() );
        ob_end_clean();
        
        $view->display();
        $this->footer();
    }
    
/**
     * Perform the logout 
     * @return 
     */
    function autologout()
    { 
    // RETURN IN A MENU PARAM
    $params = JFactory::getApplication('site')->getParams();
    $item = JFactory::getApplication()->getMenu()->getItem($params->get("logout_redirect"));
    $return = JRoute::_($item->link . '&Itemid=' . $item->id);

    $app = JFactory::getApplication();              
    $user_id = JFactory::getUser()->get('id');   

    $app->logout($user_id, array());

    // Redirect if the return url is not registration or logout
            if ( empty($return) ) 
            {
                $return = 'index.php?option=com_ambra&view=login';
                $return = JRoute::_( $return, false );
            }

    $this->messagetype  = 'message';
    $this->message      = JText::_('COM_AMBRA_SUCCESSFULLY_LOGGED_OUT');

            // fire plugins
    $dispatcher = JDispatcher::getInstance();
    $dispatcher->trigger( 'onAfterUserLogoutAmbra', array() );
           

    $app->redirect($return, $this->message, $this->messagetype);
    }

    /**
     * Perform the logout 
     * @return 
     */
    function logout()
    {
        // Check for request forgeries
        JRequest::checkToken('request') or jexit( 'Invalid Token' );

        $app = JFactory::getApplication();
        
        if ($return = JRequest::getVar('return', '', 'method', 'base64')) 
        {
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) {
                $return = '';
            }
        }

        //preform the logout action
        $error = $app->logout();

        if (!JError::isError($error))
        {
            // Redirect if the return url is not registration or logout
            if ( empty($return) ) 
            {
                $return = 'index.php?option=com_ambra&view=login';
                $return = JRoute::_( $return, false );
            }

            $this->messagetype  = 'message';
            $this->message      = JText::_( 'You have been successfully logged out' );

            // fire plugins
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterUserLogoutAmbra', array() );
            
            $this->setRedirect( $return, $this->message, $this->messagetype );            
            
        }
            else
        {
            // Facilitate third party logout forms
            if ( empty($return) ) 
            {
                $return = 'index.php?option=com_ambra&view=logout';
                $return = JRoute::_( $return, false );
            }

            // Redirect to a logout form
            $app->redirect( $return );
        }
    }
}