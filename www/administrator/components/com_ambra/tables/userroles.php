<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::import( 'com_ambra.tables._basexref', JPATH_ADMINISTRATOR.DS.'components' );

class AmbraTableUserRoles extends AmbraTableXref 
{
	/** 
	 * @param $db
	 * @return unknown_type
	 */
	function AmbraTableUserRoles ( &$db ) 
	{
		$keynames = array();
		$keynames['user_id']  = 'user_id';
        $keynames['role_id'] = 'role_id';
        $this->setKeyNames( $keynames );
                
		$tbl_key 	= 'user_id';
		$tbl_suffix = 'roles2users';
		$name 		= 'ambra';
		
		$this->set( '_tbl_key', $tbl_key );
		$this->set( '_suffix', $tbl_suffix );
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if (empty($this->role_id))
		{
			$this->setError( JText::_( "Role Required" ) );
			return false;
		}
		if (empty($this->user_id))
		{
			$this->setError( JText::_( "User Required" ) );
			return false;
		}
		
		return true;
	}
}
