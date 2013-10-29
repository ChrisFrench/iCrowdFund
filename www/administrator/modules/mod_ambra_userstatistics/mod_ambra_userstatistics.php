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

// TODO Decide whether or not this should include an admin/helpers/statistics.php
require_once( dirname(__FILE__).DS.'helper.php' );

$helper = new modAmbraUserStatisticsHelper( $params );

if ($helper->isInstalled())
{
    $stats = $helper->getStatistics(); 
    require( JModuleHelper::getLayoutPath( 'mod_ambra_userstatistics' ) );    
}
    else
{
    require( JModuleHelper::getLayoutPath( 'mod_ambra_userstatistics', 'notinstalled' ) );
}
