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

class AmbraTableCategoryFields extends AmbraTableXref 
{
	/** 
	 * @param $db
	 * @return unknown_type
	 */
	function AmbraTableCategoryFields ( &$db ) 
	{
		$keynames = array();
		$keynames['field_id']  = 'field_id';
        $keynames['category_id'] = 'category_id';
        $this->setKeyNames( $keynames );
                
		$tbl_key 	= 'field_id';
		$tbl_suffix = 'fields2categories';
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
		if (empty($this->field_id))
		{
			$this->setError( JText::_( "Field Required" ) );
			return false;
		}
		
		return true;
	}
}
