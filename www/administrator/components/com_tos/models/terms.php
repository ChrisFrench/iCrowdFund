<?php
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

if ( !class_exists('Tos') ) 
             JLoader::register( "Tos", JPATH_ADMINISTRATOR."/components/com_tos/defines.php" );
        

Tos::load('TosModelBase','models.base');

class TosModelTerms extends TosModelBase 
{
	
	
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
       	$filter_name      = $this->getState('filter_name');
        $filter_terms_title      = $this->getState('filter_terms_title');
        $filter_terms      = $this->getState('filter_terms');
		$filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
		$filter_user_id    = $this->getState('filter_user_id');
    	$filter_scope_id    = $this->getState('filter_scope_id');
		$filter_created_date    = $this->getState('filter_created_date');
    	$filter_expires_date     = $this->getState('filter_expires_date');
    	$filter_modified_date     = $this->getState('filter_modified_date ');
		$filter_enabled    = $this->getState('filter_enabled');
		
		
		
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.terms) LIKE '.$key;
            $where[] = 'LOWER(tbl.terms_title) LIKE '.$key;
           
      
            $query->where('('.implode(' OR ', $where).')');
        }
		if ($filter_name) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.terms_title ) LIKE '.$key;
          
      
            $query->where('('.implode(' OR ', $where).')');
        }
        if ($filter_terms_title) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_terms_title ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.terms_title ) LIKE '.$key;
          
      
            $query->where('('.implode(' OR ', $where).')');
        }
        if ($filter_terms) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_terms ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.terms ) LIKE '.$key;
          
      
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
        
    	
    	if (strlen($filter_user_id ))
    	{
    	
    		
    	 $query->where("tbl.user_id = '".$filter_user_id ."'");
	
		}


        if (strlen( $filter_scope_id ))
        {
            $query->where("tbl.scope_id= '". $filter_scope_id ."'");
        }
		
    	if (strlen($filter_created_date))
        {
            $query->where("tbl.created_date= '".$filter_created_date."'");
        }
        
        if (strlen($filter_expires_date))
        {
            $query->where("tbl.expires_date= '".$filter_expires_date."'");
        }

        if (strlen($filter_modified_date))
        {
            $query->where("tbl.modified_date= '".$filter_modified_date."'");
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
   // $query -> join('LEFT', '#__users AS user ON tbl.user_id = user.id');	
		
    }
	/**
	 * Builds SELECT fields list for the query
	 */
	protected function _buildQueryFields(&$query)
	{
		$fields = array();
	//	$fields[] = " user.* ";
		        
		
		
		$query -> select($this -> getState('select', 'tbl.*'));
	//$query -> select($fields);
	}
	
	
	protected function prepareItem( &$item, $key=0, $refresh=false )
	{
			$item->link = 'index.php?option=com_tos&view=terms&task=edit&id='.$item->terms_id;
			
			parent::prepareItem($item, $key, $refresh );
	    
	}
	
	
}