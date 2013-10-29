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
Billets::load('BilletsHelperManager','helpers.manager');	
class BilletsControllerUsers extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'users');
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'selected_disable', 'selected_switch' );
		$this->registerTask( 'emailed_enable', 'emailed_switch' );
		$this->registerTask( 'emailed_disable', 'emailed_switch' );
		$this->registerTask( 'limit_tickets_exclusion.enable', 'userdata_boolean' );
		$this->registerTask( 'limit_tickets_exclusion.disable', 'userdata_boolean' );
        $this->registerTask( 'limit_tickets.enable', 'userdata_boolean' );
        $this->registerTask( 'limit_tickets.disable', 'userdata_boolean' );
        $this->registerTask( 'limit_hours_exclusion.enable', 'userdata_boolean' );
		$this->registerTask( 'limit_hours_exclusion.disable', 'userdata_boolean' );
        $this->registerTask( 'limit_hours.enable', 'userdata_boolean' );
        $this->registerTask( 'limit_hours.disable', 'userdata_boolean' );
	}
	
	function display($cachable=false, $urlparams = false)
	{
	    $config = Billets::getInstance();
	    JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $umodel  = $this->getModel( $this->get('suffix') );
        $this->_setModelState();
        if ($list = $umodel->getList())
        {
            foreach ($list as $item)
            {
                if (empty($item->userdata_id))
                {
                    $userdata = JTable::getInstance('Userdata', 'BilletsTable');
                    $userdata->user_id = $item->id;
                    $userdata->limit_tickets = $config->get('limit_tickets_globally');
                    $userdata->ticket_max = $config->get('default_max_tickets');
                    $userdata->limit_hours = $config->get('limit_hours_globally');
                    $userdata->hour_max = $config->get('default_max_hours');
                    
                    $model = JModel::getInstance( 'Tickets', 'BilletsModel' );
                    $model->setState( 'select', 'COUNT(tbl.id)' );
                    $model->setState( 'filter_userid', $item->id );
                    $userdata->ticket_count = $model->getResult();
                    
                    $model = JModel::getInstance( 'Tickets', 'BilletsModel' );
                    $model->setState( 'select', 'SUM(tbl.hours_spent)' );
                    $model->setState( 'filter_userid', $item->id );
                    $userdata->hour_count = $model->getResult();
                    
                    $userdata->store();
                }
            }
        }
        $umodel->_list = array();
        
	    parent::display($cachable, $urlparams);
	}
	
    function selectcategories()
    {
    	$this->set('suffix', 'categories');
    	$state = parent::_setModelState();
    	$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

      	$state['filter_parentid'] 	= $app->getUserStateFromRequest($ns.'parentid', 'filter_parentid', '', '');
      	$state['filter_scope'] 		= 'categories';
      	$state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.lft', 'cmd');

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
		
		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'users' );
		$row->load( $id );
		
		$view	= $this->getView( 'users', 'html' );
		$view->set( '_controller', 'users' );
		$view->set( '_view', 'users' );
		$view->set( '_action', "index.php?option=com_billets&view=users&task=selectcategories&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectcategories' );
		$view->setTask( true );
		$view->display();
    }
    
	/**
	 * 
	 * @return unknown_type
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
				$obj = BilletsHelperManager::isCategory( $id, $cid, '1' );
			
				if (isset($obj->id)) 
				{
					if (!BilletsHelperManager::removeFromCategory( $id, $cid ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsHelperManager::addToCategory( $id, $cid ))
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
						if (!BilletsHelperManager::addToCategory( $id, $cid ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsHelperManager::removeFromCategory( $id, $cid ))
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

		$redirect = "index.php?option=com_billets&view=users&task=selectcategories&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function emailed_switch()
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
				$obj = BilletsHelperManager::getsEmails( $id, $cid );

				if ($obj) 
				{
					if (!BilletsHelperManager::setGetsEmails( $id, $cid, '0' ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsHelperManager::setGetsEmails( $id, $cid, '1' ))
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
						if (!BilletsHelperManager::setGetsEmails( $id, $cid, $enable ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsHelperManager::setGetsEmails( $id, $cid, $enable ))
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

		$redirect = "index.php?option=com_billets&view=users&task=selectcategories&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
    /*
     * 
     */
    function userdata_boolean()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
        $redirect = 'index.php?option=com_billets&view='.$this->get('suffix');
        $redirect = JRoute::_( $redirect, false );
                
        $model = $this->getModel($this->get('suffix'));
        $row = JTable::getInstance('Userdata', 'BilletsTable');  

        $cids = JRequest::getVar('cid', array (0), 'post', 'array');
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
                $this->message      = JText::_('COM_BILLETS_INVALID_TASK');
                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                return;
              break;
        }

        if ( !in_array( $field, array_keys( $row->getProperties() ) ) ) 
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_('COM_BILLETS_INVALID_FIELD').": {$field}";
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        foreach (@$cids as $cid)
        {
            $row->load( array('user_id'=>$cid) );

            if (empty($row->user_id))
            {
                $row->_isNew = true;
                $row->user_id = $cid;
            }
            
            switch ($switch)
            {
                case "1":
                    $row->$field = $row->$field ? '0' : '1';
                  break;
                case "0":
                default:
                    $row->$field = $enable;
                  break;
            }
            
            if ( !$row->save() ) 
            {
                $this->message .= $row->getError();
                $this->messagetype = 'notice';
                $error = true;
            }
        }
        
        if ($error)
        {
            $this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
        }
            else
        {
            $this->message = JText::_('COM_BILLETS_STATUS_CHANGED');
        }
        
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
}

?>