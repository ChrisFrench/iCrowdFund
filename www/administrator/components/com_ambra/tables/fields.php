<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Ambra::load( 'AmbraTable', 'tables._base' );

class AmbraTableFields extends AmbraTable 
{
	function AmbraTableFields ( &$db ) 
	{		
		$tbl_key 	= 'field_id';
		$tbl_suffix = 'fields';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if ( empty($this->field_name) ) 
		{
        	$this->setError( JText::_( 'Title is Required' ) );
        	return false;
		}
		
		if (empty($this->ordering))
		{
			$this->ordering = '99';
		}
		return true;
	}
	
	function store( $updateNulls=false )
	{
		$key = $this->getKeyName();
		
		if ( empty($this->$key) ) 
		{
			$database = JFactory::getDBO();
			// if new, check for existence of suggested db_fieldname
			JLoader::import( 'com_ambra.library.field', JPATH_ADMINISTRATOR.DS.'components' );
			$cut_title = AmbraField::cleanTitle( $this->field_name );
			$test_fieldname = strtolower( $cut_title );
			$test_fieldname_orig = $test_fieldname;
			$num = 0;
			
			while (!isset($db_fieldname)) 
			{
				$query = "SHOW COLUMNS FROM #__ambra_userdata "
				. "\n LIKE '".$test_fieldname."' "
				;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				if ($database->getErrorNum()) 
				{
					$this->setError( $database->stderr() );
					return false;
				}
				if (!$rows) {
					$db_fieldname = $test_fieldname;
				} else {
					// if it's not OK, generate a new one
					$test_fieldname = $test_fieldname_orig."_".$num;
					$num++;
				}						
			}	// end while
	
			// create the field in the table
			if ( $this->integer == '1' ) 
			{
				// if the value is forced integer, use int		
				$fieldtype = "INT(11)";
				$this->maxlength = "11";
			} elseif ( $this->typeid == '6' ) {
				$fieldtype = "DATETIME";
				$this->maxlength = "255";
			} elseif ( $this->typeid == '2' || $this->maxlength > "255" ) {
				$fieldtype = "TEXT";
			} else {
				$fieldtype = "VARCHAR(255)";
				$this->maxlength = "255";
			}
	
			$query = "ALTER TABLE #__ambra_userdata ADD `".$db_fieldname."` ".$fieldtype." NOT NULL; " ;
			$database->setQuery( $query );
			if (!$database->query()) 
			{
				$this->setError( $database->stderr() );
				return false;
			}
			$this->db_fieldname = $db_fieldname;
		}

		// TODO If $this->typeid != the typid in the DB, then user has changed the field's type, so do ALTER TABLE to change the ticketdata field definition
		// TODO If $this->integer != the integer value in the DB, then user has changed the field's 'force integer' value, so do ALTER TABLE to change the ticketdata field definition to VARCHAR
				
		return parent::store($updateNulls);
	}
}
