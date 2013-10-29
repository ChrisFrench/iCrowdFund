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

class WepayTableTools extends DSCTable 
{
	public function WepayTableTools ( &$db ) 
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
		$name 		= "wepay";
		
		parent::__construct( "#__{$tbl_suffix}", $tbl_key, $db );	
	}
}
