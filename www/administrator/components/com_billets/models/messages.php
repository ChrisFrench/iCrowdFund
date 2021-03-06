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

Billets::load( 'BilletsModelBase', 'models._base' );

class BilletsModelMessages extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$ticketid	= $this->getState('filter_ticketid');
       	$userid		= $this->getState('filter_userid');

       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.userid_from) LIKE '.$key;
			$where[] = 'LOWER(tbl.username_from) LIKE '.$key;
			$where[] = 'LOWER(tbl.subject) LIKE '.$key;
			$where[] = 'LOWER(tbl.message) LIKE '.$key;
			$where[] = 'LOWER(u.email) LIKE '.$key;
			$where[] = 'LOWER(u.name) LIKE '.$key;
			$where[] = 'LOWER(u.username) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
    	if (strlen($ticketid))
        {
          	$query->where('tbl.ticketid = '.$ticketid);
       	}
        if (strlen($userid))
        {
          	$query->where('tbl.userid_from = '.$userid);
       	}
    }
    
	protected function _buildQueryJoins(&$query)
	{
		$query->join('LEFT', '#__users AS u ON u.id = tbl.userid_from');
		$query->join('LEFT', '#__billets_tickets AS ticket ON tbl.ticketid = ticket.id');
	}
	
	protected function _buildQueryFields(&$query)
	{
		$field = array();
		$field[] = " u.name AS user_name ";
		$field[] = " u.username AS user_username ";
		$field[] = " u.email AS user_email ";
		$field[] = " ticket.categoryid AS ticket_categoryid ";
		$field[] = " ticket.title AS ticket_title ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );		
		$query->select( $field );
	}
        	
	public function getList($refresh = false)
	{
		Billets::load('BilletsHelperCategory', 'helpers.category' );
		$list = parent::getList($refresh);
		foreach(@$list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=tickets&task=view&id='.$item->ticketid;
			$item->category_title = BilletsHelperCategory::getTitle( $item->ticket_categoryid );
			$item->authorimage = "<img src='".JURI::root()."/media/com_billets/images/comment.png'>";
			
			$name = "";
	        $config = Billets::getInstance();
			$name_display = $config->get( 'display_name', '1');
			if ($name_display == '3') 
			{ 
				$name = $item->user_email; 
			} 
			elseif($name_display == '2') 
			{ 
				$name = $item->user_username; 
			}
			else
			{ 
				$name = $item->user_name; 
			}
			$item->name = $name;
		}
		return $list;
	}
}
