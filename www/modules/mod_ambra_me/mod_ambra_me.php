<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__).'/helper.php' );

$helper = new modAmbraMeHelper( $params );

if ($helper->isInstalled() && JFactory::getUser()->id)
{
//menu stuff
$list   = $helper::getList($params);
$app    = JFactory::getApplication();
$menu   = $app->getMenu();
$active = $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path   = isset($active) ? $active->tree : array();
$showAll    = $params->get('showAllChildren');
$class_sfx  = htmlspecialchars($params->get('class_sfx'));
$document = JFactory::getDocument();


    $itemid_suffix = '';
    if ($itemid = $params->get('itemid')) { $itemid_suffix = '&Itemid='.$itemid; }
    $url = "index.php?option=com_ambra&view=users".$itemid_suffix;
    $doc = JFactory::getDocument();
    $user = $helper->getUserInfo(); 

    require( JModuleHelper::getLayoutPath( 'mod_ambra_me'  ,  $params->get('layout', 'default')) );    
}
    else
{
    require( JModuleHelper::getLayoutPath( 'mod_ambra_me', 'notinstalled' ) );
}
