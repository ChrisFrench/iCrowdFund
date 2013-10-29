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

class BilletsTableTicketdata extends BilletsTable 
{
	function BilletsTableTicketdata ( &$db ) 
	{
		$tbl_key 	= 'ticketdata_id';
		$tbl_suffix = 'ticketdata';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		// TODO for each varchar & text field, do HTML filtering
		
		// get all fields required for this _categoryid
		Billets::load( 'BilletsHelperCategory', 'helpers.category' );
		$fields = BilletsHelperCategory::getFields( $this->_categoryid, '1' );
		foreach (@$fields as $field)
		{
			$name = $field->db_fieldname;
			if (empty($this->$name))
			{
				$this->setError( "'$field->title' " . JText::_('COM_BILLETS_REQUIRED') );
				return false;
			}	
		}
		
		return true;
	}
	
}
