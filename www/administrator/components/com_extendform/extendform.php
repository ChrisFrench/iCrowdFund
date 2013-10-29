<?php
/**
 * @package Extendform
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Extendform class has been overridden
if ( !class_exists('Extendform') ) 
    JLoader::register( "Extendform", JPATH_ADMINISTRATOR.DS."components".DS."com_extendform".DS."defines.php" );

// before executing any tasks, check the integrity of the installation
Extendform::getClass( 'ExtendformHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Extendform::load( 'ExtendformController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Extendform::load( 'ExtendformController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new ExtendformController();
	$redirect = "index.php?option=com_extendform&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

JHTML::_('stylesheet', 'admin.css', 'media/com_extendform/css/');

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_extendform = {};\n";
$js.= "com_extendform.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_extendform/helpers';
DSCLoader::discover('ExtendformHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_extendform/library';
DSCLoader::discover('Extendform', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'extendform' );

// Create the controller
$classname = 'ExtendformController'.$controller;
$controller = Extendform::getClass( $classname );
    
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