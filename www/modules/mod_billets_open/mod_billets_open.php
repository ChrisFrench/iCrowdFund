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

// Include the syndicate functions only once
	require_once( dirname(__FILE__).DS.'helper.php' );

// include lang files
	$element = strtolower( 'com_Billets' );
	$lang = JFactory::getLanguage();
	$lang->load( $element, JPATH_BASE );
	$lang->load( $element, JPATH_ADMINISTRATOR );

require( JModuleHelper::getLayoutPath( 'mod_billets_open' ) );