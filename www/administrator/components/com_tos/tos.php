<?php 
/**
 * @package	Terms of Service
 * @author 	Ammonite Networks
 * @link 	http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Check the registry to see if our Tos class has been overridden
if ( !class_exists('Tos') ) 
    JLoader::register( "Tos", JPATH_ADMINISTRATOR."/components/com_tos/defines.php" );

// before executing any tasks, check the integrity of the installation
Tos::getClass( 'TosHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Tos::load( 'TosController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Tos::load( 'TosController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new TosController();
	$redirect = "index.php?option=com_tos&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

DSC::loadBootstrap();

JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
JHTML::_('stylesheet', 'admin.css', 'media/com_tos/css/');

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_tos = {};\n";
$js.= "com_tos.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_tos/helpers';
DSCLoader::discover('TosHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_tos/library';
DSCLoader::discover('Tos', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'tos' );

// Create the controller
$classname = 'TosController'.$controller;
$controller = Tos::getClass( $classname );
    
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