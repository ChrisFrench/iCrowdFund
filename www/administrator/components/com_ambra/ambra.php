<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Access check: is this user allowed to access the backend of this component?
//if (!JFactory::getUser()->authorise('core.manage', 'com_ambra')) 
//{
//        return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
//}

// Check the registry to see if our Ambra class has been overridden
if ( !class_exists('Ambra') ) 
    	 JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );

// before executing any tasks, check the integrity of the installation
Ambra::getClass( 'AmbraHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Ambra::load( 'AmbraController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Ambra::load( 'AmbraController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new AmbraController();
	$redirect = "index.php?option=com_ambra&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

DSC::loadBootstrap();

JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
JHTML::_('stylesheet', 'admin.css', 'media/com_ambra/css/');

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_ambra = {};\n";
$js.= "com_ambra.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_ambra/helpers';
DSCLoader::discover('AmbraHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_ambra/library';
DSCLoader::discover('Ambra', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'ambra' );

$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger( 'onInstanceAdminComponentAmbra', array() );
// Create the controller
$classname = 'AmbraController'.$controller;
$controller = Ambra::getClass( $classname );
    
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