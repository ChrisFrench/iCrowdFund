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
class BilletsModelTaxonomies extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$enabled	= $this->getState('filter_enabled');
		$parentid	= $this->getState('filter_parentid');

       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.title) LIKE '.$key;
			$where[] = 'LOWER(tbl.description) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
    	if (strlen($enabled))
        {
        	$query->where('tbl.enabled = '.$enabled);
       	}       	
		if (strlen($parentid))
        {
        	$query->where('tbl.parentid = '.$parentid);
       	}
    }
    
    protected function _buildQueryOrder(&$query)
    {
       	$order      = $this->_db->getEscaped( $this->getState('order') );
       	$direction  = $this->_db->getEscaped( strtoupper($this->getState('direction') ) );
		if ($order)
       	{
	       	switch ($order)
	       	{
	       		case "tbl.parentid":
	       			$query->order("tbl.parentid $direction");
	       			$query->order("tbl.ordering ASC");
	       		  break;
	       		case "tbl.ordering":
	       			$query->order("tbl.parentid ASC");
	       			$query->order("tbl.ordering $direction");
	       		  break;
	       		default:
	       			$query->order("$order $direction");
	       		  break;	
	       	}
       	}
    }
}
