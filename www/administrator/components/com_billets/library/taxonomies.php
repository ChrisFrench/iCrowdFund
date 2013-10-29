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

class BilletsTaxonomies extends JObject
{	
	/**
	 * Gets an entire tree (recursive) from a table of records
	 * 
	 * @param $scope
	 * @param $parent
	 * @param $published
	 * @param $indent
	 * @param $level
	 * @param $list
	 * @return unknown_type
	 */
	public static function getTree( $scope='', $parent='0', $published='1', $indent='.&nbsp;&nbsp;', $level='-1', $list=array() )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( $scope, 'BilletsModel' );		
				
		unset($root);
		$root = JTable::getInstance( $scope, 'BilletsTable' );
		$root->load( (int) $parent );
		$root->title = ($level > 0) ? str_repeat( $indent, $level ).JText::_( $root->title ) : JText::_( $root->title );
		if (!empty($root->id))
		{
			$list[$root->id] = $root;
		}
		
		$model->setState('order', 'ordering' );
		$model->setState('direction', 'ASC' );
		$model->setState('filter_parentid', $root->id );
		$model->setState('filter_enabled', $published );
		$descendants = $model->getList();
		if ( !$descendants ) 
		{
			return $list;
		}

		$level++;
	   	for ($i=0; $i<count($descendants); $i++) 
	   	{
	   		$row = $descendants[$i];
	   		if (!empty($list[$row->id])) 
	   		{
				continue;
			}
	   		$row->title = ($level > 0) ? str_repeat( $indent, $level ).JText::_( $row->title ) : JText::_( $row->title );
			$list[$row->id] = $row;
			$list = BilletsTaxonomies::getTree( $scope, $row->id, $published, $indent, $level, $list );
	   	}
		
		return $list;
	}
	
	/**
	 * Gets the parents (recursive) of an item
	 * 
	 * @param $scope
	 * @param $id
	 * @param $published
	 * @param $indent
	 * @param $level
	 * @param $list
	 * @return unknown_type
	 */
	public static function getRoots( $scope='', $id='0', $published='1', $indent='.&nbsp;&nbsp;', $level='0', $list=array() )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( $scope, 'BilletsModel' );		
		$database = JFactory::getDBO();
				
		unset($root);
		$root = JTable::getInstance( $scope, 'BilletsTable' );
		$root->load( (int) $id );
		$root->title = str_repeat( $indent, $level ).JText::_( $root->title );
		if (!empty($root->id))
		{
			array_unshift( $list, $root );	
		}

		if ( !$root->parentid ) 
		{
			return $list;
		}

		$level++;
		$list = BilletsTaxonomies::getRoots( $scope, $root->parentid, $published, $indent, $level, $list );		
		return $list;
	}
}