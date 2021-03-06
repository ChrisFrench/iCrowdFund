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

class AmbraControllerFields extends AmbraController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'fields');
		$this->registerTask( 'field_enabled.enable', 'boolean' );
		$this->registerTask( 'field_enabled.disable', 'boolean' );
		$this->registerTask( 'list_displayed.enable', 'boolean' );
		$this->registerTask( 'list_displayed.disable', 'boolean' );
        $this->registerTask( 'profile_displayed.enable', 'boolean' );
        $this->registerTask( 'profile_displayed.disable', 'boolean' );
        $this->registerTask( 'selected.enable', 'selected_switch' );
        $this->registerTask( 'selected.disable', 'selected_switch' );
        $this->registerTask( 'required.enable', 'required_switch' );
        $this->registerTask( 'required.disable', 'required_switch' );
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
    function _setModelState()
    {
    	$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();

    	$state['filter_name']       = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
    	$state['filter_typeid'] 	= $app->getUserStateFromRequest($ns.'typeid', 'filter_typeid', '', '');
      	$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
        $state['filter_category']    = $app->getUserStateFromRequest($ns.'category', 'filter_category', '', '');
        $state['filter_listdisplayed']    = $app->getUserStateFromRequest($ns.'listdisplayed', 'filter_listdisplayed', '', '');
        $state['filter_profiledisplayed']    = $app->getUserStateFromRequest($ns.'profiledisplayed', 'filter_profiledisplayed', '', '');
        
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
    function selectcategories()
    {
        $this->set('suffix', 'categories');
        $state = parent::_setModelState();
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $state['filter_parentid']   = $app->getUserStateFromRequest($ns.'parentid', 'filter_parentid', '', '');
        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.ordering', 'cmd');

        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        
        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $row = $model->getTable( 'fields' );
        $row->load( $id );
        
        $view   = $this->getView( 'fields', 'html' );
        $view->set( '_doTask', true );
        $view->set( '_controller', 'fields' );
        $view->set( '_view', 'fields' );
        $view->set( '_action', "index.php?option=com_ambra&controller=fields&task=selectcategories&tmpl=component&id=".$model->getId() );
        $view->setModel( $model, true );
        $view->assign( 'state', $model->getState() );
        $view->assign( 'row', $row );
        $view->setLayout( 'selectcategories' );
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
        $vals = explode('.', $task);
        
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
            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
            $keynames["field_id"] = $id;
            $keynames["category_id"] = $cid;
            $table->load( $keynames );
            if ($switch)
            {
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
                    $table->field_id = $id;
                    $table->category_id = $cid;
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
                        $table->field_id = $id;
                        $table->category_id = $cid;
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
            base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=fields&task=selectcategories&tmpl=component&id=".$id;
        $redirect = JRoute::_( $redirect, false );
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    
    /**
     * 
     * @return unknown_type
     */
    function required_switch()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
                
        $model = $this->getModel($this->get('suffix'));
        $row = $model->getTable();  

        $id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        $task = JRequest::getVar( 'task' );
        $vals = explode('.', $task);
        
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
            $table = JTable::getInstance('CategoryFields', 'AmbraTable');
            $keynames["field_id"] = $id;
            $keynames["category_id"] = $cid;
            $table->load( $keynames );
            $table->field_id = $id;
            $table->category_id = $cid;
            if (!empty($switch))
            {
                $enable = (!empty($table->required)) ? '0' : '1';
            }
            $table->required = $enable;
            if (!$table->save())
            {
                $this->message .= $cid.': '.$table->getError().'<br/>';
                $this->messagetype = 'notice';
                $error = true;                      
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
            base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_ambra&controller=fields&task=selectcategories&tmpl=component&id=".$id;
        $redirect = JRoute::_( $redirect, false );
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
}

?>