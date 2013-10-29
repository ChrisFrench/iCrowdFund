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

class AmbraControllerRegistration extends AmbraController 
{
    function __construct() 
    {
        if (!empty(JFactory::getUser()->id))
        {
            $redirect = "index.php?option=com_ambra&view=users";
            $redirect = JRoute::_( $redirect, false );
            JFactory::getApplication()->redirect( $redirect );
           
            return;

        }
        parent::__construct();
        $this->set('suffix', 'registration');
    }
    
    /**
     * Displays the registration form
     * but only if they have selected a profile 
     * (or if there is only one created profile)
     * 
     * @see ambra/site/AmbraController#display($cachable)
     */
    function display($cachable=false, $urlparams = false)
    {
       $profile_id = JRequest::getVar( 'profile_id', '0', 'request', 'int','int' );
        // if profile_id empty and more than one created & enabled, redirect to select profiles
        $tpl = null;
        if (empty($profile_id))
        {
            // check if more than one enabled & created
            $profiles_model = Ambra::getClass("AmbraModelProfiles", "models.profiles");
            $profiles_model->setState('filter_enabled', '1');
            $profiles = $profiles_model->getList();
            if (!empty($profiles) && count($profiles) > '1')
            {
                // if profile_id empty and more than one created & enabled, redirect to select profiles
                $redirect = "index.php?option=com_ambra&view=registration&task=selectprofile";
                $redirect = JRoute::_( $redirect, false );
                JFactory::getApplication()->redirect( $redirect );
                return;                
            }
            
            // otherwise use the default profile_id
            if (!empty($profiles) && count($profiles) == '1')
            {
                $profile_id = $profiles[0]->profile_id;
            }



        }
        // check if more than one enabled & created
         
      
        $model  = $this->getModel( $this->get('suffix') );
        $view = $this->getView( 'registration', 'html' );
        $view->set( '_controller', 'registration' );
        $view->set( '_view', 'registration' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        
        
        // assign the profile
        $view->assign('profile_id', $profile_id );
        
        // check if more than one enabled & created
        $profiles_model = Ambra::getClass("AmbraModelProfiles", "models.profiles");
        $profiles_model->setState('filter_enabled', '1');
        $profiles = $profiles_model->getList();
        $view->assign('profiles', count($profiles) );
        
       $templates =  Ambra::getInstance()->get('registration_profiles_templates', 0);
       if($templates) {
        foreach ($profiles as $profile) {
            if($profile->profile_id == $profile_id) {
              
                $profilename = str_replace (" ", "", $profile->profile_name);
                $profilename = JFilterOutput::stringURLSafe($profilename); 
                $tpl = $profilename;
            }
        }
        }


        // get the profile's custom fields
        $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
        $categories_model->setState('filter_enabled', '1');
        $categories_model->setState('order', 'tbl.ordering');
        $categories_model->setState('filter_profile', $profile_id);
        if ($categories = $categories_model->getList())
        {
            foreach ($categories as $category)
            {
                $fields_model = Ambra::getClass("AmbraModelFields", "models.fields");
                $fields_model->setState('filter_enabled', '1');
                $fields_model->setState('order', 'tbl.ordering');
                $fields_model->setState('filter_category', $category->category_id);
                $category->fields = $fields_model->getList();
            }
        }
        if (empty($categories)) { $categories = array(); }
        $view->assign('categories', $categories );
                
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
        $dispatcher->trigger( 'onBeforeDisplayRegistrationForm', array() );
        $view->assign( 'onBeforeDisplayRegistrationForm', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onDisplayRegistrationFormRightColumn', array() );
        $view->assign( 'onDisplayRegistrationFormRightColumn', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterDisplayRegistrationForm', array() );
        $view->assign( 'onAfterDisplayRegistrationForm', ob_get_contents() );
        ob_end_clean() ;
        
       
        $view->display($tpl);
        $this->footer(); 
    }
    
    /**
     * Displays the select profile form
     * but only if they have selected a profile 
     * (or if there is only one created profile)
     * 
     * @see ambra/site/AmbraController#display($cachable)
     */
    function selectprofile()
    {
        $model = Ambra::getClass( "AmbraModelProfiles", 'models.profiles' );
        $model->setState('filter_enabled', '1');
        $model->setState('order', 'tbl.ordering');
        $model->setState('direction', 'ASC');
        $items = $model->getList();
        if (empty($items) || count($items) == '1')
        {
            if (empty($items))
            {
                $profile_id = 0;
            }
                else
            {
                $profile_id = $items[0]->profile_id;
            }
            $redirect = "index.php?option=com_ambra&view=registration&profile_id=".$profile_id;
            $redirect = JRoute::_( $redirect, false );
            JFactory::getApplication()->redirect( $redirect );
            return;                
        }
        
        $view = $this->getView( 'registration', 'html' );
        $view->set( '_controller', 'registration' );
        $view->set( '_view', 'registration' );
        $view->set('layout', 'selectprofile');
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        $view->setLayout('selectprofile');
        $view->assign( 'items', $items);
        
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
        $dispatcher->trigger( 'onBeforeDisplaySelectProfileForm', array() );
        $view->assign( 'onBeforeDisplaySelectProfileForm', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterDisplaySelectProfileForm', array() );
        $view->assign( 'onAfterDisplaySelectProfileForm', ob_get_contents() );
        ob_end_clean();
        
        $view->display();
        $this->footer();
    }

    /**
     * Checks that a username is available
     * @return unknown_type
     */
    function checkUsername()
    {   
        
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string','string' ) ) );
      
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );
     
        $username = $values['username'];
    
        if (empty($username))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Username cannot be empty", 'fail' );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
         
        $message = "";        
        if ($checker->usernameExists($username)) 
        {
           
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Username Unavailable", 'fail' );
        } 
            else 
        {   
            
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Valid Username', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';
        
        echo json_encode( $response );
        return;
    }

    /**
     * Checks that a username is available, Cleaned up version we are only checking 1 value why, get the entire form and convert it to array, 
     * get one value, check one value.
     * @return unknown_type
     */
    function checkUN()
    {   
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $username =  JRequest::getVar( 'username', '', 'post', 'string','string' );
       
        if (empty($username))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Username cannot be empty", 'fail' );
            $response['error'] = '1';
            echo json_encode( $response );
            return;
        }
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
   
        $message = "";        
        if ($checker->usernameExists($username)) 
        {
           
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Username Unavailable", 'fail' );
        } 
            else 
        {   
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Valid Username', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';
        echo json_encode( $response );
        return;
    }
    /**
     * Checks that an email is valid , Cleaned up version we are only checking 1 value why, get the entire form and convert it to array, 
     * get one value, check one value.
     * @return unknown_type
     */
    function emailCheck()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $email =  JRequest::getVar( 'email', '', 'post', 'string','string' );
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        

        if (empty($email))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email cannot be empty", 'fail' );
            $response['error'] = '1';
              echo json_encode( $response );
            return;
        }
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        
        $message = "";

        if (!$checker->isEmailAddress($email)) 
        {
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email Invalid", 'fail' );
        }
        
        if ($checker->emailExists($email)) 
        {
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email Already Registered", 'fail' );
        }

        if (!$checker->emailExists($email) && $checker->isEmailAddress($email))
        {
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Email OK', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';

        echo json_encode( $response );
        return;
    }




    /**
     * Checks that an email is valid
     * @return unknown_type
     */
    function checkEmail()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string','string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $email = $values['email'];

        if (empty($email))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email cannot be empty", 'fail' );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        
        $message = "";

        if (!$checker->isEmailAddress($email)) 
        {
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email Invalid", 'fail' );
        }
        
        if ($checker->emailExists($email)) 
        {
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email Already Registered", 'fail' );
        }

        if (!$checker->emailExists($email) && $checker->isEmailAddress($email))
        {
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Email OK', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';

        echo json_encode( $response );
        return;
    }
    /**
     * Checks that a password is strong enough, Cleaned up version we are only checking 1 value why, get the entire form and convert it to array, 
     * get one value, check one value.
     * @return unknown_type
     */
    function passwordCheck()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
         // get elements from post
        $password =  JRequest::getVar( 'password', '', 'post', 'string','string' );
         // get elements from post
        $username =  JRequest::getVar( 'username', '', 'post', 'string','string' );
        

        if (empty($password))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Password cannot be empty", 'fail' );
            $response['error'] = '1';
             echo json_encode( $response );
            return;
        }
        
        // get the password checker & check it
        $checker = Ambra::getClass( "AmbraHelperPassword", 'helpers.password' );
        $checker->setConfig();
        
        $message = "";        
        if (!$checker->checkPassword($password, $username)) 
        {
            // error encountered            
            foreach ($checker->errorMsgArray as $error) 
            {
                $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( $error, 'fail' );
            }
        } 
            else 
        {
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Strong Password', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';

        echo json_encode( $response );
        return;
    }


    /**
     * Checks that a password is strong enough
     * @return unknown_type
     */
    function checkPassword()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string','string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $password = $values['password'];
        $username = empty( $values['username'] ) ? '' : $values['username'];

        if (empty($password))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Password cannot be empty", 'fail' );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        // get the password checker & check it
        $checker = Ambra::getClass( "AmbraHelperPassword", 'helpers.password' );
        $checker->setConfig();
        
        $message = "";        
        if (!$checker->checkPassword($password, $username)) 
        {
            // error encountered            
            foreach ($checker->errorMsgArray as $error) 
            {
                $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( $error, 'fail' );
            }
        } 
            else 
        {
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Strong Password', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';

        echo json_encode( $response );
        return;
    }
    
    /**
     * Checks that a password and password2 match
     * @return unknown_type
     */
    function checkPassword2()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string','string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $password = $values['password'];
        $password2 = $values['password2'];

        if (empty($password))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Password cannot be empty", 'fail' );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        if (empty($password2))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Verify Password field cannot be empty", 'fail' );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        $message = "";        
        if ($password != $password2) 
        {
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Passwords do not match', 'fail' );
        } 
            else 
        {
            // no error
            $message .= Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( 'Password Verified', 'success' ); 
        } 
        
        $response['msg'] = $message;
        $response['error'] = '1';

        echo json_encode( $response );
        return;
    }

    /**
     * Verifies the fields in a submitted form.  Uses the table's check() method.
     * Will often be overridden. Is expected to be called via Ajax 
     * 
     * @return unknown_type
     */
    function validate()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
            
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string','string' ) ) );
        
        if (empty($elements))
        {
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Unable to Process Form" ); 
            echo ( json_encode( $response ) );
            return;
        }
            
        // convert elements to array that can be binded
        $values = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->elementsToArray( $elements );
        
        $helper = Ambra::getClass( "AmbraHelperBase", 'helpers._base' );
        // check username
        $username = $values['username'];
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        if ($checker->usernameExists($username)) 
        {
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Please fix username" );
            echo ( json_encode( $response ) );
            return;
        }
        
        // check email
        $email = $values['email'];
        if ($checker->emailExists($email) || !$checker->isEmailAddress($email))
        {
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Please fix email address" );
            echo ( json_encode( $response ) );
            return;
        }
                
        // check password
        $password = $values['password'];
        
        // check password2
        $password2 = $values['password2'];
        
        $checker = Ambra::getClass( "AmbraHelperPassword", 'helpers.password' );
        $checker->setConfig();
        
        $message = "";        
        if (!$checker->checkPassword($password, $username)) 
        {
            // error encountered            
            foreach ($checker->errorMsgArray as $error) 
            {
                $message .= "<li>".JText::_( $error )."</li>" ;
            }
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( $message );
            echo ( json_encode( $response ) );
            return;
        }
        
        if (empty($password) || empty($password2) || ($password != $password2) )
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Please fix password fields" );
            $response['error'] = '1';
            echo ( json_encode( $response ) );
            return;
        }
        
        // check custom fields
        if (!empty($values['profile_id']))
        {
            $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
            $categories_model->setState('filter_enabled', '1');
            $categories_model->setState('order', 'tbl.ordering');
            $categories_model->setState('filter_profile', $values['profile_id']);
            if ($categories = $categories_model->getList())
            {
                foreach ($categories as $category)
                {
                    $fields_model = Ambra::getClass("AmbraModelFields", "models.fields");
                    $fields_model->setState('filter_enabled', '1');
                    $fields_model->setState('order', 'tbl.ordering');
                    $fields_model->setState('filter_category', $category->category_id);
                    $fields_model->setState('filter_required', '1');
                    if ($required_fields = $fields_model->getList())
                    {
                        foreach ($required_fields as $field)
                        {
                            $db_fieldname = $field->db_fieldname;
                            if (empty($values['userdata'][$db_fieldname]))
                            {
                                $response['error'] = '1';
                                $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage(  $field->field_name . " Required" );
                                echo ( json_encode( $response ) );
                                return;
                            }
                        }
                    }
                }
            }
        }
        
        // fire validate method for plugins
        $results = array();
        $dispatcher =& JDispatcher::getInstance();
        $results = $dispatcher->trigger( "onGetRegistrationFormVerify", array( $values ) );

        for ($i=0; $i<count($results); $i++)
        {
            $result = $results[$i];
            if (!empty($result->error))
            {
                $response['msg'] = $helper->generateMessage( $result->message );
                $response['error'] = '1';
            }
            else
            {
                // if here, all is OK
                $response['error'] = '0';
            }
        }        
        
        // if all is good, return ok
      echo json_encode( $response );
        return;
    }
    
    /**
     * Saves the registration form
     * and fires the post-save plugin event
     * 
     * @see ambra/admin/AmbraController#save()
     */
    function save()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );
        
        $redirect = JRoute::_( "index.php?option=com_ambra&view=registration" );
         
        $values = JRequest::get('post');
        
        // verify fields are present
        $helper = Ambra::getClass( "AmbraHelperBase", 'helpers._base' );
        // check username
        $username = $values['username'];
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        if ($checker->usernameExists($username)) 
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Username already exists" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // check email
        $email = $values['email'];
        if ($checker->emailExists($email))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Email already exists" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        if (!$checker->isEmailAddress($email))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Invalid Email Address" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
                
        // check password
        $password = $values['password'];
        
        // check password2
        $password2 = $values['password2'];
        
        $checker = Ambra::getClass( "AmbraHelperPassword", 'helpers.password' );
        $checker->setConfig();
        
        $message = "";        
        if (!$checker->checkPassword($password, $username)) 
        {
            // error encountered            
            foreach ($checker->errorMsgArray as $error) 
            {
                $message .= "<li>".JText::_( $error )."</li>" ;
            }
            $this->messagetype  = 'notice';
            $this->message      = $message;
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        if (empty($password) || empty($password2) || ($password != $password2) )
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Passwords Did Not Match" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // check custom fields
        if (!empty($values['profile_id']))
        {
            $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
            $categories_model->setState('filter_enabled', '1');
            $categories_model->setState('order', 'tbl.ordering');
            $categories_model->setState('filter_profile', $values['profile_id']);
            if ($categories = $categories_model->getList())
            {
                foreach ($categories as $category)
                {
                    $fields_model = Ambra::getClass("AmbraModelFields", "models.fields");
                    $fields_model->setState('filter_enabled', '1');
                    $fields_model->setState('order', 'tbl.ordering');
                    $fields_model->setState('filter_category', $category->category_id);
                    $fields_model->setState('filter_required', '1');
                    if ($required_fields = $fields_model->getList())
                    {
                        foreach ($required_fields as $field)
                        {
                            $db_fieldname = $field->db_fieldname;
                            if (empty($values['userdata'][$db_fieldname]))
                            {
                                $this->messagetype  = 'notice';
                                $this->message      = JText::_( $field->field_name . " Required" );
                                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                                return;
                            }
                        }
                    }
                }
            }
        }
        
        // fire validate method for plugins
        $results = array();
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger( "onGetRegistrationFormVerify", array( $values ) );

        for ($i=0; $i<count($results); $i++)
        {
            $result = $results[$i];
            if (!empty($result->error))
            {
                $this->messagetype  = 'notice';
                $this->message      = $result->message;
                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                return;
            }
        }
        
        // if here, then all is OK
        // so perform save registration
        $userHelper = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        if (!$row = $userHelper->createNewUser( $values, false ) ) 
        {
            $this->messagetype  = 'notice';
            $this->message      = $userHelper->getError();
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        $row->_isNew = true;
        
        $this->values = $values;
        $this->row = $row;
        
        if (!$this->saveUserdata())
        {
            JError::raiseNotice('saveUserdata', $this->userdata->getError());
        }
        if (!$this->saveAvatar())
        {
            JError::raiseNotice('saveAvatar', $this->avatar->getError());
        }
        if (!$this->saveIntegrations())
        {
            JError::raiseNotice('saveIntegrations', $this->integrations->getError());
        }
        
        // fire plugin event after save
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger( 'onAfterSaveSuccessUser', array( $row ) );

        // if there is a return URL base64encoded, then redirect to there
        if ($return = JRequest::getVar('return', '', 'method', 'base64')) 
        {
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) 
            {
                $return = '';
            }
        }
        
        // TODO Check the parameters for the return url set by admin
        $returnFromParams = false;
        jimport('joomla.application.component.helper');
        // display message about email activation if its required
        $usersConfig = JComponentHelper::getParams( 'com_users' ); // TODO Make these Ambra parameters
        $useractivation = $usersConfig->get( 'useractivation' );

        $returnFromParams = Ambra::getInstance()->get('registration_redirect_url', 'NULL');
        $registration_redirect = Ambra::getInstance()->get('registration_redirect', 'NULL'); 
        // $useractivation = Ambra::getInstance()->get('require_useractivation', '1');
        if ($useractivation == '1')
        {
            // set the message to display about email activation if its required
            // and the redirect (if no redirect exists, which allows redirect to override the user account activation)
             if (empty($return))
             {
                // set the return/redirect target to be the page where account authentication happens
                 $redirect = Ambra::getInstance()->get('registration_redirect_url','');
                         if (empty($return))
                     {
                        $redirect = DSCRoute::_( "index.php?option=com_ambra&view=registration" );
                    }
             }
                else
             {
                $redirect = $return;
             }
        }
            else
        {
            // login the user if no activation by email required
            $userHelper->login( $values );
            
            // set the return url
            if (!empty($return))
            {
                // 1. if present, then to the return url in the request
                $redirect = $return;
            }
                elseif ($returnFromParams)
            {
                if($registration_redirect){
                $redirect = $returnFromParams;
                }
                // 2. if no return in request, then to the url set in params (same page/specific itemid)            
            }
                else
            {
                //  if all else fails, redirect to profile page
                $redirect = JRoute::_( "index.php?option=com_ambra&view=users" );
            }
        }
        
        // redirect to wherever is set in redirect
        if ( $useractivation == 1 ) {
            $message  = JText::_( 'REG_COMPLETE_ACTIVATE' );
        } else {
            $message = JText::_( 'REG_COMPLETE' );
        }

    
        $this->setRedirect( $redirect, $message );
        
    }
    
    /**
     * Save all the custom fields 
     * from the registration form
     * 
     * @param $values
     * @return unknown_type
     */
    function saveUserdata()
    {
        $success = true;
        
        $values = $this->values;
        $row = $this->row;

        // save profile_id
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
        $userdata = JTable::getInstance('Userdata', 'AmbraTable');
        $userdata->load( array( 'user_id'=>$row->id ) );
        foreach ($values['userdata'] as $name =>$data)
        {         
            if(is_array($data)){
            $newValue=implode(",",$data);
            $values['userdata'][$name]=$newValue;           
        
        }
        }   
        foreach($values['userdata'] as $profile=>$val)
         {
          $values['userdata'][$profile]=$val;
        
          if(($profile=='profile_twitter'||$profile=='profile_facebook'||$profile=='profile_youtube'||$profile=='profile_linkedin') && ($values['userdata'][$profile]!=""))
          {         
            $profileid=explode("/",$values['userdata'][$profile]);
            if($profileid[0]!="http:")
            {               
                $val="http://".$val;
                $values['userdata'][$profile]=$val;                                 
            } 
            
          }      
                             
        }          
        
        // save all custom fields
        $userdata->bind( @$values['userdata'] );
        $userdata->user_id = $row->id;
        $userdata->profile_id = $values['profile_id'];
        if (!$userdata->save())
        {
            // TODO What to do if extra userdata doesn't save?
            $success = false;
        }
        
        return $success;
    }
    
    /**
     * Save the avatar 
     * 
     * @param $values
     * @return unknown_type
     */
    function saveAvatar()
    {
        $success = true;
        
        $values = $this->values;
        $row = $this->row;
        $fieldname = 'avatar';

        $userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
        if (!empty($userfile['size']))
        {
            $this->avatar = new JObject();
            
            
            Ambra::load('AmbraImage','library.image');
            
            $upload = new AmbraImage();
            
            // handle upload creates upload object properties
            $upload->handleUpload( $fieldname );
            // then save image to appropriate folder
            $upload->setDirectory( Ambra::getPath('avatars') );

            $valid_extensions = array( 'png', 'jpg', 'jpeg', 'gif' );
            $extension = strtolower( $upload->getExtension() );
            if (!in_array($extension, $valid_extensions))
            {
                $this->avatar->setError( 'Invalid File Type' );
                return false;
            }
                        
            // Do the real upload!
            if (!$upload->upload())
            {
                $this->avatar->setError( $upload->getError() );
                return false;  
            }

            if ($avatar = AmbraHelperUser::getAvatarFilename( $row->id ))
            {
                $storage_folder = Ambra::getPath( 'avatars' );
                JFile::delete( $storage_folder.'/'.$avatar );
            }
            
            // move the file            
            $extension = $upload->getExtension();
            $new_name = $row->id.'.'.$extension;
            $new_full_path = Ambra::getPath('avatars').'/'.$new_name;
            if (!JFile::move($upload->full_path, $new_full_path))
            {
                // error is handled by the jfile
                return false;
            }
            FB::log($row->id);

            if ( Ambra::getClass( "AmbraHelperPoint", "helpers.point" )->createLogEntry( $row->id, 'com_ambra', 'onAfterUploadAvatar' ))
            {
                JFactory::getApplication()->enqueueMessage( $helper->getError() );
            }
        }
        
        return $success;
    }
    
    /**
     * Save all the 3pd integrations 
     * from the registration form
     * 
     * @param $values
     * @return unknown_type
     */
    function saveIntegrations()
    {
        $success = true;
        $values = $this->values;
        $row = $this->row;

        $this->integrations = new JObject();
        $this->integration_errors = array();
        
        // TODO foreach integration, such as ambrasubs, or juga 
        // save its settings from registraion
        
        $this->saveAmigos();
        $this->savePhplist();
        $this->saveAllchimp();
        $this->saveCampaignMonitor();
        
        if (!empty($this->integration_errors))
        {
            $this->integrations->setError( implode( '<br/>', $this->integration_errors ) );            
        }
        
        return $success;
    }
    
    /**
     * If Amigos is installed
     * save integration
     */
    function saveAmigos()
    {
        $values = $this->values;
        $row = $this->row;
        
        // if amigos is installed, save the registration form option
        if ( Ambra::getClass( "AmbraHelperAmigos", 'helpers.amigos' )->isInstalled() ) 
        {
            // amigos_autoregister
            $amigos_autoregister = JRequest::getVar('amigos_autoregister');
            if (!empty($amigos_autoregister))
            {
                JLoader::register( "AmigosTableAccounts", JPATH_ADMINISTRATOR."/components/com_amigos/tables/accounts.php" );
                JLoader::register( "AmigosTableLogs", JPATH_ADMINISTRATOR."/components/com_amigos/tables/logs.php" );
                JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_amigos/tables' );
                $amigosAccount = JTable::getInstance('Accounts', 'AmigosTable');
                $amigosAccount->name = $row->name;
                $amigosAccount->email = $row->email;
                $amigosAccount->userid = $row->id; 
                
                // get the sessionid from the session table
                $session = JFactory::getSession();
                $sessionid = $session->getId();
                // get the amigos session log        
                $logBySession = JTable::getInstance('Logs', 'AmigosTable');
                $logBySession->load( array('sessionid'=>$sessionid) );
                if (!empty($logBySession->id))
                {
                    // update the referred_by field
                    $referrer = JTable::getInstance('Accounts', 'AmigosTable');
                    $referrer->load($logBySession->amigosid);
                    $amigosAccount->referred_by = $referrer->userid;                    
                }
                
                // auto-register the user
                $config = AmigosConfig::getInstance();
                if ($config->get('approve_new', '0'))
                {
                    $amigosAccount->approved = '1';
                    $amigosAccount->enabled = '1';
                }
                
                if ( $amigosAccount->save() ) 
                {
                    $amigosAccount->_isNew = true;

                    // load the plugins
                    JPluginHelper::importPlugin( 'amigos' );
                    // fire plugins
                    $dispatcher =& JDispatcher::getInstance();
                    $dispatcher->trigger( 'onAfterSaveAccounts', array( $amigosAccount ) );
        
                } else {
                    // save failed
                    $this->integration_errors[] = $amigosAccount->getError();
                    $success = false;
                }
            }
        }

        return null;
    }

    /**
     * 
     */
    function savePhplist()
    {
        $values = $this->values;
        $row = $this->row;
        
        // if phplist is installed, save the registration form option
        if ( Ambra::getClass( "AmbraHelperPhplist", 'helpers.phplist' )->isInstalled() ) 
        {
            // phplist_newsletters[]
            $phplist_newsletters = JRequest::getVar('phplist_newsletters', array(), 'post', 'array');
            if (!empty($phplist_newsletters))
            {
                JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_phplist/tables' );
                JLoader::import( 'com_phplist.helpers.user', JPATH_ADMINISTRATOR.'/components' );
                JLoader::import( 'com_phplist.helpers.subscription', JPATH_ADMINISTRATOR.'/components' );
                JLoader::import( 'com_phplist.helpers.attribute', JPATH_ADMINISTRATOR.'/components' );
                JLoader::import( 'com_phplist.helpers.newsletter', JPATH_ADMINISTRATOR.'/components' );
                
                foreach ($phplist_newsletters as $newsletter)
                {
                    // auto-register the user
                    $user = JFactory::getUser();
                    $phplistUser = PhplistHelperUser::getUser( $row->id, '1', 'foreignkey' );
                    if (empty($phplistUser))
                    {
                        $phplistUser = PhplistHelperUser::create( $row );
                    }
                    
                    // get phplist user details
                    $details = new JObject();
                    $details->userid = $phplistUser->id;
                    $details->listid = $newsletter;
                    
                    $switch = PhplistHelperSubscription::addUserTo( $details );
                }                
            }
        }
        return null;
    }

    /**
     * If Allchimp is installed
     * save integration
     */
    function saveAllchimp()
    {
        $values = $this->values;
        $row = $this->row;
        $errors = array();

        // if allchimp is installed, save the registration form option
        if ( Ambra::getClass( "AmbraHelperAllchimp", 'helpers.allchimp' )->isInstalled() ) 
        {
            // allchimp_newsletters[]
            $newsletters = JRequest::getVar('allchimp_newsletters', array(), 'post', 'array');
            if (!empty($newsletters))
            {
                JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_allchimp/tables' );
                $model = Ambra::getClass( "allChimpModelallChimp", 'models.allchimp', array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_allchimp' ));
                
                $newsletters_exist = $model->getMailList( $row->id );
                $model->addToList( $row->id, $newsletters );
                foreach ($newsletters as $newsletter)
                {
                    $newsletters_detail = $model->get_mail_list_id( $newsletter );
                    $result = $model->update_mailchimp_add_new( $newsletters_detail, $row, $post );
                    if ($result !='1' )
                    {
                        $this->integration_errors[] = $result; 
                    }
                }
            }
        }
        return null;
    }
    
    /**
     * If CM is installed
     * save integration
     */
    function saveCampaignMonitor()
    {
        $values = $this->values;
        $row = $this->row;
        $errors = array();

        // if allchimp is installed, save the registration form option
        if ( Ambra::getClass( "AmbraHelperCampaignMonitor", 'helpers.campaignmonitor' )->isInstalled() ) 
        {
            // allchimp_newsletters[]
            $newsletters = JRequest::getVar('campaignmonitor_newsletters', array(), 'post', 'array');
            if (!empty($newsletters))
            {
                $campaignmonitor_registration = Ambra::getInstance()->get('campaignmonitor_registration');
                $campaignmonitor_api_key      = Ambra::getInstance()->get('campaignmonitor_api_key');
                $campaignmonitor_listid       = Ambra::getInstance()->get('campaignmonitor_listid');
                
                Ambra::load( 'CampaignMonitor', 'library.campaignmonitor.campaignmonitor' );
                                
                $cm = new CampaignMonitor( $campaignmonitor_api_key, null, null, $campaignmonitor_listid, 'soap' );
                $cm->subscriberAdd( $row->email, $row->name );
                $response = CMBase::xml2array( $cm->debug_response, '/soap:Envelope/soap:Body/Subscriber.AddResponse/Subscriber.AddResult' );
    
                if ( in_array( $response['Code'], array( '1', '100', '101', '204' ) ) ) 
                {
                    switch( $response['Code'] ) 
                    {
                        case '1':
                            $this->integration_errors[] = JText::_( 'Invalid Email Address' );
                            break;
                        case '100':
                            $this->integration_errors[] = JText::_( 'Invalid API Key' );
                            break;
                        case '101':
                            $this->integration_errors[] = JText::_( 'Invalid Newsletter ID' );
                            break;
                        case '204':
                            $this->integration_errors[] = JText::_( 'Address in suppression list' );
                            break;
                    }
                }
            }
        }
        return null;
    }
    
    /**
     * 
     * @return 
     */
    function activate()
    {
        $mainframe = JFactory::getApplication();

        // Initialize some variables
        $db         =& JFactory::getDBO();
        $user       =& JFactory::getUser();
        $document   =& JFactory::getDocument();
        $pathway    =& $mainframe->getPathWay();

        $ambraConfig = &JComponentHelper::getParams( 'com_ambra' );
        $userActivation         = $ambraConfig->get('useractivation');
        $allowUserRegistration  = $ambraConfig->get('allowUserRegistration');

        // Check to see if they're logged in, because they don't need activating!
        if ($user->get('id')) {
            // They're already logged in, so redirect them to the home page
            $mainframe->redirect( 'index.php' );
        }

        if ($allowUserRegistration == '0' || $userActivation == '0') {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        $redirect = JRoute::_( "index.php?option=com_ambra&view=login" );

        $message = new stdClass();

        // Do we even have an activation string?
        $activation = JRequest::getVar('activation', '', '', 'alnum' );
        $activation = $db->getEscaped( $activation );

        if (empty( $activation ))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "REG_ACTIVATE_NOT_FOUND" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
            //                        
            //            // Page Title
            //            $document->setTitle( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ) );
            //            // Breadcrumb
            //            $pathway->addItem( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ));
            //            // TODO Redirect and display message? or just display here
            //            $message->title = JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' );
            //            $message->text = JText::_( 'REG_ACTIVATE_NOT_FOUND' );
            //            //$view->assign('message', $message);
            //            //$view->display('message');
            //            echo "<h3>".$message->title."</h3>";
            //            echo $message->text;
            //            return;
        }

        // Lets activate this user
        jimport('joomla.user.helper');
        if (JUserHelper::activateUser($activation))
        {
            $this->messagetype  = 'message';
            $this->message      = JText::_( "REG_ACTIVATE_COMPLETE" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
            //            // Page Title
            //            $document->setTitle( JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' ) );
            //            // Breadcrumb
            //            $pathway->addItem( JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' ));
            //
            //            $message->title = JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' );
            //            $message->text = JText::_( 'REG_ACTIVATE_COMPLETE' );
        }
            else
        {
            $this->messagetype  = 'message';
            $this->message      = JText::_( "REG_ACTIVATE_NOT_FOUND" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
            
            //            // Page Title
            //            $document->setTitle( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ) );
            //            // Breadcrumb
            //            $pathway->addItem( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ));
            //
            //            $message->title = JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' );
            //            $message->text = JText::_( 'REG_ACTIVATE_NOT_FOUND' );
        }
        
        return;
    }
}