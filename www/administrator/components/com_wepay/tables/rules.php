<?php
/**
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class WepayTableRules extends DSCTable 
{
		public function WepayTableRules( &$db ) 
	{
		$tbl_key 	= 'rule_id';
		$tbl_suffix = 'rules';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "wepay";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
}
