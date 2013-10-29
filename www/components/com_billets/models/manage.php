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
defined('_JEXEC') or die('Restricted access');


Billets::load( 'BilletsModelTickets', 'models.tickets' );

class BilletsModelManage extends BilletsModelTickets 
{
	function getTable($name='', $prefix='BilletsTable', $options = array())
	{
	    // default table for this model is not Manage, but rather Tickets
		if (empty($name)) 
		{
			$name = 'tickets';
		}

		if($table = &$this->_createTable( $name, $prefix, $options ))  {
			return $table;
		}

		JError::raiseError( 0, 'Table ' . $prefix . $name . ' not supported. File not found.' );
		$null = null;
        return $null;
	}
        	
	public function getList()
	{
		//JLoader::import( 'com_billets.helpers.ticket', JPATH_ADMINISTRATOR.DS.'components' );
		//JLoader::import( 'com_billets.helpers.category', JPATH_ADMINISTRATOR.DS.'components' );
		$list = parent::getList();
		
		if (empty($list)) return array();
		
		foreach($list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=manage&task=view&id='.$item->id;
			$item->category_title = BilletsHelperCategory::getTitle( $item->categoryid, 'flat' );
			$item->articles = BilletsHelperTicket::getArticles( $item->id );
		}
		return $list;
	}
}
