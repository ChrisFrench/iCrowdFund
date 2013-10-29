<?php
/**
 * @package Featured Items
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('FeaturedItems') ) { 
    JLoader::register( "FeaturedItems", JPATH_ADMINISTRATOR.DS."components".DS."com_featureditems".DS."defines.php" );
}

require_once ( dirname( __FILE__ ) .   '/helper.php' );

$helper = new modFeaturedItemsItemsHelper( $params );
$items = $helper->getItems();
$count = count($items);

require (JModuleHelper::getLayoutPath('mod_featureditems_items', $params->get('layout', 'default')));