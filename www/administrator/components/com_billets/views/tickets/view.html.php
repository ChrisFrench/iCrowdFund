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
defined('_JEXEC') or die('Restricted access');

Billets::load( 'BilletsViewBase', 'views._base' );

class BilletsViewTickets extends BilletsViewBase 
{
	
	 /**
     * Gets layout vars for the view
     *
     * @return unknown_type
     */
    function getLayoutVars($tpl=null)
    {
        $layout = $this->getLayout();
		switch(strtolower($layout))
		{
			case "convert":
				$this->_convert($tpl);
			  break;
			case "merge":
				$this->_merge($tpl);
			  break;
			case "view":
				$this->_form($tpl);
			  break;
			case "form":
				JRequest::setVar('hidemainmenu', '1');
				$this->_form($tpl);
			  break;
			case "default":
			default:
				$this->_default($tpl);
			  break;
		}
    } 
	 
	 
	 
	 /**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl=null) 
	{
		
		
		parent::display($tpl);
		
		$layout = $this->getLayout();
		switch(strtolower($layout))
		{
			case "view":
				$model = $this->getModel();
				$row = $model->getItem();
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterViewTicket', array( $row, JFactory::getUser() ) );
			  break;
			case "form":
				$model = $this->getModel();
				$row = $model->getItem();
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterEditTicket', array( $row, JFactory::getUser() ) );
			  break;
			case "convert":
			case "merge":
			case "default":
			default:
			  break;
		}
	}

	function _viewToolbar( $isNew=null )
	{
		$model = JModel::getInstance( 'Tickets', 'BilletsModel' );
		$row = $model->getTable();
		$row->load( $model->getId() );
		
		JToolBarHelper::custom('merge', 'assign', 'assign', JText::_('COM_BILLETS_MERGE'), false);
		
		JToolBarHelper::custom('convert', 'forward', 'forward', JText::_('COM_BILLETS_CONVERT_TO_ARTICLE'), false);
		

		$surrounding = BilletsHelperTicket::getSurrounding( $model->getId() );
		if (!empty($surrounding['prev']))
		{
			JToolBarHelper::custom('prev', 'prev', 'prev', JText::_('COM_BILLETS_PREV'), false);
		}
		if (!empty($surrounding['next']))
		{
			JToolBarHelper::custom('next', 'next', 'next', JText::_('COM_BILLETS_NEXT'), false);	
		}
		
		JToolBarHelper::divider();
		
		// if not checkedout, enable delete & edit
//		if (JFactory::getUser()->id == @$row->checked_out)
//		{
			JToolBarHelper::custom( 'edit', 'edit', 'edit', JText::_('COM_BILLETS_EDIT'), false);
			JToolBarHelper::custom( 'remove', 'delete', 'delete', JText::_('COM_BILLETS_DELETE'), false);	
//		}
		parent::_viewToolbar($isNew);
	}
	
	function _defaultToolbar()
	{
		JToolBarHelper::custom('convert', 'forward', 'forward', JText::_('COM_BILLETS_CONVERT_TO_ARTICLE' ), true);
		JToolBarHelper::custom('unlockall', 'move', 'move', JText::_('COM_BILLETS_UNLOCK_ALL_TICKETS'), false);
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
	
	function _default($tpl=null)
	{
		parent::_default($tpl);

		$model = JModel::getInstance( 'Fields', 'BilletsModel' );
		$model->setState( 'order', 'tbl.ordering' );
		$model->setState( 'direction', 'ASC' );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_listdisplayed', '1');
		$fields = $model->getList();
		$this->assign( 'fields', $fields );

	}
	
	/**
	 * 
	 * @return void
	 **/
	function _form($tpl = null) 
	{
		parent::_form($tpl);
	

		// This behavior is required conditionally when date field is being used
		// but here we are incorporating this behavior explicitly, because
		// for AJAX-calls (json), there is no way to attach calendar js/css files  
		JHTML::_( 'behavior.calendar');

		$model = $this->getModel();
		$model->setState( 'filter_stateid', '' );
		$model->setState( 'filter_categoryid', '' );
		$row = $model->getItem();
		$this->assign('row', $row );
		
		$userid = @$row->sender_userid ? @$row->sender_userid : JRequest::getVar( 'userid' );
		// Element
		$elementmodel 	= JModel::getInstance( 'ElementUser', 'BilletsModel' );
		$elementUser 	= $elementmodel->fetchElement( 'sender_userid', $userid, '',  array('onClose'=>'\function(){onCloseModal();}') );
		$this->assign('elementUser', $elementUser);
		$resetUser 		= $elementmodel->clearElement( 'sender_userid', '0' );
		$this->assign('resetUser', $resetUser);
    }
	
	/**
	 * Process the data for the convert view
	 * @return void
	 **/
	function _convert($tpl=null)
	{
		// Import necessary helpers + library files

		$model = $this->getModel();
		
		// set the model state
			$this->assign( 'state', $model->getState() );
			
		// page-navigation
			$this->assign( 'pagination', $model->getPagination() );
		
		// list of items
			$items = $model->getList();	
			$this->assign('items', $items);
			
		// set toolbar
			$this->_convertToolbar();
			
		// form
			$validate = JUtility::getToken();
			$form = array();
			$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
			$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
			$action = $this->get( '_action', "index.php?option=com_billets&controller={$controller}&view={$view}" );
			$form['action'] = $action;
			$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
			$this->assign( 'form', $form );
	}
	
	function _convertToolbar()
	{
		$this->set('title', "Convert Tickets" );
		JToolBarHelper::save( 'completeconvert' );
		JToolBarHelper::cancel();
	}
	
	/**
	 * Process the data for the merge view
	 * @return void
	 **/
	function _merge($tpl=null)
	{
		$model = $this->getModel();
		
		// set the model state
		$this->assign( 'state', $model->getState() );
			
		// page-navigation
		$this->assign( 'pagination', $model->getPagination() );
		
		// list of items
		$items = $model->getList();	
		$this->assign('items', $items);
			
		// set toolbar
		$this->_mergeToolbar();
			
		// form
		$validate = JUtility::getToken();
		$form = array();
		$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
		$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
		$action = $this->get( '_action', "index.php?option=com_billets&controller={$controller}&view={$view}" );
		$form['action'] = $action;
		$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
		$this->assign( 'form', $form );
	}
	
	function _mergeToolbar()
	{
		$this->set('title', "Merge Tickets" );
		JToolBarHelper::save( 'completemerge' );
		JToolBarHelper::cancel();
	}
}
