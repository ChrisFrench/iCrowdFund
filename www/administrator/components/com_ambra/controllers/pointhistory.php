<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerPointHistory extends AmbraController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'pointhistory');
        $this->registerTask( 'pointhistory_enabled.enable', 'boolean' );
        $this->registerTask( 'pointhistory_enabled.disable', 'boolean' );
        $this->registerTask( 'batchedit', 'batchedit' );
        $this->registerTask( 'batchDelete', 'batchDelete' );
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

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.created_date', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'DESC', 'word');
        $state['filter_id_from']    = $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
        $state['filter_id_to']      = $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
        $state['filter_name']       = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
        $state['filter_enabled']    = $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
        $state['filter_date_from'] = $app->getUserStateFromRequest($ns.'date_from', 'filter_date_from', '', '');
        $state['filter_date_to'] = $app->getUserStateFromRequest($ns.'date_to', 'filter_date_to', '', '');
        $state['filter_datetype']   = $app->getUserStateFromRequest($ns.'datetype', 'filter_datetype', '', '');
        $state['filter_user']       = $app->getUserStateFromRequest($ns.'user', 'filter_user', '', '');
        $state['filter_points_from']    = $app->getUserStateFromRequest($ns.'points_from', 'filter_points_from', '', '');
        $state['filter_points_to']      = $app->getUserStateFromRequest($ns.'points_to', 'filter_points_to', '', '');
        $state['filter_rule']       = $app->getUserStateFromRequest($ns.'rule', 'filter_rule', '', '');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }
    function save()
	{
		$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
	    $copyrow = clone $row;		      
	    $expirationperiod = Ambra::getInstance()->get('expirationperiod', '');
        $expirationperiod = (int)$expirationperiod;
        $orgDate=date('Y-m-d');
        $cd = strtotime($orgDate);
		$retDate = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$expirationperiod,date('d',$cd),date('Y',$cd)));
		$row->bind( $_POST );		
		$comment = '( '.  $row->points . ' ' . JText::_('POINTS') . ' )';  
        $isNew = ($row->id < 1);
			if($isNew)
			{
			if($_POST['expire_date']=="")
			{
			$row->expire_date=$retDate;
			}
			JLoader::import( 'com_ambra.helpers.user', JPATH_ADMINISTRATOR.'/components' );
			AmbraHelperUser::sendMailPoints( $row, $comment );
			}
			else
			{	if($copyrow->points!=$row->points)
				{
					JLoader::import( 'com_ambra.helpers.user', JPATH_ADMINISTRATOR.'/components' );
					AmbraHelperUser::sendMailPoints($row);
				}
				
			}
			
        if ( $row->save($isNew) )
		{
			$model->setId( $row->id );
			$this->messagetype 	= 'message';
			$this->message  	= JText::_( 'Saved' );

			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
		}
			else
		{
			$this->messagetype 	= 'notice';
			$this->message 		= JText::_( 'Save Failed' )." - ".$row->getError();
		}

    	$redirect = "index.php?option=com_ambra";
    	$task = JRequest::getVar('task');
    	switch ($task)
    	{
    		case "savenew":
    			$redirect .= '&view='.$this->get('suffix').'&task=add';
    		  break;
    		case "apply":
    			$redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$model->getId();
    		  break;
    		case "save":
    		default:
    			$redirect .= "&view=".$this->get('suffix');
    		  break;
    	}

    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	function batchedit()
	{
		
		$cids = JRequest::getVar('cid', array(0), 'request', 'array');
		// select only the ids from cid
		$model 	= $this->getModel( $this->get('suffix') );
		$query = $model->getQuery();
		$query->where("tbl.pointhistory_id IN ('".implode( "', '", $cids )."') ");
		$model->setQuery( $query );
		// create view, assign model, and display
		$view = $this->getView( 'pointhistory', 'html' );
		$view->set( '_controller', 'pointhistory' );
		$view->set( '_view', 'pointhistory' );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->setLayout( 'batchedit' );
		$view->display();
	}
	
		function batchDelete()
	{

		
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
        if (!isset($this->redirect)) {
            $this->redirect = JRequest::getVar( 'return' )
                ? base64_decode( JRequest::getVar( 'return' ) )
                : 'index.php?option=com_ambra&view='.$this->get('suffix');
            $this->redirect = JRoute::_( $this->redirect, false );
        }

		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();

		$cids = JRequest::getVar('cid', array (0), 'request', 'array');
	
		$comments = JRequest::getVar('comment', array (0), 'request', 'array');
		$count=0;
		foreach (@$cids as $cid)
		{
			$comments=$comments[$count];
			$row->load($cid);
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
			else
			{
				JLoader::import( 'com_ambra.helpers.user', JPATH_ADMINISTRATOR.DS.'components' );
				AmbraHelperUser::sendMailPoints($row,$comments);
			}
			$count++;
		}

		if ($error)
		{
			$this->message = JText::_('Error') . " - " . $this->message;
		}
			else
		{
			$this->message = JText::_('Items Deleted');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}
    
}

?>