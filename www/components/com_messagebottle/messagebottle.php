<?php
/**
 * @package Messagebottle
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Messagebottle class has been overridden
if ( !class_exists('Messagebottle') ) 
    JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );

// before executing any tasks, check the integrity of the installation
Messagebottle::getClass( 'MessagebottleHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// set the options array
$options = array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_messagebottle' );

// Require the base controller
Messagebottle::load( 'MessagebottleController', 'controller', $options );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Messagebottle::load( 'MessagebottleController'.$controller, "controllers.$controller", $options ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
    $default_controller = new MessagebottleController();
    $redirect = "index.php?option=com_messagebottle&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_messagebottle = {};\n";
$js.= "com_messagebottle.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_messagebottle/helpers';
DSCLoader::discover('MessagebottleHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_messagebottle/library';
DSCLoader::discover('Messagebottle', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'messagebottle' );

// Create the controller
$classname = 'MessagebottleController'.$controller;
$controller = Messagebottle::getClass( $classname );

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