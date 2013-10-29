<?php
/**
 * @package Wepay
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Wepay class has been overridden
if ( !class_exists('Wepay') ) 
    	 JLoader::register( "Wepay", JPATH_ADMINISTRATOR."/components/com_wepay/defines.php" );

// before executing any tasks, check the integrity of the installation
Wepay::getClass( 'WepayHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Wepay::load( 'WepayController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Wepay::load( 'WepayController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new WepayController();
	$redirect = "index.php?option=com_wepay&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

DSC::loadBootstrap();

JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
JHTML::_('stylesheet', 'admin.css', 'media/com_wepay/css/');

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_wepay = {};\n";
$js.= "com_wepay.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_wepay/helpers';
DSCLoader::discover('WepayHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_wepay/library';
DSCLoader::discover('Wepay', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'wepay' );

// Create the controller
$classname = 'WepayController'.$controller;
$controller = Wepay::getClass( $classname );
    
// ensure a valid task exists
$task = JRequest::getVar('task');
if (empty($task))
{
    $task = 'display';
    JRequest::setVar( 'layout', 'default' );
}
JRequest::setVar( 'task', $task );

// Perform the requested task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
?>