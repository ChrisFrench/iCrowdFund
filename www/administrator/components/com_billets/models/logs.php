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

class BilletsModelLogs extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     		  = $this->getState('filter');
        $filter_id_from		  = $this->getState('filter_id_from');
        $filter_id_to		  = $this->getState('filter_id_to');
        $filter_object_id	  = $this->getState('filter_object_id');
       	$filter_object_type   = $this->getState('filter_object_type');
       	$filter_property_name = $this->getState('filter_property_name');

       	if ( $filter ) 
       	{
			$key	= $this->_db->Quote( '%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%' );

			$where = array();
			$where[] = 'LOWER(tbl.log_id) LIKE '.$key;
			$where[] = 'LOWER(tbl.property_name) LIKE '.$key;
			$where[] = 'LOWER(tbl.object_type) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
       	
        if ( strlen( $filter_id_from ) )
        {
        	$query->where('tbl.log_id >= '.(int) $filter_id_from);
       	}
		
       	if ( strlen( $filter_id_to ) )
        {
        	$query->where('tbl.log_id <= '.(int) $filter_id_to);
       	}
    	       	
    	if ( strlen( $filter_object_id ) )
        {
        	$query->where( 'tbl.filter_object_id = '.$filter_object_id );
       	}
       	
    	if ( strlen( $filter_object_type ) )
        {
        	$query->where( 'tbl.filter_object_type = '.$filter_object_type );
       	}
       	    
       	if ( strlen( $filter_property_name ) )
        {
        	$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_property_name ) ) ).'%');
        	$query->where( 'LOWER(tbl.property_name) LIKE '.$key );
       	}
    }
    
	protected function _buildQueryJoins(&$query)
	{
		$query->join('LEFT', '#__users AS u ON u.id = tbl.user_id');
	}
        
	protected function _buildQueryFields(&$query)
	{
		$field = array();
		$field[] = " u.name AS user_name ";
		$field[] = " u.username AS user_username ";
		$field[] = " u.email AS user_email ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );		
		$query->select( $field );
	}	  	
}
