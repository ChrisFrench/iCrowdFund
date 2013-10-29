<?php
/**
 * @package	Terms of Service
 * @author 	Ammonite Networks
 * @link 	http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosTableTerms extends DSCTable 
{
	function TosTableTerms ( &$db ) 
	{
		
		$tbl_key 	= 'terms_id';
		$tbl_suffix = 'terms';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'tos';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check() {

		$date = JFactory::getDate();
      

		if (empty($this->created_date))
		{
			$this->created_date = $date->toSql();
		}
		
	    
		return true;
	}
	
	
}
