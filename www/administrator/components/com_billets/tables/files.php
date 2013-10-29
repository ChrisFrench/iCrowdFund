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

class BilletsTableFiles extends BilletsTable 
{
	/**
	 * 
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function BilletsTableFiles ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 'files';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{		
		return true;
	}
	
	function load( $oid=null, $reset=true )
	{
	    if ($load = parent::load($oid, $reset))
	    {
	        if ($this->fileisblob)
	        {
	            $db = JFactory::getDBO();
	            $db->setQuery( "SELECT `fileblob` FROM #__billets_fileblobs WHERE `fileid` = '{$this->id}'" );
	            $this->fileblob = $db->loadResult();
	        }
	    }
	    
	}
}
