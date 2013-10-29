<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Billets::load( 'BilletsTable', 'tables._base' );

class BilletsTableTools extends BilletsTable 
{
	/**
	 * Could this be abstracted into the base?
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function BilletsTableTools ( &$db ) 
	{
	    if(version_compare(JVERSION,'1.6.0','ge')) {
	        // Joomla! 1.6+ code here
	        $tbl_key 	= 'extension_id';
	        $tbl_suffix = 'extensions';
	    } else {
	        // Joomla! 1.5 code here
	        $tbl_key 	= 'id';
	        $tbl_suffix = 'plugins';
	    }
		
	    $this->set( '_suffix', $tbl_suffix );
		parent::__construct( "#__{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{		
		return true;
	}

}
