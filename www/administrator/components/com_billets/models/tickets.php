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

class BilletsModelTickets extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$categoryid	= $this->getState('filter_categoryid');
       	$stateid	= $this->getState('filter_stateid');
       	$userid		= $this->getState('filter_userid');
       	$label	 	= $this->getState('filter_labelid');
       	$managerid	= $this->getState('filter_managerid');

        $filter_id_from	= $this->getState('filter_id_from');
        $filter_id_to	= $this->getState('filter_id_to');
       	$filter_user	= $this->getState('filter_user');
		$filter_title	= $this->getState('filter_title');

        $filter_date_from	= $this->getState('filter_date_from');
        $filter_date_to		= $this->getState('filter_date_to');
       	$filter_datetype	= $this->getState('filter_datetype');
		
       	if (strlen($filter)) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.title) LIKE '.$key;
			$where[] = 'LOWER(tbl.description) LIKE '.$key;
			$where[] = 'LOWER(tbl.sender_userid) LIKE '.$key;
			$where[] = 'LOWER(u.email) LIKE '.$key;
			$where[] = 'LOWER(u.name) LIKE '.$key;
			$where[] = 'LOWER(u.username) LIKE '.$key;

       	    // trying to find ticket-messages
            $database = JFactory::getDBO();
            $pre_sql = '';
            if (strlen($userid))
            {
                $query->where("tbl.sender_userid = '$userid'");
                $sql    = " SELECT `id` FROM #__billets_tickets WHERE sender_userid = '$userid'";
                $database->setQuery($sql);
                $result = $database->loadResultArray();
                if ( count( $result ) ) {
                    $pre_sql  = 'AND ticketid IN ( ' . implode ( ", ", $result ) . ' )';
                }
            }           
            $sql    = " SELECT `ticketid` FROM #__billets_messages WHERE `message` LIKE {$key} {$pre_sql}";
            $database->setQuery($sql);
            $result = $database->loadResultArray();
            if ( count( $result ) ) {
                $where[]  = 'tbl.id IN ( ' . implode ( ", ", $result ) . ' )';
            }

			$query->where('('.implode(' OR ', $where).')');
       	}
    	if (strlen($filter_id_from))
        {
			if (strlen($filter_id_to))
        	{
        		$query->where('tbl.id >= '.(int) $filter_id_from);	
        	}
        		else
        	{
        		$query->where('tbl.id = '.(int) $filter_id_from);
        	}
       	}
		if (strlen($filter_id_to))
        {
        	$query->where('tbl.id <= '.(int) $filter_id_to);
       	}
        if (strlen($filter_title))
        {
        	$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_title ) ) ).'%');
        	$query->where('(LOWER(tbl.title) LIKE '.$key.' OR LOWER(tbl.subject) LIKE '.$key.')');
       	}
        if (strlen($filter_user))
        {
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_user ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.sender_userid) LIKE '.$key;
			$where[] = 'LOWER(u.email) LIKE '.$key;
			$where[] = 'LOWER(u.name) LIKE '.$key;
			$where[] = 'LOWER(u.username) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
       	
    	if (strlen($categoryid))
        {
        	Billets::load('BilletsHelperCategory', 'helpers.category' );
        	$categories = BilletsHelperCategory::getChildren( $categoryid );
        	$arr = array( $categoryid );
        	if ($categories !== false)
        	{
				foreach (@$categories as $cat)
				{
					$arr[] = $cat->id;	
				}
        	}
        	$query->where("tbl.categoryid IN ('".implode( "', '", $arr )."') ");
       	}
        if (strlen($stateid))
        {
			Billets::load('BilletsTaxonomies', 'library.taxonomies' );
			$items = BilletsTaxonomies::getTree( 'ticketstates', $stateid, '1' );
        	$arr2 = array();
			foreach (@$items as $item)
			{
				$arr2[] = $item->id;
			}
          	$query->where("tbl.stateid IN ('".implode( "', '", $arr2 )."') ");
       	}
       	
        if (strlen($managerid))
        {
       		$user = JFactory::getUser( $managerid );
			if ($user->get('gid') != '25' )
			{
	        	Billets::load('BilletsHelperManager', 'helpers.manager' );
	        	$categories = BilletsHelperManager::getCategories( $user->id );
	        	$arr = array();
				foreach (@$categories as $cat)
				{
					$arr[] = $cat->id;	
				}
				$query->where("tbl.categoryid IN ('".implode( "', '", $arr )."') ");
			}
       	}
       	
       	if (strlen($userid))
       	{
       		$query->where("tbl.sender_userid = '$userid'");	
       	}       	
       	
        if (strlen($label))
       	{
       		if (intval($label) == '0')
       		{

       		}
			elseif ($label == '-1')
       		{
       			$query->where("tbl.labelid = '0'");
       		}
       		else
       		{
       			$query->where("tbl.labelid = '$label'");	
       		}
       	}

    	if (strlen($filter_date_from))
        {
        	switch ($filter_datetype)
        	{
        		case "closed":
        			$query->where("tbl.closed_datetime >= '".$filter_date_from."'");
        		  break;
        		case "modified":
        			$query->where("tbl.last_modified_datetime >= '".$filter_date_from."'");
        		  break;
        		case "created":
        		default:
        			$query->where("tbl.created_datetime >= '".$filter_date_from."'");		
        		  break;
        	}
       	}
		if (strlen($filter_date_to))
        {
			switch ($filter_datetype)
        	{
        		case "closed":
        			$query->where("tbl.closed_datetime <= '".$filter_date_to."'");
        		  break;
        		case "modified":
        			$query->where("tbl.last_modified_datetime <= '".$filter_date_to."'");
        		  break;
        		case "created":
        		default:
        			$query->where("tbl.created_datetime <= '".$filter_date_to."'");		
        		  break;
        	}
       	}
       	
    }
    
	protected function _buildQueryJoins(&$query)
	{
		$query->join('LEFT', '#__users AS u ON u.id = tbl.sender_userid');
		$query->join('LEFT', '#__billets_userdata AS userdata ON userdata.user_id = tbl.sender_userid');
		$query->join('LEFT', '#__billets_ticketdata AS td ON td.ticketid = tbl.id');
		$query->join('LEFT', '#__billets_ticketstates AS states ON tbl.stateid = states.id');
		$query->join('LEFT', '#__billets_labels AS label ON tbl.labelid = label.id');
		if($this->getState('filter'))
		{
			//$query->join('LEFT', '#__billets_messages AS bodies ON bodies.ticketid = tbl.id');	
		}
	}
	
    protected function _buildQueryGroup(&$query)
    {
        $filter     = $this->getState('filter');
    	if (strlen($filter))
		{
			$query->group('tbl.id');	
		}
    }
	
	protected function _buildQueryFields(&$query)
	{
		$field = array();
		$field[] = " u.name AS user_name ";
		$field[] = " u.username AS user_username ";
		$field[] = " u.email AS user_email ";
		$field[] = " td.* ";
		$field[] = " states.img AS state_image ";
		$field[] = " states.title AS state_title ";
		$field[] = " label.title AS label_title ";
		$field[] = " label.color AS label_color ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );		
		$query->select( $field );
	}
        	
	public function getList($refresh = false)
	{
		Billets::load('BilletsHelperTicket', 'helpers.ticket' );
		Billets::load('BilletsHelperCategory', 'helpers.category' );
		if ($list = parent::getList($refresh))
		{
	        foreach(@$list as $item)
	        {
	            $item->link = 'index.php?option=com_billets&view=tickets&task=view&id='.$item->id;
	            $item->category_title = (!empty($item->categoryid)) ? BilletsHelperCategory::getTitle( $item->categoryid, 'flat' ) : '';
	            $item->articles = BilletsHelperTicket::getArticles( $item->id );
	        }
		}
		return $list;
	}
	
    /**
     * Overriden function, that query for total number of records from database
     * instead of getting count of total rows
     * 
     * @return integer Returns number of rows fetched out of a SQL query
     */
    function getTotal()
    {
        if (empty($this->_total))
        {
            $query	= $this->getQuery();

			// clean query for retriving totals
			$query	= preg_replace('/SELECT.+?FROM/si', 'SELECT COUNT(*) AS total FROM', $query);
			$query	= preg_replace('/GROUP BY.+/i', '', $query);
			$query	= preg_replace('/ORDER BY.+/i', '', $query);

			$database = JFactory::getDBO();
			$database->setQuery( $query );
			$this->_total = $database->loadResult();
        }
        return $this->_total;
    }
	
}
