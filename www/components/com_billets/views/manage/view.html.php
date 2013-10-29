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

//JLoader::import( 'com_billets.views._base', JPATH_SITE.DS.'components' );
Billets::load( 'BilletsViewBase', 'views._base', array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_billets' ) );

class BilletsViewManage extends BilletsViewBase 
{
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
			case "default":
			default:
			  break;
		}
	}
	
	
	
	function _default($tpl=null)
	{
		parent::_default($tpl);
		
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( 'Fields', 'BilletsModel' );
		$model->setState( 'order', 'tbl.ordering' );
		$model->setState( 'direction', 'ASC' );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_listdisplayed', '1' );
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
		
		// Element
		$elementmodel 	= JModel::getInstance( 'ElementUser', 'BilletsModel' );
		$elementUser 	= $elementmodel->fetchElement( 'sender_userid', ( ( $row ) ? $row->sender_userid : null ), '', array('onClose'=>'\function(){onCloseModal();}') );
		$this->assign('elementUser', $elementUser);
		$resetUser 		= $elementmodel->clearElement( 'sender_userid', ( ( $row ) ? $row->sender_userid : null ) );
		$this->assign('resetUser', $resetUser);
    }
}
