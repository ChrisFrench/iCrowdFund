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

class BilletsTableFields extends BilletsTable 
{
	/**
	 * 
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function BilletsTableFields ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 'fields';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if ( !$this->title ) 
		{
        	$this->setError( JText::_('COM_BILLETS_TITLE_IS_REQUIRED') );
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
		$database = JFactory::getDBO();
			
		
		if ( empty($this->$key) ) 
		{
			// if new, check for existence of suggested db_fieldname
			Billets::load('BilletsField', 'library.field' );
			$cut_title = BilletsField::cleanTitle( $this->title );
			$test_fieldname = strtolower( "f_".$cut_title );
			$test_fieldname_orig = $test_fieldname;
			$num = 0;
			
			while (!isset($db_fieldname)) 
			{
				$query = "SHOW COLUMNS FROM #__billets_ticketdata "
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
	
			$query = "ALTER TABLE #__billets_ticketdata ADD `".$db_fieldname."` ".$fieldtype." NOT NULL; " ;
			$database->setQuery( $query );
			if (!$database->query()) 
			{
				$this->setError( $database->stderr() );
				return false;
			}
			$this->db_fieldname = $db_fieldname;
		} else {
		//This is an update to an existing column so we need to check for any changes to the field's structure
		
		//Load an unmodified copy of this field from the DB so we can compare it to the one being saved
			//get the model and then the table
			JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
			$model = JModel::getInstance( 'Fields', 'BilletsModel' );
			$oldField = $model->getTable('fields');
			//load the field
			if(!$oldField->load($this->$key)){
				$this->setError( JText::_('COM_BILLETS_ERROR_LOADING_EXISTING_FIELD_RECORD')  );
				return false;				
			}
			
			if ($this->integer != $oldField->integer){
				$intChanged = 1;
			} else {
				$intChanged = 0;
			}
			
			// If $this->integer != the integer value in the DB, then user has changed the field's 'force integer' value, so do ALTER TABLE to change the ticketdata field definition to VARCHAR
			if($intChanged && ($this->integer == '1')){
				// if the value is forced integer, use int		
				$fieldtype = "INT(11)";
				$this->maxlength = "11";
			}// If $this->typeid != the typid in the DB, then user has changed the field's type, so do ALTER TABLE to change the ticketdata field definition
			elseif (($this->typeid != $oldField->typeid && $this->integer != '1') || ($intChanged && ($this->integer != '1'))){
				if ( $this->typeid == '6' ) {
					$fieldtype = "DATETIME";
					$this->maxlength = "255";
				} elseif ( $this->typeid == '2' || $this->maxlength > "255" ) {
					$fieldtype = "TEXT";
				} else {
					$fieldtype = "VARCHAR(255)";
					$this->maxlength = "255";
				}
		
			}
			
			//I used an elsif above, because if someone has changed $this->typeid but NOT $this->integer and
			// $this->integer = 1 then we don't need to make any changes to the DB structure.
			// It is also why there is an if (isset($fieldtype)) around the alter table statement below
		
			if (isset($fieldtype)){
				$query = "ALTER TABLE #__billets_ticketdata MODIFY `".$this->db_fieldname."` ".$fieldtype." NOT NULL; " ;
				$database->setQuery( $query );
				if (!$database->query()) 
				{
					$this->setError( $database->stderr() );
					return false;
				}	
			}
			 
			
			
			
		}
		
		return parent::store($updateNulls);
	}
}
