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

if ( !class_exists('Featureditems') ) {
    JLoader::register( "Featureditems", JPATH_ADMINISTRATOR.DS."components".DS."com_featureditems".DS."defines.php" );
}

Featureditems::load( "FeatureditemsHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for FeatureditemsHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function FeatureditemsBuildRoute(&$query)
{
    return FeatureditemsHelperRoute::build($query);
}

/**
 * Parse the url segments
 * Is just a wrapper for FeatureditemsHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function FeatureditemsParseRoute($segments)
{
    return FeatureditemsHelperRoute::parse($segments);
}