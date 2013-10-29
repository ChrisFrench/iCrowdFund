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

class BilletsTableT2A extends BilletsTable 
{
	/**
	 * 
	 * 
	 * @param $db
	 * @return unknown_type
	 */
	function BilletsTableT2A ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 't2a';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{		
		$db			= JFactory::getDBO();
		$nullDate	= $db->getNullDate();
		if (empty($this->created_datetime) || $this->created_datetime == $nullDate)
		{
			$date = JFactory::getDate();
			$this->created_datetime = $date->toMysql();
		}
		if (empty($this->ticketid))
		{
			$this->setError( JText::_('COM_BILLETS_TICKET_ID_REQUIRED') );
			return false;
		}
		if (empty($this->articleid))
		{
			$this->setError( JText::_('COM_BILLETS_ARTICLE_ID_REQUIRED') );
			return false;
		}
		
		return true;
	}
}
