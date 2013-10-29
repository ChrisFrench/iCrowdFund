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

if ( !class_exists('Ambra') ) {
    JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );
}

Ambra::load( "AmbraHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for AmbraHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function AmbraBuildRoute(&$query)
{
    return AmbraHelperRoute::build($query);
}

/**
 * Parse the url segments
 * Is just a wrapper for AmbraHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function AmbraParseRoute($segments)
{	
    return AmbraHelperRoute::parse($segments);
}