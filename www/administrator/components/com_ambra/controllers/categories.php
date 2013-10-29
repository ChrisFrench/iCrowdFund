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

class AmbraControllerCategories extends AmbraController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'categories');
        $this->registerTask( 'category_enabled.enable', 'boolean' );
        $this->registerTask( 'category_enabled.disable', 'boolean' );
        $this->registerTask( 'selected_enable', 'selected_switch' );
        $this->registerTask( 'selected_disable', 'selected_switch' );
        $this->registerTask( 'field_selected.enable', 'fieldselected_switch' );
        $this->registerTask( 'field_selected.disable', 'fieldselected_switch' );
        $this->registerTask( 'field_required.enable', 'fieldrequired_switch' );
        $this->registerTask( 'field_required.disable', 'fieldrequired_switch' );
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

        $state['filter_id_from']    = $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
        $state['filter_id_to']      = $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
        $state['filter_name']       = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
        $state['filter_enabled']    = $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
        $state['filter_field']    = $app->getUserStateFromRequest($ns.'field', 'filter_field', '', '');
        $state['filter_profile']    = $app->getUserStateFromRequest($ns.'profile', 'filter_profile', '', '');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }
    
    /**
     * Loads view for assigning to categories
     * 
     * @return unknown_type
     */
    function selectprofiles()
    {
        $this->set('suffix', 'profiles');
        $state = parent::_setModelState();
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        
        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $row = $model->getTable( 'categories' );
        $row->load( $id );
        
        $view   = $this->getView( 'categories', 'html' );
        $view->set( '_controller', 'categories' );
        $view->set( '_view', 'categories' );
        $view->set( '_action', "index.php?option=com_ambra&view=categories&task=selectprofiles&tmpl=component&id=".$model->getId() );
        $view->setModel( $model, true );
        $view->set( '_doTask', true );
        $view->assign( 'state', $model->getState() );
        $view->assign( 'row', $row );
        $view->setLayout( 'selectprofiles' );
        $view->display();
    }
    
    /**
     * 
     * @return unknown_type
     */
    function selected_switch()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
                
        $model = $this->getModel($this->get('suffix'));
        $row = $model->getTable();  

        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        $task = JRequest::getVar( 'task' );
        $vals = explode('_', $task);
        
        $field = $vals['0'];
        $action = $vals['1'];       
        
        switch (strtolower($action))
        {
            case "switch":
                $switch = '1';
              break;
            case "disable":
                $enable = '0';
                $switch = '0';
              break;
            case "enable":
                $enable = '1';
                $switch = '0';
              break;
            default:
                $this->messagetype  = 'notice';
                $this->message      = JText::_( "Invalid Task" );
                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                return;
              break;
        }
        
        $keynames = array();
        foreach (@$cids as $cid)
        {
            $table = JTable::getInstance('ProfileCategories', 'AmbraTable');
            $keynames["category_id"] = $id;
            $keynames["profile_id"] = $cid;
            $table->load( $keynames );
            if ($switch)
            {
                if (isset($table->profile_id)) 
                {
                    if (!$table->delete())
                    {
                        $this->message .= $cid.': '.$table->getError().'<br/>';
                        $this->messagetype = 'notice';
                        $error = true;
                    }
                } 
                    else 
                {
                    $table->category_id = $id;
                    $table->profile_id = $cid;
                    if (!$table->save())
                    {
                        $this->message .= $cid.': '.$table->getError().'<br/>';
                        $this->messagetype = 'notice';
                        $error = true;                      
                    }
                }
            }
                else
            {
                switch ($enable)
                {
                    case "1":
                        $table->category_id = $id;
                        $table->profile_id = $cid;
                        if (!$table->save())
                        {
                            $this->message .= $cid.': '.$table->getError().'<br/>';
                            $this->messagetype = 'notice';
                            $error = true;
                        }
                      break;
                    case "0":
                    default:
                        if (!$table->delete())
                        {
                            $this->message .= $cid.': '.$table->getError().'<br/>';
                            $this->messagetype = 'notice';
                            $error = true;                      
                        }
                      break;
                }
            }
        }
        
        if ($error)
        {
            $this->message = JText::_('Error') . ": " . $this->message;
        }
            else
        {
            $this->message = "";
        }
 
        $redirect = JRequest::getVar( 'return' ) ?  
            base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=categories&task=selectprofiles&tmpl=component&id=".$id;
        $redirect = JRoute::_( $redirect, false );
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    
    /**
     * Loads view for assigning to categories
     * 
     * @return unknown_type
     */
    function selectfields()
    {
        $this->set('suffix', 'fields');
        $state = parent::_setModelState();
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        
        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $row = $model->getTable( 'categories' );
        $row->load( $id );
        
        $view   = $this->getView( 'categories', 'html' );
        $view->set( '_controller', 'categories' );
        $view->set( '_view', 'categories' );
        $view->set( '_action', "index.php?option=com_ambra&view=categories&task=selectfields&tmpl=component&id=".$model->getId() );
        $view->setModel( $model, true );
        $view->assign( 'state', $model->getState() );
        $view->assign( 'row', $row );
        $view->setLayout( 'selectfields' );
        $view->display();
    }
    
    /**
     * 
     * @return unknown_type
     */
    function fieldselected_switch()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
                
        $model = $this->getModel($this->get('suffix'));
        $row = $model->getTable();  

        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        $task = JRequest::getVar( 'task' );
        
        if( count($cids) > 1)
        {
        $keynames = array();
	        foreach (@$cids as $cid)
	        {
	            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
	            $keynames["category_id"] = $id;
	            $keynames["field_id"] = $cid;
	            $table->load( $keynames );
	            
	                if (isset($table->field_id)) 
	                {
	                    if (!$table->delete())
	                    {
	                        $this->message .= $cid.': '.$table->getError().'<br/>';
	                        $this->messagetype = 'notice';
	                        $error = true;
	                    }
	                } 
	                    else 
	                {
	                    $table->category_id = $id;
	                    $table->field_id = $cid;
	                    if (!$table->save())
	                    {
	                        $this->message .= $cid.': '.$table->getError().'<br/>';
	                        $this->messagetype = 'notice';
	                        $error = true;                      
	                    }
	                }
	        }
        }
        else 
        {
	        $vals = explode('.', $task);
	        
	        $field = $vals['0'];
	        $action = $vals['1'];       
	        
	        switch (strtolower($action))
	        {
	            case "disable":
	                $enable = '0';
	                $switch = '0';
	              break;
	            case "enable":
	                $enable = '1';
	                $switch = '0';
	              break;
	            default:
	                $this->messagetype  = 'notice';
	                $this->message      = JText::_( "Invalid Task" );
	                $redirect = JRequest::getVar( 'return' ) ?  
            			base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=categories&task=selectfields&tmpl=component&id=".$id;
        			$redirect = JRoute::_( $redirect, false );
	                $this->setRedirect( $redirect, $this->message, $this->messagetype );
	                return;
	              break;
	        }
	        
	        $keynames = array();
	        foreach (@$cids as $cid)
	        {
	            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
	            $keynames["category_id"] = $id;
	            $keynames["field_id"] = $cid;
	            $table->load( $keynames );
	            
	            switch ($enable)
	            {
	                    case "1":
	                        $table->category_id = $id;
	                        $table->field_id = $cid;
	                        if (!$table->save())
	                        {
	                            $this->message .= $cid.': '.$table->getError().'<br/>';
	                            $this->messagetype = 'notice';
	                            $error = true;
	                        }
	                      break;
	                    case "0":
	                    default:
	                        if (!$table->delete())
	                        {
	                            $this->message .= $cid.': '.$table->getError().'<br/>';
	                            $this->messagetype = 'notice';
	                            $error = true;                      
	                        }
	                      break;
	                }
	            
	        }
        }
        
        if ($error)
        {
            $this->message = JText::_('Error') . ": " . $this->message;
        }
            else
        {
            $this->message = "";
        }
 
        $redirect = JRequest::getVar( 'return' ) ?  
            base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=categories&task=selectfields&tmpl=component&id=".$id;
        $redirect = JRoute::_( $redirect, false );
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    
    /**
     * 
     * @return unknown_type
     */
    function fieldrequired_switch()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
                
        $model = $this->getModel($this->get('suffix'));
        $row = $model->getTable();  

        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        $task = JRequest::getVar( 'task' );
        
        
        if( count($cids) > 1)
        {
        	$keynames = array();
	        foreach (@$cids as $cid)
	        {
	            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
	            $keynames["category_id"] = $id;
	            $keynames["field_id"] = $cid;
	            $table->load( $keynames );
	            $table->category_id = $id;
	            $table->field_id = $cid;
	            $enable = (!empty($table->required)) ? '0' : '1';
	            $table->required = $enable;
	            if (!$table->save())
	            {
	                $this->message .= $cid.': '.$table->getError().'<br/>';
	                $this->messagetype = 'notice';
	                $error = true;                      
	            }
	        }
        }
        else 
        {
        	$vals = explode('.', $task);
	        $field = $vals['0'];
	        $action = $vals['1'];       
	        
	        switch (strtolower($action))
	        {
	            case "disable":
	                $enable = '0';
	                $switch = '0';
	              break;
	            case "enable":
	                $enable = '1';
	                $switch = '0';
	              break;
	            default:
	                $this->messagetype  = 'notice';
	                $this->message      = JText::_( "Invalid Task" );
	                $redirect = JRequest::getVar( 'return' ) ?  
            			base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=categories&task=selectfields&tmpl=component&id=".$id;
        			$redirect = JRoute::_( $redirect, false );
	                $this->setRedirect( $redirect, $this->message, $this->messagetype );
	                return;
	              break;
	        }
	        
	        $keynames = array();
	        foreach (@$cids as $cid)
	        {
	            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
	            $keynames["category_id"] = $id;
	            $keynames["field_id"] = $cid;
	            $table->load( $keynames );
	            $table->category_id = $id;
	            $table->field_id = $cid;
	            $table->required = $enable;
	            if (!$table->save())
	            {
	                $this->message .= $cid.': '.$table->getError().'<br/>';
	                $this->messagetype = 'notice';
	                $error = true;                      
	            }
	        }
        }
        
        if ($error)
        {
            $this->message = JText::_('Error') . ": " . $this->message;
        }
            else
        {
            $this->message = "";
        }
 
        $redirect = JRequest::getVar( 'return' ) ?  
            base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=categories&task=selectfields&tmpl=component&id=".$id;
        $redirect = JRoute::_( $redirect, false );
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
}

?>