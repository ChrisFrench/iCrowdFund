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

// Include the syndicate functions only once
	require_once( dirname(__FILE__).DS.'helper.php' );

// include lang files
	$element = strtolower( 'com_Ambra' );
	$lang =& JFactory::getLanguage();
	$lang->load( $element, JPATH_BASE );
	$lang->load( $element, JPATH_ADMINISTRATOR );

// load plugins
	JPluginHelper::importPlugin( 'ambra' );

// get params
	$showmode = $params->get( 'showmode', 'avatars' );
	$pic_size = $params->get( 'pic_size', '36' );
	$display_urls = $params->get( 'display_urls', '1' );
	$moduleclass_sfx = $params->get( 'moduleclass_sfx' );
	$itemid2affix = $params->get( 'itemid2affix' );
		
	$count = "";
	$users = "";
	$display_num = false;
	switch ($showmode) 
	{
		case "usernames":
	    	$users = modLoggedAmbraHelper::getOnlineMembers();			
		  break;
		case "num_usernames":
			$display_num = true;
			$count 	= modLoggedAmbraHelper::getOnlineCount();
			$users = modLoggedAmbraHelper::getOnlineMembers();
		  break;
		case "num_avatars":
			$display_num = true;
			$count 	= modLoggedAmbraHelper::getOnlineCount();
			$users = modLoggedAmbraHelper::getOnlineMembers();
		  break;
		case "avatars":
			$users = modLoggedAmbraHelper::getOnlineMembers();
			$usersAdmin = modLoggedAmbraHelper::getOnlineMembers( '1' );
		  break;
		case "num":
		default:
			$display_num = true;
			$count 	= modLoggedAmbraHelper::getOnlineCount();
			$users = modLoggedAmbraHelper::getOnlineMembers();
		  break;
	}


require( JModuleHelper::getLayoutPath( 'mod_logged_ambra' ) );