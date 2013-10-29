<?php
/**
 * @package Billets
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Billets') ) {
    JLoader::register( "Billets", JPATH_ADMINISTRATOR.DS."components".DS."com_billets".DS."defines.php" );
}

Billets::load( "BilletsHelperRoute", 'helpers.route' );

/**
 * Build the route
 * Is just a wrapper for BilletsHelperRoute::build()
 * 
 * @param unknown_type $query
 * @return unknown_type
 */
function BilletsBuildRoute(&$query)
{
    return BilletsHelperRoute::build($query);
}

/**
 * Parse the url segments
 * Is just a wrapper for BilletsHelperRoute::parse()
 * 
 * @param unknown_type $segments
 * @return unknown_type
 */
function BilletsParseRoute($segments)
{
    return BilletsHelperRoute::parse($segments);
}





/**
 * @version	0.1
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
//defined('_JEXEC') or die('Restricted access');

/*
function BilletsBuildRoute(&$query)
{
	$segments = array();
	
	// echo "query:<br /><pre>";
	// print_r($query);
	// echo "</pre>";
	
	
	if(isset($query['controller']))
	{
		$segments[] = $query['controller'];
		unset($query['controller']);
	}
			
	if(isset($query['task'])) 
	{
		$segments[] = $query['task'];
		unset($query['task']);
	};

	if(isset($query['view'])) 
	{
		$segments[] = $query['view'];
		unset($query['view']);
	};
	
	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	};

	return $segments;
}

/**
* 	BilletsParseRoute
*/
/*
function BilletsParseRoute($segments)
{
	/*
	$format = JRequest::getVar( 'format', '0' );
	if ( empty( $format ) )
	{
		echo "Segments<br /><pre>";

		echo "</pre>";
	}
	*/
/*	
	$vars = array();
	switch($segments[0])
	{
		case 'manage':
		case 'tickets':
		case 'labels':
			$vars['controller'] = $segments[0];
			$vars['view'] = $segments[0];
			$first = isset($segments[1]) ? $segments[1] : 'default'; 
			switch ( $first )
			{
				case 'view':
					$vars['task'] = 'view';
					if(isset($segments[3])){
						$vars['id'] = $segments[3];
					}
				  break;
				case 'new':
					$vars['task'] = 'new';
				  break;
				case 'edit':
					$vars['task'] = 'edit';
					if(isset($segments[3])){
						$vars['id'] = $segments[3];
					}
				  break;
				case 'downloadfile':
					$vars['task'] = 'downloadfile';
					$id = explode( ':', $segments[2] );
					$vars['id'] = (int) $id[0];	
				  break;
				default:
				  break;
			}
		  break;
		  
			
		default:
			$vars['task'] = $segments[0];
			if(isset($segments[1])){
				$vars['controller'] = $segments[1];
				$vars['view'] = $segments[1];
			}
		  break;
			
	}
	
	return $vars;
}*/