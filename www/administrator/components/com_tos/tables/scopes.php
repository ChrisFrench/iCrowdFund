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

class TosTableScopes extends DSCTable 
{
	public function TosTableScopes( &$db ) 
	{
		
		$tbl_key 	= 'scope_id';
		$tbl_suffix = 'scopes';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'tos';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
	    
		if (empty($this->scope_name))
		{
			$this->setError( JText::_( "Scope Name Required" ) );
			return false;
		}
		
	    if (empty($this->scope_identifier))
        {
            $this->setError( JText::_( "Scope Identifier Required" ) );
            return false;
        }
		return true;
	}
}
