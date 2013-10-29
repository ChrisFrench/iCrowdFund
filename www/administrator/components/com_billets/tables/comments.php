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

class BilletsTableComments extends BilletsTable 
{
	/**
	 * 
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function BilletsTableComments ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 'comments';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{		
		$db			= JFactory::getDBO();
		$nullDate	= $db->getNullDate();
		if (empty($this->datetime) || $this->datetime == $nullDate)
		{
			$date = JFactory::getDate();
			$this->datetime = $date->toMysql();
		}

		if (empty($this->message))
		{
			$this->setError( JText::_('COM_BILLETS_MESSAGE_REQUIRED') );
			return false;
		}
        
		return true;
	}
}
