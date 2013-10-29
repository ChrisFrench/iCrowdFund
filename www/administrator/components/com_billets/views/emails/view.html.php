<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2011 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Billets::load( 'BilletsViewBase', 'views._base' );

class BilletsViewEmails extends BilletsViewBase 
{
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl=null) 
	{
		parent::display($tpl);
	}
	
	function _default($tpl=null)
	{
		$this->_defaultToolbar();
		parent::_default($tpl);
	}
	
	function _defaultToolbar()
	{
		
	}
	
	function _form($tpl=null)
	{
		$this->_formToolbar();
		//parent::_form($tpl);
	}
	
	function _formToolbar( $isNew = false )
	{
		JToolBarHelper::save('save', JText::_('COM_BILLETS_SAVE') );
		JToolBarHelper::apply('apply', JText::_('COM_BILLETS_APPLY') );		
		JToolBarHelper::cancel( 'close', JText::_('COM_BILLETS_CLOSE') );
	}
}