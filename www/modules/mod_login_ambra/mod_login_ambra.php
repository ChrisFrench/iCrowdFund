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
$lang = JFactory::getLanguage();
$lang->load( $element, JPATH_BASE );
$lang->load( $element, JPATH_ADMINISTRATOR );

$user =  JFactory::getUser();

// display based on login status
if (empty(JFactory::getUser()->id))
{
    $return = modLoginAmbraHelper::getReturnURL( $params, 'return_login' );
    require( JModuleHelper::getLayoutPath( 'mod_login_ambra' ) );    
}
    else
{
    $return = modLoginAmbraHelper::getReturnURL( $params, 'return_logout' );
    require( JModuleHelper::getLayoutPath( 'mod_login_ambra', 'logout' ) );
}



