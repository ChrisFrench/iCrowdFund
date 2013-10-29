<?php
/**
 * @package Featureditems
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Featureditems class has been overridden
if ( !class_exists('Featureditems') ) 
    JLoader::register( "Featureditems", JPATH_ADMINISTRATOR.DS."components".DS."com_featureditems".DS."defines.php" );

// before executing any tasks, check the integrity of the installation
Featureditems::getClass( 'FeatureditemsHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// set the options array
$options = array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_featureditems' );

// Require the base controller
Featureditems::load( 'FeatureditemsController', 'controller', $options );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Featureditems::load( 'FeatureditemsController'.$controller, "controllers.$controller", $options ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
    $default_controller = new FeatureditemsController();
    $redirect = "index.php?option=com_featureditems&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_featureditems = {};\n";
$js.= "com_featureditems.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_featureditems/helpers';
DSCLoader::discover('FeatureditemsHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_featureditems/library';
DSCLoader::discover('Featureditems', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'featureditems' );

// Create the controller
$classname = 'FeatureditemsController'.$controller;
$controller = Featureditems::getClass( $classname );

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