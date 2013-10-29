<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerUsers extends AmbraController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'users');
	}

    /**
     * Sets the model's state
     * 
     * @return array()
     */
    function _setModelState()
    {
        $state = parent::_setModelState();      
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.registerDate', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'DESC', 'word');
        $state['filter_id_from']    = $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
        $state['filter_id_to']      = $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
        $state['filter_flex']         = $app->getUserStateFromRequest($ns.'flex', 'filter_flex', '', '');
        $state['filter_username']         = $app->getUserStateFromRequest($ns.'username', 'filter_username', '', '');
        $state['filter_email']         = $app->getUserStateFromRequest($ns.'email', 'filter_email', '', '');
        $state['filter_online']   = $app->getUserStateFromRequest($ns.'online', 'filter_online', '', '');
        $state['filter_profile']         = $app->getUserStateFromRequest($ns.'profile', 'filter_profile', '', '');
        $state['filter_date_from'] = $app->getUserStateFromRequest($ns.'date_from', 'filter_date_from', '', '');
        $state['filter_date_to'] = $app->getUserStateFromRequest($ns.'date_to', 'filter_date_to', '', '');
        $state['filter_datetype']   = $app->getUserStateFromRequest($ns.'datetype', 'filter_datetype', '', '');
        $state['filter_points_from']    = $app->getUserStateFromRequest($ns.'points_from', 'filter_points_from', '', '');
        $state['filter_points_to']      = $app->getUserStateFromRequest($ns.'points_to', 'filter_points_to', '', '');
        $state['filter_pointtype']   = $app->getUserStateFromRequest($ns.'pointtype', 'filter_pointtype', '', '');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }
    
    /**
     * Displays a user profile page
     * @see ambra/site/AmbraController#display($cachable)
     */
    function view($cachable=false, $urlparams = false)
    {
        $model  = $this->getModel( $this->get('suffix') );
        $this->_setModelState();
        
        $id = $model->getId();
        if (empty($id))
        {
            $id = JFactory::getUser()->id;
        }
        $model->setId($id);
        
        $view = $this->getView( 'users', 'html' );
        $view->set( '_controller', 'users' );
        $view->set( '_view', 'users' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->assign( 'row', JFactory::getUser( $id ) );
        $view->setModel( $model, true );
        $view->setLayout( 'view' );
        
        $max_points_per_day = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPointsPerDay( JFactory::getUser( $id )->id );
        if ((int) $max_points_per_day == '-1')
        {
            $max_points_per_day = JText::_( "Unlimited" );
        }
        $view->assign( 'max_points_per_day', $max_points_per_day );

        // get the points sum for today
        $pointhistory_today = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getTodayPoints( JFactory::getUser( $id )->id );
        $view->assign('pointhistory_today', $pointhistory_today );
        
        // get the userdata
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/models' );
        $model = JModel::getInstance('Users', 'AmbraModel');
        $model->setId( $id );
        $userdata = $model->getItem();
        $view->assign('userdata', $userdata );       
        
        // get the profile's custom fields
        $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
        $categories_model->setState('filter_enabled', '1');
        $categories_model->setState('order', 'tbl.ordering');
        $categories_model->setState('filter_profile', $userdata->profile_id);
        if ($categories = $categories_model->getList())
        {
            foreach ($categories as $category)
            {
                $fields_model = Ambra::getClass("AmbraModelFields", "models.fields");
                $fields_model->setState('filter_enabled', '1');
                $fields_model->setState('order', 'tbl.ordering');
                $fields_model->setState('filter_category', $category->category_id);
                if ($id != JFactory::getUser()->id)
                {
                    // if viewing someone else's profile, filter fields that aren't displayed
                    $fields_model->setState('filter_profiledisplayed', '1');
                }
                $category->fields = $fields_model->getList();
            }
        }
        if (empty($categories)) { $categories = array(); }
        $view->assign('categories', $categories );
        
        // get plugins & their data
        $filtered_sliders = array();
        $filtered_tabs = array();
        $filtered = array();
        $items = Ambra::getClass( "AmbraTools", 'library.tools' )->getPlugins();
        for ($i=0; $i < count($items); $i++) 
        {
            $item = &$items[$i];
            // Check if they have an onListConfigAmbra event
            if (AmbraTools::hasEvent( $item, 'onViewProfile' )) {
                // add item to filtered array
                $filtered[] = $item;
            } elseif (AmbraTools::hasEvent( $item, 'onViewProfileTabs' )) {
                // add item to filtered array
                $filtered_tabs[] = $item;
            } elseif (AmbraTools::hasEvent( $item, 'onViewProfileSliders' )) {
                // add item to filtered array
                $filtered_sliders[] = $item;
            }
        }
        $items      = $filtered;
        $items_tabs = $filtered_tabs;
        $items_sliders = $filtered_sliders;
        
        // Assign variables
        $view->assign( 'items', $items );
        $view->assign( 'items_tabs', $items_tabs );
        $view->assign( 'items_sliders', $items_sliders );
        
        $dispatcher =& JDispatcher::getInstance();

        ob_start();
        $dispatcher->trigger( 'onBeforeDisplayUser', array( JFactory::getUser( $id ) ) );
        $view->assign( 'onBeforeDisplayUser', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onDisplayUserRightColumn', array( JFactory::getUser( $id ) ) );
        $view->assign( 'onDisplayUserRightColumn', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterDisplayUser', array( JFactory::getUser( $id ) ) );
        $view->assign( 'onAfterDisplayUser', ob_get_contents() );
        ob_end_clean();
        
        $view->display();
       
        $this->footer();  
        
        
    }
    

    /**
     * Displays a user profile edit form
     * @see ambra/site/AmbraController#display($cachable)
     */
    function edit($cachable=false, $urlparams = false)
    {
        $model  = $this->getModel( $this->get('suffix') );
        $this->_setModelState();
        $row = $model->getTable();
        
        $id = $model->getId();
        $row->load( $id );
        
        if (!Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->canEdit( JFactory::getUser(), $row ))
        {
            $this->message = JText::_( "Access Denied" );
            $this->messagetype = 'notice'; 
            $redirect = JRoute::_( "index.php?option=com_ambra&view=users" ); 
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        $view = $this->getView( 'users', 'html' );
        $view->set( '_controller', 'users' );
        $view->set( '_view', 'users' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->assign( 'row', $row );
        $view->setModel( $model, true );
        $view->setLayout( 'form' );
        
        // get the userdata
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/tables' );
        $userdata = JTable::getInstance('Userdata', 'AmbraTable');
        $userdata->load( array( 'user_id'=>$id ) );
        $view->assign('userdata', $userdata );        
        
        if (!empty($userdata->profile_id))
        {
            // get the profile's custom fields
            $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
            $categories_model->setState('filter_enabled', '1');
            $categories_model->setState('order', 'tbl.ordering');
            $categories_model->setState('filter_profile', $userdata->profile_id);
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
        }
        if (empty($categories)) { $categories = array(); }
        $view->assign('categories', $categories );
                
        // get plugins & their data
        $filtered_sliders = array();
        $filtered_tabs = array();
        $filtered = array();
        $items = Ambra::getClass( "AmbraTools", 'library.tools' )->getPlugins();
        for ($i=0; $i < count($items); $i++) 
        {
            $item = &$items[$i];
            // Check if they have an onListConfigAmbra event
            if (AmbraTools::hasEvent( $item, 'onEditProfile' )) {
                // add item to filtered array
                $filtered[] = $item;
            } elseif (AmbraTools::hasEvent( $item, 'onEditProfileTabs' )) {
                // add item to filtered array
                $filtered_tabs[] = $item;
            } elseif (AmbraTools::hasEvent( $item, 'onEditProfileSliders' )) {
                // add item to filtered array
                $filtered_sliders[] = $item;
            }
        }
        $items      = $filtered;
        $items_tabs = $filtered_tabs;
        $items_sliders = $filtered_sliders;
        
        // Assign variables
        $view->assign( 'items', $items );
        $view->assign( 'items_tabs', $items_tabs );
        $view->assign( 'items_sliders', $items_sliders );
        
        $dispatcher =& JDispatcher::getInstance();

        ob_start();
        $dispatcher->trigger( 'onBeforeEditUser', array( $row ) );
        $view->assign( 'onBeforeEditUser', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onEditUserRightColumn', array( $row ) );
        $view->assign( 'onEditUserRightColumn', ob_get_contents() );
        ob_end_clean();
        
        ob_start();
        $dispatcher->trigger( 'onAfterEditUser', array( $row ) );
        $view->assign( 'onAfterEditUser', ob_get_contents() );
        ob_end_clean();
        
        $view->display( );
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
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        // convert elements to array that can be binded 
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );  
        $values = AmbraHelperBase::elementsToArray( $elements );

        $username = $values['username'];
        $current = JFactory::getUser()->username;
        
        if ($username == $current)
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Username Unchanged", 'success' );
            echo ( json_encode( $response ) );
            return;
        }

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

        echo ( json_encode( $response ) );
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
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $email = $values['email'];
        $current = JFactory::getUser()->email;
        
        if ($email == $current)
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Email Unchanged", 'success' );
            echo ( json_encode( $response ) );
            return;
        }

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

        echo ( json_encode( $response ) );
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
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $password = $values['password'];
        $username = empty( $values['username'] ) ? '' : $values['username'];

        if (empty($password))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Password Unchanged", 'success' );
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

        echo ( json_encode( $response ) );
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
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $password = $values['password'];
        $password2 = $values['password2'];

        if (empty($password))
        {
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->validationMessage( "Password Unchanged", 'success' );
            echo ( json_encode( $response ) );
            return;
        }
        
        if (!empty($password) && empty($password2))
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

        echo ( json_encode( $response ) );
        return;
    }
    
    /**
     * Updates form fields based on the selected profile type
     * @return unknown_type
     */
    function changeProfileType()
    {
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        // convert elements to array that can be binded             
        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        $values = AmbraHelperBase::elementsToArray( $elements );

        $this->values = $values;
        
        // now get the userdata form
        $html = $this->getUserdataForm();
        
        $response['msg'] = $html;
        $response['error'] = '';

        echo ( json_encode( $response ) );
        return;
    }
    
    /**
     * Gets the html input fields 
     * for just the userdata portion of the form
     * 
     * @return string HTML 
     */
    function getUserdataForm()
    {
        // get the values array from the post
        $values = &$this->values;
        $id = (int) @$values['id']; 

        $model  = $this->getModel( $this->get('suffix') );
        $this->_setModelState();
        
        $view = $this->getView( 'users', 'html' );
        $view->set( '_controller', 'users' );
        $view->set( '_view', 'users' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->assign( 'row', JFactory::getUser( $id ) );
        $view->setModel( $model, true );
        $view->setLayout( 'form_userdata' );
        
        // get the userdata
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/tables' );
        $userdata = JTable::getInstance('Userdata', 'AmbraTable');
        $userdata->load( array( 'user_id'=>$id ) );
        $userdata->bind( @$values['userdata'] );
        $userdata->profile_id = $values['profile_id'];
        $view->assign('userdata', $userdata );
        
        if (!empty($userdata->profile_id))
        {
            // get the profile's custom fields
            $categories_model = Ambra::getClass("AmbraModelCategories", "models.categories");
            $categories_model->setState('filter_enabled', '1');
            $categories_model->setState('order', 'tbl.ordering');
            $categories_model->setState('filter_profile', $userdata->profile_id);
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
        }
        if (empty($categories)) { $categories = array(); }
        $view->assign('categories', $categories );
        
        ob_start();
        $view->display();
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
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
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        if (empty($elements))
        {
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Unable to Process Form" ); 
            echo ( json_encode( $response ) );
            return;
        }
            
        // convert elements to array that can be binded
        $values = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->elementsToArray( $elements );
        $id = (int) @$values['id'];
        
        $helper = Ambra::getClass( "AmbraHelperBase", 'helpers._base' );
        
        // check username
        $username = $values['username'];
        $username_current = JFactory::getUser( $id )->username;
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        if ($username != $username_current && $checker->usernameExists($username)) 
        {
            $response['error'] = '1';
            $response['msg'] = Ambra::getClass( "AmbraHelperBase", 'helpers._base' )->generateMessage( "Please fix username" );
            echo ( json_encode( $response ) );
            return;
        }
        
        // check email
        $email = $values['email'];
        $email_current = JFactory::getUser( $id )->email;
        if ($email != $email_current && $checker->emailExists($email) || !$checker->isEmailAddress($email))
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
        if (!empty($password) && !$checker->checkPassword($password, $username)) 
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
        
        if (!empty($password) && (empty($password2) || ($password != $password2)) )
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
        echo ( json_encode( $response ) );
        return;
    }
    
    /**
     * Saves the form
     * and fires the post-save plugin event
     * 
     * @see ambra/admin/AmbraController#save()
     */
    function save()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );
        
        $redirect = "index.php?option=com_ambra&view=users";
         
        $values = JRequest::get('post');
        $id = (int) @$values['id'];
                
        // verify fields are present
        $helper = Ambra::getClass( "AmbraHelperBase", 'helpers._base' );
        
        // check username
        $username = $values['username'];
        $username_current = JFactory::getUser( $id )->username;
        
        $checker = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
        if ($username != $username_current && $checker->usernameExists($username)) 
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Username already exists" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // check email
        $email = $values['email'];
        $email_current = JFactory::getUser( $id )->email;
        if ($email != $email_current && $checker->emailExists($email))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Email already exists" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        if ($email != $email_current && !$checker->isEmailAddress($email))
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
        if (!empty($password) && !$checker->checkPassword($password, $username)) 
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
        
        if (!empty($password) && (empty($password2) || ($password != $password2)) )
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
        $dispatcher =& JDispatcher::getInstance();
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
            
        if( empty($id) )
        {
        	$userHelper = Ambra::getClass( "AmbraHelperUser", 'helpers.user' );
	        if (!$row = $userHelper->createNewUser( $values ) ) 
	        {
	            $this->messagetype  = 'notice';
	            $this->message      = $userHelper->getError();
	            $this->setRedirect( $redirect, $this->message, $this->messagetype );
	            return;
	        }
	        
	        $row->_isNew = true;
        }
        else 
        {
	        // if here, then all is OK
	        // so perform normal save operation
	        $row = JUser::getInstance($id);
	        $row->_isNew = false;
	
	        // we don't want users to edit certain fields so we will unset them
	        unset($values['gid']);
	        unset($values['usertype']);
	        unset($values['registerDate']);
	        unset($values['activation']);
	        $row->bind( $values );
        }

        if ( $row->save() )
        {
            $this->messagetype  = 'message';
            $this->message      = JText::_( 'Profile Saved' );
            
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

            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterSaveSuccessUser', array( $row ) );
            
        }
            else
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( 'Save Failed' )." :: ".$row->getError();
        }

        // if there is a return URL base64encoded, then redirect to there
        if ($return = JRequest::getVar('return', '', 'method', 'base64')) 
        {
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) 
            {
                $return = '';
            }
            $redirect = $return;
        }
            else
        {
            $redirect = "index.php?option=com_ambra";
            $task = JRequest::getVar('task');
            switch ($task)
            {
                case "savenew":
                    $redirect .= '&view='.$this->get('suffix').'&task=add';
                  break;
                case "apply":
                    $redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$row->id;
                  break;
                case "save":
                default:
                    $redirect .= "&view=".$this->get('suffix');
                  break;
            }
    
            $redirect = JRoute::_( $redirect, false );
        }
        
        // redirect to wherever is set in redirect
        $this->setRedirect( $redirect );
    }
    
    /**
     * Save all the custom fields 
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
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/tables' );
        $userdata = JTable::getInstance('Userdata', 'AmbraTable');
        $userdata->load( array( 'user_id'=>$row->id ) );
        foreach ($values['userdata'] as $name =>$data)
        {         
        	if(is_array($data)){
        		$newValue=implode(",",$data);
        		$values['userdata'][$name]=$newValue;
        	}
        } 
        // save all custom fields
        $userdata->bind( $values['userdata'] );
        $userdata->user_id = $row->id;
        $userdata->profile_id = $values['profile_id'];
        $userdata->is_manual_approval = $values['is_manual_approval'];
        $userdata->points_current = $values['points_current'];
        $userdata->points_total = $values['points_total'];
        
        if (!$userdata->save())
        {
            // What to do if extra userdata doesn't save?
            $success = false;
        }
        	else 
        {
        	$dispatcher =& JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterSaveUserData', array( $userdata ) );
        }
        
        $this->userdata = $userdata;
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
            
            // Do the real upload!
            if (!$upload->upload())
            {
                $this->avatar->setError( $upload->getError() );
                return false;  
            }

            if ($avatar = AmbraHelperUser::getAvatarFilename( $row->id ))
            {
                $storage_folder = Ambra::getPath( 'avatars' );
                JFile::delete( $storage_folder.DS.$avatar );
            }
            
            // move the file            
            $extension = $upload->getExtension();
            $new_name = $row->id.'.'.$extension;
            $new_full_path = Ambra::getPath('avatars').DS.$new_name;
            if (!JFile::move($upload->full_path, $new_full_path))
            {
                // error is handled by the jfile
                return false;
            }
        }
        
        return $success;
    }
    
    /**
     * Save all the 3pd integrations 
     * 
     * @param $values
     * @return unknown_type
     */
    function saveIntegrations()
    {
        $success = true;
        
        $values = $this->values;
        $row = $this->row;
        
        // TODO foreach integration, such as phplist, ambrasubs, or amigos 
        // save its settings from registraion
        
        return $success;
    }
    
    /**
     * Deletes record(s) and redirects to default layout
     */
    function delete()
    {
        // we will disallow deleting from within Ambra for now
        // TODO Add this in later, with ACL checks 
        $this->messagetype  = '';
        $this->message      = JText::_( "Deleting Users Not Yet Enabled" ) . " :: " .JText::_( "Please Use Core User Manager" );
        $this->redirect = JRoute::_( "index.php?option=com_ambra&view=users", false );
        $this->setRedirect( $this->redirect, $this->message, $this->messagetype );
        
        //        $error = false;
        //        $this->messagetype  = '';
        //        $this->message      = '';
        //        if (!isset($this->redirect)) {
        //            $this->redirect = JRequest::getVar( 'return' )
        //                ? base64_decode( JRequest::getVar( 'return' ) )
        //                : 'index.php?option=com_ambra&view='.$this->get('suffix');
        //            $this->redirect = JRoute::_( $this->redirect, false );
        //        }
        //
        //        $model = $this->getModel($this->get('suffix'));
        //        $row = $model->getTable();
        //        
        //        JPluginHelper::importPlugin('user');
        //        $dispatcher = &JDispatcher::getInstance();
        //        
        //        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        //        foreach (@$cids as $cid)
        //        {
        //            $properties = $row->getProperties();
        //            $dispatcher->trigger('onBeforeDeleteUser', array($properties));
        //            
        //            if (!$row->delete($cid))
        //            {
        //                $this->message .= $row->getError();
        //                $this->messagetype = 'notice';
        //                $error = true;
        //            }
        //                else
        //            {
        //                $dispatcher->trigger('onAfterDeleteUser', array($properties, true, $this->getError()));
        //            }
        //        }
        //
        //        if ($error)
        //        {
        //            $this->message = JText::_('Error') . " - " . $this->message;
        //        }
        //            else
        //        {
        //            $this->message = JText::_('Items Deleted');
        //        }
        //
        //        $this->setRedirect( $this->redirect, $this->message, $this->messagetype );
    }    
}

?>