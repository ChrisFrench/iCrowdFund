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

class AmbraControllerLogin extends AmbraController 
{
    /**
     * constructor
     */
    function __construct() 
    {
        if (!empty(JFactory::getUser()->id))
        {
            $redirect = "/index.php?option=com_ambra&view=logout";
            $redirect = JRoute::_( $redirect, false );
            JFactory::getApplication()->redirect( $redirect );
            return;
        }
        parent::__construct();
        $this->set('suffix', 'login');
    }
    
    /**
     * Displays the login form
     * @see ambra/site/AmbraController#display($cachable)
     */
    function display($cachable=false, $urlparams = false)
    {
        $model  = $this->getModel( $this->get('suffix') );
        
        $view = $this->getView( 'login', 'html' );
        $view->set( '_controller', 'login' );
        $view->set( '_view', 'login' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        $view->setLayout( 'default' );
        
        // get the article        
        $article_id = Ambra::getInstance()->get('article_login', '0');
        $article_html = Ambra::getClass('AmbraArticle', 'library.article')->display( $article_id );
        $view->assign('loginArticle', $article_html );
        
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
        $dispatcher->trigger( 'onBeforeDisplayLoginForm', array() );
        $view->assign( 'onBeforeDisplayLoginForm', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onDisplayLoginFormRightColumn', array() );
        $view->assign( 'onDisplayLoginFormRightColumn', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterDisplayLoginForm', array() );
        $view->assign( 'onAfterDisplayLoginForm', ob_get_contents() );
        ob_end_clean();
        
        $view->display();
        $this->footer();
    }
    
    /**
     * Perform the login 
     * @return 
     */
    function login()
    {
        
        // Check for request forgeries
        JRequest::checkToken('request') or jexit( 'Invalid Token' );

        $app = JFactory::getApplication();
        
        
        

        $options = array();
        $options['remember'] = JRequest::getBool('remember', false);
        $login_redirect=Ambra::getInstance()->get('login_redirect_url', '');
        $redirect=Ambra::getInstance()->get('login_redirect', '');
        
        if(($login_redirect)&&($redirect))
        {
        $return=$login_redirect;    
        }
        else{
            $options['return'] = $return;
        } 
        
        if ($postedreturn = JRequest::getVar('return', '', 'method', 'base64')) 
        {
            $return = base64_decode($postedreturn);

            if (!JURI::isInternal($return)) {
                $return = '';
            }

        }


        $credentials = array();
        $credentials['username'] = JRequest::getVar('username', '', 'method', 'username','string');
        $credentials['password'] = JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);
      
        
        //preform the login action
        $login = $app->login($credentials, $options);
       
        
        if ($login)
        
        {
            
            // Redirect if the return url is not registration or login
                
            if ( empty($return) ) 
            {
            
                $return = 'index.php?option=com_ambra&view=login';
                $return = DSCRoute::_( $return, false );
            }
            
            $this->messagetype  = 'message';
            // $this->message      = JText::_( 'You have successfully logged in' );

            // fire plugins
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterUserLoginAmbra', array() );
            
            $this->setRedirect( $return, $this->message, $this->messagetype );            
        }
            else
        {
            $return = 'index.php?option=com_ambra&view=registration';
            $return = DSCRoute::_( $return, false );

            // Redirect to a login form
            $app->redirect( $return );
        }
    }
}