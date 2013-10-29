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

class AmbraTableRoleActions extends AmbraTableXref 
{
	/** 
	 * @param $db
	 * @return unknown_type
	 */
	function AmbraTableRoleActions ( &$db ) 
	{
		$keynames = array();
		$keynames['action_id']  = 'action_id';
        $keynames['role_id'] = 'role_id';
        $this->setKeyNames( $keynames );
                
		$tbl_key 	= 'action_id';
		$tbl_suffix = 'roles2actions';
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
		if (empty($this->action_id))
		{
			$this->setError( JText::_( "Action Required" ) );
			return false;
		}
		
		return true;
	}
}
