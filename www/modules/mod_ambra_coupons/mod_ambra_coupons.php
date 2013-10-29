<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__).DS.'helper.php' );

$helper = new modAmbraCouponsHelper( $params );

if ($helper->isInstalled())
{
    $itemid_suffix = '';
    if ($itemid = $params->get('itemid')) { $itemid_suffix = '&Itemid='.$itemid; }
    $url = "index.php?option=com_ambra&view=pointhistory".$itemid_suffix;
    $document = JFactory::getDocument();
    require( JModuleHelper::getLayoutPath( 'mod_ambra_coupons' ) );    
}
    else
{
    require( JModuleHelper::getLayoutPath( 'mod_ambra_coupons', 'notinstalled' ) );
}
