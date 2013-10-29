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

//Billets::load('BilletsField','library.field');

class BilletsControllerFields extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'fields');	
		$this->registerTask( 'published.enable', 'boolean' );
		$this->registerTask( 'published.disable', 'boolean' );
		$this->registerTask( 'listdisplayed.enable', 'boolean' );
		$this->registerTask( 'listdisplayed.disable', 'boolean' );
		$this->registerTask( 'required_enable', 'required_switch' );
		$this->registerTask( 'required_disable', 'required_switch' );
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'selected_disable', 'selected_switch' );
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

    	$state['filter_typeid'] 	= $app->getUserStateFromRequest($ns.'typeid', 'filter_typeid', '', '');
      	$state['filter_categoryid'] 	= $app->getUserStateFromRequest($ns.'categoryid', 'filter_categoryid', '', '');
      	$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
    
    function selectcategories()
    {
    	$this->set('suffix', 'categories');
    	$state = parent::_setModelState();
    	$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

      	$state['filter_parentid'] 	= $app->getUserStateFromRequest($ns.'parentid', 'filter_parentid', '', '');

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'fields' );
		$row->load( $id );
		
		$view	= $this->getView( 'fields', 'html' );
		$view->set( '_controller', 'fields' );
		$view->set( '_view', 'fields' );
		$view->set( '_action', "index.php?option=com_billets&view=fields&task=selectcategories&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setTask(true);
		$view->setLayout( 'selectcategories' );
		$view->display();
    }

	function save() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
		$params	= JRequest::get( 'post' );
		// description would contain HTML 
		$params['description']	= JRequest::getVar( 'description', '', '', '', 2 );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		$row->bind( $params );
		
		if ( $row->save() ) 
		{
			$model->setId( $row->id );
			$this->messagetype 	= 'message';
			$this->message  	= JText::_('COM_BILLETS_SAVED');
			
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." - ".$row->getError();
		}
		
    	$redirect = "index.php?option=com_billets";
    	$task = JRequest::getVar('task');
    	switch ($task)
    	{
    		case "savenew":
    			$redirect .= '&view='.$this->get('suffix').'&layout=form';
    		  break;
    		case "apply":
    			$redirect .= '&view='.$this->get('suffix').'&layout=form&id='.$model->getId();
    		  break;
    		case "save":
    		default:
    			$redirect .= "&view=".$this->get('suffix');
    		  break;
    	}

    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}

	/*
	 * 
	 */
	function selected_switch()
	{
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
				$obj = BilletsField::isCategory( $id, $cid, '1' );
			
				if (isset($obj->fieldid)) 
				{
					if (!BilletsField::removeFromCategory( $id, $cid ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsField::addToCategory( $id, $cid ))
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
						if (!BilletsField::addToCategory( $id, $cid ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsField::removeFromCategory( $id, $cid ))
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

		$redirect = "index.php?option=com_billets&view=fields&task=selectcategories&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/*
	 * 
	 */
	function required_switch()
	{
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
				$obj = BilletsField::isRequired( $id, $cid, '1' );
			
				if (isset($obj->required) && $obj->required == '1') 
				{
					if (!BilletsField::setRequired( $id, $cid, '0' ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsField::setRequired( $id, $cid, '1' ))
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
						if (!BilletsField::setRequired( $id, $cid, $enable ))
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

		$redirect = "index.php?option=com_billets&view=fields&task=selectcategories&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
}

?>