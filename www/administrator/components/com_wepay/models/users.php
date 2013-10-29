<?php
/**
* @package		Wepay
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


Wepay::load('WepayModelBase','models.base');

class WepayModelUsers extends WepayModelBase 
{
	
	protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
       	$filter_name      = $this->getState('filter_name');
		$filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
		$filter_userid    = $this->getState('filter_userid');
    	$filter_wepay_userid    = $this->getState('filter_wepay_userid');
    	$filter_wepay_access_token     = $this->getState('filter_wepay_access_token ');
    	$filter_wepay_token_type    = $this->getState('filter_wepay_token_type');
		$filter_wepay_expires_in    = $this->getState('filter_wepay_expires_in');
		$filter_oauth_code    = $this->getState('filter_oauth_code');
		$filter_datecreated    = $this->getState('filter_datecreated');
		$filter_enabled    = $this->getState('filter_enabled');
		
		
		
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.name) LIKE '.$key;
           
      
            $query->where('('.implode(' OR ', $where).')');
        }
		if ($filter_name) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.name) LIKE '.$key;
          
      
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
        
    	
    	if (strlen($filter_userid))
    	{
    	
    		
    	 $query->where("tbl.joomla_user_id = '".$filter_userid."'");
	
		}
		
    	if (strlen($filter_datecreated))
        {
            $query->where("tbl.datecreated = '".$filter_datecreated."'");
        }
          
		    
	    
		if (strlen($filter_enabled))
        {
            $query->where("tbl.enabled = '".$filter_enabled."'");
        }
		
	 
    }

	 protected function _buildQueryGroup(&$query)
    {
    }

	/**
     * Builds JOINS clauses for the query
     */
    protected function _buildQueryJoins(&$query)
    {
    $query -> join('LEFT', '#__users AS user ON tbl.joomla_user_id = user.id');	
		
    }
	/**
	 * Builds SELECT fields list for the query
	 */
	protected function _buildQueryFields(&$query)
	{
		$fields = array();
		$fields[] = " user.* ";
		        
		
		
		$query -> select($this -> getState('select', 'tbl.*'));
		$query -> select($fields);
	}
	
	
	protected function prepareItem( &$item, $key=0, $refresh=false )
	{
			$item->link = 'index.php?option=com_wepay&view=users&task=edit&id='.$item->users_id;
			
			parent::prepareItem($item, $key, $refresh );
	    
	}
	
	
}