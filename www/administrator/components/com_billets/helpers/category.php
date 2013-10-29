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

//if(!class_exists('BilletsHelperCategory')){

class BilletsHelperCategory extends BilletsHelperBase
{
	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public static function getTitle( $id, $format='bullets' )
	{
		$name = '';
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$cat = JTable::getInstance( 'Categories', 'BilletsTable' );
		$cat->load( $id );
		$pat = '';
		if (intval($cat->parentid) > 0)
		{
			$pat = JTable::getInstance( 'Categories', 'BilletsTable' );
			$pat->load( $cat->parentid );
		}

		switch ($format)
		{
			case "plain":
				$name = $cat->title;
				break;
			case "flat":
				if ($pat)
				{
					if (!$pat->isroot)
					{
						$name .= JText::_( $pat->title );
						$name .= " / ";
					}
				}
				$name .= $cat->title ? JText::_( $cat->title ) : JText::_('COM_BILLETS_UNCATEGORIZED');
			  break;
			default:
				if ($pat)
				{
					if (!$pat->isroot)
					{
						$name .= '&bull;&nbsp;&nbsp;';
						$name .= JText::_( $pat->title );
						$name .= "<br/>";
					}
				}
				$name .= '&bull;&nbsp;&nbsp;';
				$name .= $cat->title ? JText::_( $cat->title ) : JText::_('COM_BILLETS_UNCATEGORIZED');
			  break;
		}

		return $name;
	}

	/**
	 * Returns a list of types
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getAll( $enabled='1', $scope='categories' )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Categories', 'BilletsTable' );
		$items = $table->getTree( null, $enabled );
		return $items;
	}

	/**
	 * Returns a list of types
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getChildren( $id=0, $enabled='', $scope='categories' )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Categories', 'BilletsTable' );
		$table->load( $id );
		$items = $table->getDescendants( $enabled );
		return $items;
	}

	/**
	 * Returns a list of parents
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getParents( $id=0, $published='', $scope='categories' )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Categories', 'BilletsTable' );
		$table->load( $id );
		$items = $table->getPath();
		if(empty($items))
			$items = $table;	
		
		return $items;
	}
	
	/**
	 * Returns first parent
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getFirstParent( $id=0, $published='', $scope='categories' )
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Categories', 'BilletsTable' );
		$table->load( $id );		
		$items = $table->getPath();
		if(count($items)>1)
			$category = $items[1];
		else 
			$category = $table;
		
		return $category;
	}

	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public static function getEmailManagers( $id )
	{
        static $instance;

        if (empty($instance[$id]))
        {
        	if (!is_array($instance)) { $instance = array(); }
        	$instance[$id] = array();
			$database = JFactory::getDBO();

			$where = array();
			$lists = array();
			$nodupes = array();

			$items = BilletsHelperCategory::getViewingManagers( $id );
			foreach (@$items as $item)
			{
				$where[] = $item->id;
			}

			$cat_query = " AND u2c.userid IN ('".implode( "', '", $where )."') ";
			$email_query = " AND u2c.emails = '1' ";

			$query = "
				SELECT
					u.*, u2c.categoryid
				FROM
					#__billets_u2c AS u2c
					LEFT JOIN #__users AS u ON u2c.userid = u.id
				WHERE 1
					{$cat_query}
					{$email_query}
					AND u2c.categoryid = '{$id}'
				GROUP BY u.id
			";

			$database->setQuery( $query );
			$instance[$id] = $database->loadObjectList();

        }

		return $instance[$id];
	}

	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public static function getViewingManagers( $id )
	{
        static $instance;

        if (empty($instance[$id]))
        {
        	if (!is_array($instance)) { $instance = array(); }
        	$instance[$id] = array();
        	$database = JFactory::getDBO();

			$where = array();
			$lists = array();
			$nodupes = array();

			$where[] = $id;
			$items = BilletsHelperCategory::getParents( $id );
			foreach (@$items as $item)
			{
				$where[] = $item->id;
			}

			$cat_query = " AND u2c.categoryid IN ('".implode( "', '", $where )."') ";
			$email_query = " AND u2c.emails = '1' ";

			$query = "
				SELECT
					u.*, u2c.categoryid
				FROM
					#__billets_u2c AS u2c
					LEFT JOIN #__users AS u ON u2c.userid = u.id
				WHERE 1
					{$cat_query}
					{$email_query}
				GROUP BY u.id
			";

			$database->setQuery( $query );
			$data = $database->loadObjectList();

			for ($i=0; $i<count($data); $i++)
			{
				$d = $data[$i];
				if ($d->id > 0 && !isset($nodupes[$d->id]))
				{
					$instance[$id][] = $d;
					$nodupes[$d->id] = $d->id;
				}
			}

			DSC::load('DSCAcl','library.acl');
			$users = DSCAcl::getAdminList();

			for ($i=0; $i<count($users); $i++)
			{
				$d = $users[$i];
				if ($d->id > 0 && !isset($nodupes[$d->id]))
				{
					$instance[$id][] = $d;
					$nodupes[$d->id] = $d->id;
				}
			}
        }



		return $instance[$id];
	}

	/**
	 *
	 * @param $id
	 * @return unknown_type
	 */
	public static function getFields( $id, $required='0' )
	{
		$where = array();
		$where[] = $id;
		$items = BilletsHelperCategory::getParents( $id );
		foreach (@$items as $item)
		{
			$where[] = @$item->id;
		}
		$cat_query = " AND f2c.categoryid IN ('".implode( "', '", $where )."') ";

		$required_query = "";
		if ($required == '1')
		{
			$required_query = " AND f2c.required = '1' ";
		}

		$database = JFactory::getDBO();
		$query = "
			SELECT
				f.*, f2c.required
			FROM
				#__billets_fields AS f
				LEFT JOIN #__billets_f2c AS f2c ON f.id = f2c.fieldid
			WHERE 1
				{$required_query}
				{$cat_query}
				AND f.published = '1'
				GROUP BY f.id
				ORDER BY f.ordering ASC
		";
		$database->setQuery( sprintf( $query, $id ) );
		$data = $database->loadObjectList();

		return $data;
	}
} // End of class declaration

//} // End of if class_exists()
