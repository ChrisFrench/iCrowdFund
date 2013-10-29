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

jimport( 'joomla.application.component.view' );

class BilletsViewBase extends DSCViewSite 
{
	
	
	 
	/**
	 * First displays the submenu, then displays the output
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl=null)
	{
		
		JHTML::_('stylesheet', 'menu.css', 'media/com_billets/css/');
		
		$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/helpers';
		DSCLoader::discover('BilletsHelper', $parentPath, true);
		
		$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/library';
		DSCLoader::discover('Billets', $parentPath, true);
		
		
		parent::display($tpl);		
	}
	
	
	/**
	 * Basic commands for displaying a list
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function _default($tpl='')
	{
	
		parent::_default();
	}
	
	/**
	 * Basic methods for a form
	 * @param $tpl
	 * @return unknown_type
	 */
	function _form($tpl='')
	{
	
		parent::_form();
			
	}
	
}