<?php
/**
 * @package		Billets
 * @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link 		http://www.dioscouri.com
 */

class BilletsSelect extends DSCSelect 
{
	/**
	 * menuitem method modified from:
	 *
	 * @version      $Id: menuitem.php 11324 2008-12-05 19:06:24Z kdevine $
	 * @package      Joomla.Framework
	 * @subpackage   Parameter
	 * @copyright    Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
	 * @license      GNU/GPL, see LICENSE.php
	 * Joomla! is free software. This version may have been modified pursuant
	 * to the GNU General Public License, and as distributed it includes or
	 * is derivative of works licensed under the GNU General Public License or
	 * other free or open source software licenses.
	 * See COPYRIGHT.php for copyright notices and details.
	 */
	public static function menuitem($selected, $name = 'filter_menuitem', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'COM_BILLETS_SELECT_TYPE') {
		$db = JFactory::getDBO();

		$menuType = '';
		$where = ' WHERE 1';

		// load the list of menu types
		// TODO: move query to model
		$query = 'SELECT menutype, title' . ' FROM #__menu_types' . ' ORDER BY title';
		$db -> setQuery($query);
		$menuTypes = $db -> loadObjectList();

		// load the list of menu items
		// TODO: move query to model

		$query = 'SELECT id, parent_id, name, menutype, type' . ' FROM #__menu' . $where . ' ORDER BY menutype, parent_id, ordering';
		if (version_compare(JVERSION, '1.6.0', 'ge')) {
			$query = 'SELECT id, parent_id, title, menutype, type' . ' FROM #__menu' . $where . ' ORDER BY menutype, parent_id, ordering';
		} else {
			// Joomla! 1.5 code here
			$query = 'SELECT id, parent_id, name, menutype, type' . ' FROM #__menu' . $where . ' ORDER BY menutype, parent_id, ordering';
		}

		$db -> setQuery($query);
		$menuItems = $db -> loadObjectList();

		$children = array();

		if ($menuItems) {
			// first pass - collect children
			foreach ($menuItems as $v) {
				$pt = $v -> parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

		// assemble into menutype groups
		$n = count($list);
		$groupedList = array();
		foreach ($list as $k => $v) {
			$groupedList[$v -> menutype][] = &$list[$k];
		}

		// assemble menu items to the array
		$options = array();
		$options[] = JHTML::_('select.option', '', '- ' . JText::_('COM_BILLETS_SELECT_ITEM') . ' -');

		foreach ($menuTypes as $type) {
			if ($menuType == '') {
				$options[] = JHTML::_('select.option', '0', '&nbsp;', 'value', 'text', true);
				$options[] = JHTML::_('select.option', $type -> menutype, $type -> title . ' - ' . JText::_('COM_BILLETS_TOP'), 'value', 'text', true);
			}
			if (isset($groupedList[$type -> menutype])) {
				$n = count($groupedList[$type -> menutype]);
				for ($i = 0; $i < $n; $i++) {
					$item = &$groupedList[$type -> menutype][$i];
					$disable = false;
					$options[] = JHTML::_('select.option', $item -> id, '&nbsp;&nbsp;&nbsp;' . $item -> treename, 'value', 'text', $disable);

				}
			}
		}

		return self::genericlist($options, $name, $attribs, 'value', 'text', $selected, $idtag);
	}

	/**
	 * Generates a created/modified select list
	 *
	 * @param string The value of the HTML name attribute
	 * @param string Additional HTML attributes for the <select> tag
	 * @param mixed The key that is selected
	 * @returns string HTML for the radio list
	 */
	public static function datetype($selected, $name = 'filter_datetype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'COM_BILLETS_SELECT_TYPE') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -");
		}

		$list[] = JHTML::_('select.option', 'created', JText::_('COM_BILLETS_CREATED'));
		$list[] = JHTML::_('select.option', 'modified', JText::_('COM_BILLETS_MODIFIED'));

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @return unknown_type
	 */
	public static function taxonomy($scope = '', $selected = '', $name = 'filter_parentid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'COM_BILLETS_SELECT_PARENT', $title_none = 'COM_BILLETS_NO_PARENT') {
		static $instances;
		static $trees;

		if (!is_array($instances)) {
			$instances = array();
		}

		if (!is_array($trees)) {
			$trees = array();
		}

		if (empty($instances["$scope.$name"])) {
			// Build list
			$list = array();
			if ($allowAny) {
				$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');
			}
			if ($allowNone) {
				$list[] = self::option('0', "- " . JText::_($title_none) . " -", 'id', 'title');
			}

			if (empty($trees[$scope])) {
				Billets::load('BilletsTaxonomies', 'library.taxonomies');

				$trees[$scope] = BilletsTaxonomies::getTree($scope);
			}

			$items = $trees[$scope];
			foreach (@$items as $item) {
				$list[] = self::option($item -> id, JText::_($item -> title), 'id', 'title');
			}

			$instances["$scope.$name"] = self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
		}

		return $instances["$scope.$name"];
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @return unknown_type
	 */
	public static function category($selected, $name = 'filter_parentid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'COM_BILLETS_SELECT_CATEGORY', $title_none = 'COM_BILLETS_NO_PARENT', $enabled = null) {
		// Build list
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');

		}
		if ($allowNone) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'tables');
			$root = JTable::getInstance('Categories', 'BilletsTable') -> getRoot();
			$list[] = self::option($root -> id, "- " . JText::_($title_none) . " -", 'id', 'title');
		}

		JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'models');
		$model = JModel::getInstance('Categories', 'BilletsModel');
		$model -> setState('order', 'tbl.lft');
		if (intval($enabled) == '1') {
			// get only the enabled items in the tree
			// this would be used for the front-end
			$items = $model -> getTable() -> getTree();
		} else {
			$items = $model -> getList();
		}

		$dispatcher = JDispatcher::getInstance();
		$dispatcher -> trigger('onGetSelectListCategories', array(&$items));

		foreach (@$items as $item) {
			if (empty($item -> name)) {
				$item -> name = $item -> title;
				$item -> level = $item -> level - 1;
			}
			if (!empty($item -> id) && $item -> parentid > '0') {
				$repeats = $item -> level - 1 >= 0 ? $item -> level - 1 : 0;
				$list[] = self::option($item -> id, str_repeat('.&nbsp;', $repeats) . JText::_($item -> name), 'id', 'title');
			}
		}
		return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function fieldtype($selected, $name = 'filter_fieldtype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'COM_BILLETS_SELECT_TYPE', $title_none = 'No Type') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');
		}
		Billets::load('BilletsField', 'library.field');

		$items = BilletsField::getTypes();
		foreach (@$items as $item) {
			$list[] = self::option($item -> id, JText::_($item -> title), 'id', 'title');
		}
		return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function ticketstate($selected, $name = 'filter_stateid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'COM_BILLETS_SELECT_STATE', $title_none = 'COM_BILLETS_NO_STATE') {
		return self::taxonomy('ticketstates', $selected, $name, $attribs, $idtag, $allowAny, $allowNone, $title, $title_none);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function contentcategory($selected, $name = 'filter_contentcategory', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'COM_BILLETS_SELECT_CONTENT_CATEGORY', $title_none = 'COM_BILLETS_UNCATEGORIZED') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');
		}

		$db = JFactory::getDBO();
	if (version_compare(JVERSION, '1.6.0', 'ge')) {
			$query = "
			SELECT 
				id AS value, title AS text 
			FROM 
				 #__categories 
			WHERE 
				published = 1	
			ORDER BY 
				title
			";
		} else {
			// Joomla! 1.5 code here
			$query = "
			SELECT 
				CONCAT(sec.id,'.',cat.id) AS value, CONCAT(sec.title,' - ',cat.title) AS text 
			FROM 
				#__sections AS sec
				LEFT JOIN #__categories AS cat ON sec.id = cat.section
			WHERE 
				cat.published = 1
				AND sec.published = 1					
			ORDER BY 
				sec.title, cat.title
			";
		}	
			
			
			
		$db -> setQuery($query);
		$items = $db -> loadObjectList();
		foreach (@$items as $item) {
			$list[] = self::option($item -> value, JText::_($item -> text), 'id', 'title');
		}
		return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function label($selected, $name = 'filter_labelid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Label', $title_none = 'No Label') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');
		}
		if ($allowNone) {
			$list[] = self::option('-1', "- " . JText::_($title_none) . " -", 'id', 'title');
		}

		JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'models');
		$model = JModel::getInstance('Labels', 'BilletsModel');
		$model -> setState('order', 'title');
		$model -> setState('direction', 'ASC');
		$items = $model -> getList();
		foreach (@$items as $item) {
			$list[] = self::option($item -> id, JText::_($item -> title), 'id', 'title');
		}
		return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function color($selected, $name = 'filter_color', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = true, $allowNone = false, $title = 'COM_BILLETS_SELECT_COLOR') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'value', 'text');
		}

		$list[] = JHTML::_('select.option', '#33CCFF', JText::_('COM_BILLETS_ACQUA'));
		$list[] = JHTML::_('select.option', 'blue', JText::_('COM_BILLETS_BLUE'));
		$list[] = JHTML::_('select.option', 'fuchsia', JText::_('COM_BILLETS_FUCHSIA'));
		$list[] = JHTML::_('select.option', 'gray', JText::_('COM_BILLETS_GRAY'));
		$list[] = JHTML::_('select.option', 'green', JText::_('COM_BILLETS_GREEN'));
		$list[] = JHTML::_('select.option', 'lime', JText::_('COM_BILLETS_LIME'));
		$list[] = JHTML::_('select.option', 'maroon', JText::_('COM_BILLETS_MAROON'));
		$list[] = JHTML::_('select.option', 'navy', JText::_('COM_BILLETS_NAVY'));
		$list[] = JHTML::_('select.option', 'olive', JText::_('COM_BILLETS_OLIVE'));
		$list[] = JHTML::_('select.option', '#F5B800', JText::_('COM_BILLETS_ORANGE'));
		$list[] = JHTML::_('select.option', 'purple', JText::_('COM_BILLETS_PURPLE'));
		$list[] = JHTML::_('select.option', 'red', JText::_('COM_BILLETS_RED'));
		$list[] = JHTML::_('select.option', 'silver', JText::_('COM_BILLETS_SILVER'));
		$list[] = JHTML::_('select.option', 'teal', JText::_('COM_BILLETS_TEAL'));
		$list[] = JHTML::_('select.option', 'yellow', JText::_('COM_BILLETS_YELLOW'));

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag);
	}

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @param $allowNone
	 * @param $title
	 * @param $title_none
	 * @return unknown_type
	 */
	public static function frequent($selected, $name = 'filter_frequentid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Frequent') {
		$list = array();
		if ($allowAny) {
			$list[] = self::option('', "- " . JText::_($title) . " -", 'id', 'title');
		}

		JModel::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_billets' . DS . 'models');
		$model = JModel::getInstance('Frequents', 'BilletsModel');
		$model -> setState('filter_enabled', '1');
		$model -> setState('order', 'ordering');
		$model -> setState('direction', 'ASC');
		$items = $model -> getList();
		foreach (@$items as $item) {
			$list[] = self::option($item -> id, JText::_($item -> title), 'id', 'title');
		}
		return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag);
	}

	public static function usejQuery( $selected, $name = 'filter_usejquery', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        
        $list[] = JHTML::_('select.option',  '0', JText::_( "Mootools" ) );
        $list[] = JHTML::_('select.option',  '1', JText::_( "jQuery" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }


}
