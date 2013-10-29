<?php
/**
 * @package Billets
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Billets class has been overridden
if ( !class_exists('Billets') ) 
    JLoader::register( "Billets", JPATH_ADMINISTRATOR.DS."components".DS."com_billets".DS."defines.php" );

// before executing any tasks, check the integrity of the installation
Billets::getClass( 'BilletsHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Billets::load( 'BilletsController', 'controller' );
// Require the base controller


// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Billets::load( 'BilletsController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new BilletsController();
	$redirect = "index.php?option=com_billets&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

JHTML::_('stylesheet', 'admin.css', 'media/com_billets/css/');

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_billets = {};\n";
$js.= "com_billets.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/helpers';
DSCLoader::discover('BilletsHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/library';
DSCLoader::discover('Billets', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'billets' );

// Create the controller
$classname = 'BilletsController'.$controller;
$controller = Billets::getClass( $classname );
    
// ensure a valid task exists
$task = JRequest::getVar('task');
if (empty($task))
{
    $task = 'display';  
}
JRequest::setVar( 'task', $task );

// Perform the requested task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
?>