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
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class BilletsUrl extends DSCUrl 
{
	/**
	 * Get the link to a menu by specifying it's ID
	 * 
	 * @param $menu_id integer The menu's ID
	 */
	function getMenuLink($menu_id)
	{
	
		Billets::load('BilletsMenu', 'library.menu');
		$menu = BilletsMenu::getInstance( 'Menu' );
		
		if (!$menu->load($menu_id) || trim($menu->link) == '') 
		{
		    return 'index.php';
		}
		
		return $menu->link;
	}
}
