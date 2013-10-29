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

if ( !class_exists('Extendform') ) {
    JLoader::register( "Extendform", JPATH_ADMINISTRATOR.DS."components".DS."com_extendform".DS."defines.php" );
}

Extendform::load( "ExtendformHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for ExtendformHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function ExtendformBuildRoute(&$query)
{
    return ExtendformHelperRoute::build($query);
}

/**
 * Parse the url segments
 * Is just a wrapper for ExtendformHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function ExtendformParseRoute($segments)
{
    return ExtendformHelperRoute::parse($segments);
}