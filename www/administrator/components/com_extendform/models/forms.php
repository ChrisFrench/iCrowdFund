<?php
/**
* @package		Favorites
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Extendform::load('ExtendformModelBase','models.base');

class ExtendformModelForms extends ExtendformModelBase 
{
	
	protected function _buildQueryWhere(&$query)
    {
    	$filter  		 = $this->getState('filter');
        $filter_title     = $this->getState('filter_title');
       	$filter_component      = $this->getState('filter_component');
    	$filter_form    = $this->getState('filter_form');
    	$filter_xmlfile     = $this->getState('filter_xmlfile');
    	$filter_path     = $this->getState('filter_path ');
    	$filter_formname    = $this->getState('filter_formname');
		$filter_enabled    = $this->getState('filter_enabled');
		
    	      
		
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.title) LIKE '.$key;
            //$where[] = 'LOWER(tbl.type) LIKE '.$key;
      
            $query->where('('.implode(' OR ', $where).')');
        }
        
    	if (strlen($filter_title))
    	{
    		$query->where("tbl.title = '".$filter_title."'");
    	}

        if (strlen($filter_component))
        {
            $query->where("tbl.component = '".$filter_component."'");
        }
    	
    	if (strlen($filter_form))
    	{
    		
    	 $query->where("tbl.form = '".$filter_form."'");
	
		}
		
    	if (strlen($filter_xmlfile))
        {
            $query->where("tbl.xmlfile = '".$filter_xmlfile."'");
        }
          
		    	
       if (strlen($filter_path))
        {
            $query->where("tbl.path = '".$filter_path."'");
        }
	    if (strlen($filter_formname))
        {
            $query->where("tbl.formname = '".$filter_formname."'");
        }
		
		
		if (strlen($filter_enabled))
        {
            $query->where("tbl.enabled = '".$filter_enabled."'");
        }
	  
    }


	function getTable()
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_extendform'.DS.'tables' );
		$table = JTable::getInstance( 'Forms', 'ExtendformTable' );
		return $table;
	}
	
	
	
	
	
	public function getList()
	{
		
		
		$items = parent::getList(); 
	
		foreach(@$items as $item)
		{
			$item->link = 'index.php?option=com_extendform&view=forms&task=edit&id='.$item->id;
			
		}
		
		return $items;
	}
	
	
	
	
}