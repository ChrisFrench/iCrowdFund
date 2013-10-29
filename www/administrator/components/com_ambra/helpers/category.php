<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load( 'AmbraHelperBase', 'helpers._base' );

class AmbraHelperCategory extends AmbraHelperBase
{
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	function getTitle( $id, $format='bullets' )
	{
		$name = '';
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
		$cat = JTable::getInstance( 'Categories', 'Table' );
		$cat->load( $id );
		$pat = '';
		if (intval($cat->parentid) > 0) 
		{
			$pat = JTable::getInstance( 'Categories', 'Table' );
			$pat->load( $cat->parentid );
		}
		
		switch ($format) 
		{
			case "flat":
				if ($pat) 
				{
					$name .= JText::_( $pat->title );
					$name .= " / ";
				}
				$name .= $cat->title ? JText::_( $cat->title ) : JText::_( 'Uncategorized' );
			  break;
			default:
				if ($pat) 
				{
					$name .= '&bull;&nbsp;&nbsp;';
					$name .= JText::_( $pat->title );
					$name .= "<br/>";
				}
				$name .= '&bull;&nbsp;&nbsp;';
				$name .= $cat->title ? JText::_( $cat->title ) : JText::_( 'Uncategorized' );
			  break;
		}
		
		return $name;
	}	
}