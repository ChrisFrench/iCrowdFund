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

class BilletsViewUsers extends BilletsViewBase 
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

		parent::_default($tpl);
	}
	
	function _defaultToolbar()
	{

	}
}
