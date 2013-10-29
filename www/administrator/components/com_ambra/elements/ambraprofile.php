<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'select.php' );

class JElementAmbraProfile extends JElement
{
	var	$_name = 'AmbraProfile';

	function fetchElement($name, $value, &$node, $control_name)
	{

	// Build list
        $list = array();
		$title = 'Select Profile';
		$list[] =  JHTMLSelect::option('', "- ".JText::_( $title )." -", 'profile_id', 'profile_name' );

		$db =& JFactory::getDBO();
		$db->setQuery("SELECT profile_name, profile_id FROM #__ambra_profiles");
		$items = $db->loadObjectList();

        foreach (@$items as $item)
        {
        	$list[] =  JHTMLSelect::option( $item->profile_id, JText::_($item->profile_name), 'profile_id', 'profile_name' );
        }

		return JHTMLSelect::genericlist($list, $control_name.'['.$name.']', '', 'profile_id', 'profile_name', $value, $control_name.$name );

	}
}