<?php
/**
 * @package Tos
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Tos') ) {
    JLoader::register( "Tos", JPATH_ADMINISTRATOR."/components/com_tos/defines.php" );
}

Tos::load( "TosHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for TosHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function TosBuildRoute(&$query)
{
    return TosHelperRoute::build($query);
}

/**
 * Parse the url segments
 * Is just a wrapper for TosHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function TosParseRoute($segments)
{
    return TosHelperRoute::parse($segments);
}