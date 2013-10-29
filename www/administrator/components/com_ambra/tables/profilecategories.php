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

class AmbraTableProfileCategories extends AmbraTableXref 
{
	/** 
	 * @param $db
	 * @return unknown_type
	 */
	function AmbraTableProfileCategories ( &$db ) 
	{
		$keynames = array();
		$keynames['profile_id']  = 'profile_id';
        $keynames['category_id'] = 'category_id';
        $this->setKeyNames( $keynames );
                
		$tbl_key 	= 'profile_id';
		$tbl_suffix = 'categories2profiles';
		$name 		= 'ambra';
		
		$this->set( '_tbl_key', $tbl_key );
		$this->set( '_suffix', $tbl_suffix );
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if (empty($this->category_id))
		{
			$this->setError( JText::_( "Category Required" ) );
			return false;
		}
		if (empty($this->profile_id))
		{
			$this->setError( JText::_( "Profile Required" ) );
			return false;
		}
		
		return true;
	}
}
