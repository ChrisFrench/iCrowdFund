<?php
/**
* @package		Messagebottle
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
if ( !class_exists('Messagebottle') ) 
             JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
        

Messagebottle::load('MessagebottleModelBase','models.base');

class MessagebottleModelEmails extends MessagebottleModelBase 
{
        public $cache_enabled = false;


	protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
       	$filter_name      = $this->getState('filter_name');
		$filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_title    = $this->getState('filter_title');
		$filter_body    = $this->getState('filter_body');
    	$filter_scope_id    = $this->getState('filter_scope_id');
		$filter_template_id    = $this->getState('filter_template_id');
        $filter_object_id    = $this->getState('filter_object_id');
		$filter_sent   = $this->getState('filter_sent');
        $filter_senddate   = $this->getState('filter_senddate');
        $filter_sentdate    = $this->getState('filter_sentdate');

        $filter_datecreated    = $this->getState('filter_datecreated');
        $filter_datemodified    = $this->getState('filter_datemodified');
		$filter_enabled    = $this->getState('filter_enabled');
		
		
		
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.email_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.title) LIKE '.$key;
      
            $query->where('('.implode(' OR ', $where).')');
        }
		if ($filter_name) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(e.email_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.title) LIKE '.$key;
      
            $query->where('('.implode(' OR ', $where).')');
        }
		
		 if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.email_id >= '.(int) $filter_id_from);  
            }
                else
            {
                $query->where('tbl.email_id = '.(int) $filter_id_from);
            }
        }
        
        if (strlen($filter_id_to))
        {
            $query->where('tbl.email_id <= '.(int) $filter_id_to);
        }
        
    	
		
    	if (strlen($filter_datecreated))
        {
            $query->where("tbl.datecreated = '".$filter_datecreated."'");
        }
        if (strlen($filter_datemodified))
        {
            $query->where("tbl.datemodified = '".$filter_datemodified."'");
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
 //   $query -> join('LEFT', '#__management_extensions AS e ON  e.ext_id = tbl.ext_id ');  
	
    }
	/**
	 * Builds SELECT fields list for the query
	 */
	protected function _buildQueryFields(&$query)
	{
		$fields = array();
	 //   $fields[] = " e.ext_name, e.ext_image, e.ext_element, e.ext_version, e.ext_website, e.ext_type, e.ext_developer ";

        
     

        $query -> select($this -> getState('select', 'tbl.*'));
        $query -> select($fields);
	}
	
	
	protected function prepareItem( &$item, $key=0, $refresh=false )
	{
			$item->link = 'index.php?option=com_messagebottle&view=emails&task=edit&id='.$item->email_id;
			
			parent::prepareItem($item, $key, $refresh );
	    
	}


    
	
	
}