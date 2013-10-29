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


Billets::load('BilletsHelperBase', 'helpers._base' );
	
class BilletsHelperManager extends BilletsHelperBase
{
	/**
	 * is this user a manager?
	 * @param $id
	 * @return unknown_type
	 */
	public static function isUser( $userid )
	{
		$success = false;
		
		$userid = intval($userid);
		if (!$userid) {
			return $success;
		}
		
		$user = JFactory::getUser( $userid );
		if ($user->get('gid') == '25' ) {
			$success = true;
		}
		
		$cats = BilletsHelperManager::getCategories( $userid );
		if (!empty($cats)) 
		{
			$success = true;
		}
		
		return $success;
	}
	
	/**
	 * Returns the list of categories a user can view
	 *  
	 * @return unknown_type
	 */
	public static function getCategories( $userid, $type='view' )
	{
		Billets::load('BilletsHelperCategory', 'helpers.category' );
		
		if (empty($userid))
		{
			return array();
		}
	
		$user = JFactory::getUser( $userid );
        if ($user->get('gid') == '25' && $type == 'view') 
        {
	        return BilletsHelperCategory::getAll();
		}
		
        static $instance;
        
        if (empty($instance[$userid]))
        {
        	if (!is_array($instance)) { $instance = array(); }
        				
			JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
	        $instance[$userid] = array();
	        
        	$database = JFactory::getDBO();
	        $query = "
	            SELECT
	                tbl.*
	            FROM
	                #__billets_u2c AS tbl
	            WHERE 1
	                AND tbl.userid = '{$userid}'
	        ";
	        $database->setQuery( $query );
	        $data = $database->loadObjectList();
	        
	        $done = array();
	        foreach (@$data as $d) 
	        {
	        	if (empty($done[$d->categoryid])) 
	        	{
		        	unset($cat);
					$cat = JTable::getInstance( 'Categories', 'BilletsTable' );
					$cat->load( $d->categoryid );
					$d = $cat;
					
		        	$instance[$userid][] = $d;
		        	$done[$d->id] = '1';
		        	
					if ($children = BilletsHelperCategory::getChildren( $d->id, '1' ))
					{
					    foreach (@$children as $child) 
	                    {
	                        if (empty($done[$child->id])) 
	                        {
	                            $instance[$userid][] = $child;      
	                            $done[$child->id] = '1';        
	                        }
	                    }
					}
	        	}
	        }
        }

        return $instance[$userid];
	}

	/**
	 * 
	 * @param $userid
	 * @param $catid
	 * @param $returnObject
	 * @return unknown_type
	 */
	public static function isCategory( $userid, $catid, $returnObject='0', $type='view', $defined='0' ) 
	{
	    $success = false;
		
		if (empty($defined))
		{
			
	        $database = JFactory::getDBO();
	        $cats = BilletsHelperManager::getCategories( $userid, $type );
	        $catids = BilletsHelperBase::getColumn( $cats, 'id' ); 
			
	        if (is_array( $catids ) && in_array( $catid, $catids )) 
	        {
		        $success = true;
	            if ($returnObject == '1') 
	            {
	            	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
					$cat = JTable::getInstance( 'Categories', 'BilletsTable' );
					$cat->load( $catid );
	                $success = $cat;
	            }
	        }			
		}
			else
		{
        	$database = JFactory::getDBO();
	        $query = "
	            SELECT
	                tbl.*, cat.*
	            FROM
	                #__billets_u2c AS tbl
	                LEFT JOIN #__billets_categories AS cat ON tbl.categoryid = cat.id 
	            WHERE 1
	                AND tbl.userid = '{$userid}'
	                AND tbl.categoryid = '{$catid}'
	        ";
	        $database->setQuery( $query );
	        $data = $database->loadObject();
			if ( $data ) 
			{
	            $success = true;
	            if ($returnObject == '1') 
	            {
	                $success = $data;
	            }
	        }
	        
		}
		
        return $success;
	}
	
	/**
	 * 
	 * @param $userid
	 * @param $catid
	 * @return unknown_type
	 */
	public static function addToCategory( $userid, $catid, $emails='1' )
	{
		$success = false;
		$database = JFactory::getDBO();
		
	  	$query = "
	  		INSERT INTO 
	  			#__billets_u2c
			SET 
				`userid` = '{$userid}', 
				`categoryid` = '{$catid}',
				`emails` = '{$emails}' 
		";
		$database->setQuery( $query );
		if ($database->query()) 
		{ 
			$success = true; 
		}
		
		return $success;
	}
	
	/**
	 * 
	 * @param $userid
	 * @param $catid
	 * @return unknown_type
	 */
	public static function removeFromCategory( $userid, $catid )
	{
		$success = false;
		$database = JFactory::getDBO();
		
		$query = "
			DELETE FROM 
				#__billets_u2c
			WHERE 
				`userid` = '{$userid}'
			AND 
				`categoryid` = '{$catid}'
		";
		$database->setQuery( $query );
		if ($database->query()) 
		{ 
			$success = true; 
		}
		
		return $success;		
	}
	
	/**
	 * 
	 * @return 
	 * @param $fieldid Object
	 * @param $catid Object
	 */	
	public static function getsEmails( $userid, $categoryid, $returnObject='' )
	{
		$success = false;
		$database = JFactory::getDBO();
		
		$query = "
			SELECT 
				*
			FROM 
				#__billets_u2c
			WHERE
				`userid` = '{$userid}'
			AND 
				`categoryid` = '{$categoryid}'
			AND
				`emails` = '1'
			LIMIT 1
		";
		$database->setQuery( $query );
		$data = $database->loadObject();
		if (isset($data->emails) && intval($data->emails) == '1' ) { 
			$success = true; 
		}
		
		if ($returnObject == '1') {
			$success = $data;
		}
		return $success;		
	}

	/**
	 * 
	 * @return 
	 * @param $fieldid Object
	 * @param $catid Object
	 */	
	public static function setGetsEmails( $userid, $categoryid, $emails='1' )
	{
		$success = false;
		$database = JFactory::getDBO();

        $query = "
            SELECT
                tbl.*
            FROM
                #__billets_u2c AS tbl
            WHERE 1
                AND tbl.userid = '{$userid}'
                AND tbl.categoryid = '{$categoryid}' 
			LIMIT 1
        ";
        $database->setQuery( $query );
        $data = $database->loadObject();
		
		if (!$data)
		{
			return BilletsHelperManager::addToCategory( $userid, $categoryid, $emails );
		}
		
		$query = "
			UPDATE 
				#__billets_u2c
			SET 
				`emails` = '{$emails}'
			WHERE
				`userid` = '{$userid}'
			AND 
				`categoryid` = '{$categoryid}'
			LIMIT 1
		";
		$database->setQuery( $query );
		if ( $database->query() ) { $success = true; }
		
		return $success;		
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public static function getEmailCategories( $id )
	{
		$database = JFactory::getDBO();		
		
		$where = array();
		$lists = array();
		$nodupes = array();
		
		$items = BilletsHelperManager::getCategories( $id );
		foreach (@$items as $item)
		{
			$where[] = $item->id;	
		}

		$cat_query = " AND u2c.categoryid IN ('".implode( "', '", $where )."') ";
		$email_query = " AND u2c.emails = '1' ";
		
		$query = "
			SELECT 
				u2c.categoryid AS id
			FROM 
				#__billets_u2c AS u2c
			WHERE 1
				{$cat_query}
				{$email_query}
				AND u2c.userid = '{$id}'
		";

		$database->setQuery( $query );
		$data = $database->loadObjectList();

		return $data;
	}

	public static function isTicket( $userid, $ticketid, $returnObject='0' ) 
	{
        $success = false;
        $database = JFactory::getDBO();
        
        $query = "
            SELECT
                *
            FROM
                #__billets_u2t
            WHERE
                `userid` = '{$userid}'
            AND
                `ticketid` = '{$ticketid}'
            LIMIT 1
        ";
        $database->setQuery( $query );
        $data = $database->loadObject();
        if ( $data ) {
            $success = true;
            
            if ($returnObject == '1') {
                $success = $data;
            }
        }
        
        return $success;
	}
	
	/**
	 * 
	 * @param $userid
	 * @param $catid
	 * @return unknown_type
	 */
	public static function addToTicket( $userid, $ticketid )
	{
		$success = false;
		$database = JFactory::getDBO();
		
	  	$query = "
	  		INSERT INTO 
	  			#__billets_u2t
			SET 
				`userid` = '{$userid}', 
				`ticketid` = '{$ticketid}'
		";
		$database->setQuery( $query );
		if ($database->query()) 
		{ 
			$success = true; 
		}
		
		return $success;
	}
	
	/**
	 * 
	 * @param $userid
	 * @param $catid
	 * @return unknown_type
	 */
	public static function removeFromTicket( $userid, $ticketid )
	{
		$success = false;
		$database = JFactory::getDBO();
		
		$query = "
			DELETE FROM 
				#__billets_u2t
			WHERE 
				`userid` = '{$userid}'
			AND 
				`ticketid` = '{$ticketid}'
		";
		$database->setQuery( $query );
		if ($database->query()) 
		{ 
			$success = true; 
		}
		
		return $success;		
	}
}