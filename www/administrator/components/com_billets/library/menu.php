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

jimport('joomla.html.toolbar');
require_once( JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php' );

class BilletsMenu extends DSCMenu
{
	
	 function __construct($name = 'submenu') {
	 	parent::__contruct($name);
	 }
	/**
	 * Returns HTML to display the submenu
	 * 
	 * @return unknown_type
	 */
	function display( $name='submenu', $hidemainmenu='')
	{
		// Check the config to see if the admin has disabled submenus
		if (!Billets::getInstance()->get('display_submenu', '1'))
		{
			return null;
		}
		
		parent::display();
	}
}