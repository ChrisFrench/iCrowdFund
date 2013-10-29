<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Ambra class has been overridden
if ( !class_exists('Ambra') ) 
    JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );

// load the config class
Ambra::load( 'Ambra', 'defines' );

// before executing any tasks, check the integrity of the installation
Ambra::getClass( 'AmbraHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// set the options array
$options = array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_ambra' );

// Require the base controller
Ambra::load( 'AmbraController', 'controller', $options );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Ambra::load( 'AmbraController'.$controller, "controllers.$controller", $options ))
    $controller = ''; // redirect to default?

if (empty($controller))
{
    // redirect to default
    $redirect = "index.php?option=com_ambra&view=users";
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}
    
// load the plugins
JPluginHelper::importPlugin( 'ambra' );

$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger( 'onInstanceSiteComponentAmbra', array() );

// Create the controller
$classname = 'AmbraController'.$controller;
$controller = Ambra::getClass( $classname );

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