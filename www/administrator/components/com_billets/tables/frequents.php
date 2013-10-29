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

class BilletsTableFrequents extends BilletsTable 
{
	function BilletsTableFrequents ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 'frequents';
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
		return true;
	}
	
	function reorder( $where='' )
	{
		$database = JFactory::getDBO();
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( 'Frequents', 'BilletsModel' );
		$items = $model->getList();
		$row = new JObject();
		$row->id = '0';
		$items[] = $row;
		foreach (@$items as $item)
		{
			parent::reorder('parentid = '.$database->Quote($item->id) );
		}
	}
	
	function move( $change, $where='' )
	{
		$where = 'parentid = '.$this->_db->Quote($this->parentid);
		return parent::move( $change, $where );
	}
}
