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

Billets::load('BilletsHelperBase','helpers._base' );

class BilletsHelperTicketstate extends BilletsHelperBase
{
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public static function getImage( $id, $by='id', $alt='' )
	{
		$tmpl = "";
		if (strpos($id, '.'))
		{
			// then this is a filename, return the full img tag
			$tmpl = "<img src='".JURI::root()."/media/com_billets/images/states/{$id}' alt='".JText::_( $alt )."' title='".JText::_( $alt )."' name='".JText::_( $alt )."' align='center' border='0'>";
		}
			else
		{
			$type = BilletsHelperTicketstate::getType( $id, $by );
			$title = JText::_( $type->title );
			if (!empty($type->img))
			{
				$tmpl = "<img src='".JURI::root()."/media/com_billets/images/states/{$type->img}' alt='{$title}' title='{$title}' name='{$title}'>";	
			}
				else
			{
				$tmpl = "<img src='".JURI::root()."/media/com_billets/images/states/default.png'>";
			}
		}
		return $tmpl;
	}
	
	/**
	 * 
	 * @param $id
	 * @param $by
	 * @return unknown_type
	 */
	public static function getType( $id, $by='id' ) 
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Ticketstates', 'BilletsTable' );

		switch( $by )
		{
			case "name":
				$table->load( array('title'=>$id) );
			  break;
			case "id":
			default:
				$table->load( $id );
			  break;
		}

		return $table;
	}

}