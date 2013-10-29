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
defined( '_JEXEC' ) or die( 'Restricted access' );

class BilletsControllerCategories extends BilletsController 
{
    var $message = "";
    var $messagetype = "";
	
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'categories');
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'selected_disable', 'selected_switch' );
		$this->registerTask( 'emailed_enable', 'emailed_switch' );
		$this->registerTask( 'emailed_disable', 'emailed_switch' );
		$this->registerTask( 'requiredfield_enable', 'requiredfield_switch' );
		$this->registerTask( 'requiredfield_disable', 'requiredfield_switch' );
		$this->registerTask( 'selectedfield_enable', 'selectedfield_switch' );
		$this->registerTask( 'selectedfield_disable', 'selectedfield_switch' );
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

      	$state['filter_parentid'] 	= $app->getUserStateFromRequest($ns.'parentid', 'filter_parentid', '', '');
		$state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.lft', 'cmd');
		
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
    
    /**
     * Rebuilds the tree using a recursive loop on the parentid
     * Useful after importing categories (from other shopping carts) 
     * Or for when tree becomes corrupted
     * 
     * @return unknown_type
     */
    function rebuild()
    {    	
    	JModel::getInstance('Categories', 'BilletsModel')->getTable()->updateParents();
    	JModel::getInstance('Categories', 'BilletsModel')->getTable()->rebuildTree();
    	
    	$redirect = "index.php?option=com_billets&view=".$this->get('suffix');
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    
    function selectusers()
    {
    	
		Billets::load('BilletsHelperManager', 'helpers.manager' );
		Billets::load('BilletsHelperCategory', 'helpers.category' );
    	$this->set('suffix', 'users');
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
		
		$view	= $this->getView( 'categories', 'html' );
		$view->set( '_controller', 'categories' );
		$view->set( '_view', 'categories' );
		$view->set( '_action', "index.php?option=com_billets&view=categories&task=selectusers&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectusers' );
		$view->setTask(true);
		$view->display();
    }
    
	/*
	 * 
	 */
	function selected_switch()
	{
		Billets::load('BilletsHelperManager', 'helpers.manager' );
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();	

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
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
				$this->messagetype 	= 'notice';
				$this->message 		= JText::_('COM_BILLETS_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			  break;
		}
		
		foreach (@$cids as $cid)
		{
			if ($switch)
			{
				$obj = BilletsHelperManager::isCategory( $cid, $id, '1' );
			
				if (isset($obj->id)) 
				{
					if (!BilletsHelperManager::removeFromCategory( $cid, $id ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsHelperManager::addToCategory( $cid, $id ))
					{
						$this->message .= $cid.', ';
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
						if (!BilletsHelperManager::addToCategory( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsHelperManager::removeFromCategory( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;						
						}
					  break;
				}
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
		}
			else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_billets&view=categories&task=selectusers&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function emailed_switch()
	{
		
		Billets::load('BilletsHelperManager','helpers.manager');
		
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();	

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
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
				$this->messagetype 	= 'notice';
				$this->message 		= JText::_('COM_BILLETS_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			  break;
		}
		
		foreach (@$cids as $cid)
		{
			if ($switch)
			{
				$obj = BilletsHelperManager::getsEmails( $cid, $id );

				if ($obj) 
				{
					if (!BilletsHelperManager::setGetsEmails( $cid, $id, '0' ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsHelperManager::setGetsEmails( $cid, $id, '1' ))
					{
						$this->message .= $cid.', ';
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
						if (!BilletsHelperManager::setGetsEmails( $cid, $id, $enable ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsHelperManager::setGetsEmails( $cid, $id, $enable ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;						
						}
					  break;
				}
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
		}
			else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_billets&view=categories&task=selectusers&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Gets the list of fields for a category (specified by 'id' in the URL) 
	 * and returns HTML, formatted for a ticket form. Intended to be used by Ajax
	 *  
	 * @return json_encoded HTML
	 */
	function getFields()
	{
		//Billets::load('BilletsHelperManager', 'helpers.manager' );	
		//JLoader::import( 'com_billets.library.json', JPATH_ADMINISTRATOR.DS.'components' );
		
		$ticketid 	= JRequest::getVar( 'ticketid', '', 'request', 'int' );
		$categoryid = JRequest::getVar( 'categoryid', '', 'request', 'int' );
		$html 		= "";
		$aJavascript= array();// holder for multiple javascript code
		// load the category object based on the id variable in request
		$category = $this->getModel( $this->get('suffix') )->getTable();
		$category->load( $categoryid );
		
		// if the id is invalid, return nothing
		if (!empty($category->id))
		{
			$model = $this->getModel( 'Tickets' );
			$model->setId( $ticketid );
			$ticket = $model->getItem();
			
			// get the category's fields, format the html, and return
			Billets::load('BilletsHelperCategory', 'helpers.categories' );
			Billets::load('BilletsField', 'library.field' );
			$fields = BilletsHelperCategory::getFields( $category->id );
			if (@$fields)
			{
				$html .= "<table class=\"admintable\">";	
			}
			
			foreach (@$fields as $field)
			{
				// autopopulate fields with values from ticket being viewed
				$name = $field->db_fieldname;
				$default = @$ticket->$name;
				// $hHtmlField could be a string or array depending upon the type
				// of field
				$hHtmlField	= BilletsField::display( $field, 'ticketdata', $default );
				if ( isset ( $hHtmlField['script'] ) && is_array( $hHtmlField ) && ( !empty( $hHtmlField['script'] ) ) ) {
					$aJavascript[]	=  $hHtmlField['script'];
				}

				$html .= "
				<tr>
					<td width=\"100\" align=\"right\" class=\"key\">
						<label for=\"$field->db_fieldname\">
						".JText::_( $field->title ).":
						</label>
					</td>
					<td>
						".((!is_array($hHtmlField)) ? $hHtmlField: $hHtmlField['msg'] )."
					</td>
				</tr>
				";
			}
			
			if (@$fields)
			{
				$html .= "</table>";
			}
		}
		
		// set response array
			$response 			= array();
			$response['msg'] 	= $html;
			$response['scripts']= $aJavascript;
		// encode and echo (need to echo to send back to browser)
			echo ( json_encode( $response ) );

		return;
	}
	
	/*
	 * Creates a popup where fields can be selected and associated with this category.
	 * Basically a reverse of the category popup on the fields screen
	 */
	function selectfields()
    {
    	Billets::load('BilletsField', 'library.field' );	
    	
    	$this->set('suffix', 'fields');
    	$state = parent::_setModelState();
    	$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

      	$state['filter_typeid'] 	= $app->getUserStateFromRequest($ns.'typeid', 'filter_typeid', '', '');
      	$state['filter_scope'] 		= 'fields';

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'categories' );
		$row->load( $id );
		
		$view	= $this->getView( 'categories', 'html' );
		$view->set( '_controller', 'categories' );
		$view->set( '_view', 'categories' );
		$view->set( '_action', "index.php?option=com_billets&view=categories&task=selectfields&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setTask(true);
		$view->setLayout( 'selectfields' );
		$view->display();
    }
	
	/*
	 * 
	 */
	function selectedfield_switch()
	{
		Billets::load('BilletsField', 'library.field' );
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();	

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
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
				$this->messagetype 	= 'notice';
				$this->message 		= JText::_('COM_BILLETS_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			  break;
		}
		
		foreach (@$cids as $cid)
		{
			if ($switch)
			{
				$obj = BilletsField::isCategory( $cid, $id, '1' );
			
				if (isset($obj->fieldid)) 
				{
					if (!BilletsField::removeFromCategory( $cid, $id ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsField::addToCategory( $cid, $id ))
					{
						$this->message .= $cid.', ';
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
						if (!BilletsField::addToCategory( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsField::removeFromCategory( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;						
						}
					  break;
				}
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
		}
			else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_billets&view=categories&task=selectfields&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/*
	 * 
	 */
	function requiredfield_switch()
	{
	 Billets::load('BilletsField', 'library.field' );
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();	

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
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
				$this->messagetype 	= 'notice';
				$this->message 		= JText::_('COM_BILLETS_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			  break;
		}
		
		foreach (@$cids as $cid)
		{
			if ($switch)
			{
				$obj = BilletsField::isRequired( $cid, $id, '1' );
			
				if (isset($obj->required) && $obj->required == '1') 
				{
					if (!BilletsField::setRequired( $cid, $id, '0' ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsField::setRequired( $cid, $id, '1' ))
					{
						$this->message .= $cid.', ';
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
					case "0":
					default:
						if (!BilletsField::setRequired( $cid, $id, $enable ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;						
						}
					  break;
				}
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
		}
			else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_billets&view=categories&task=selectfields&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
}

?>