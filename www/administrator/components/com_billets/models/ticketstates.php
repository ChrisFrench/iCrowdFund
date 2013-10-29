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


Billets::load( 'BilletsModelTaxonomies', 'models.taxonomies' );

class BilletsModelTicketstates extends BilletsModelTaxonomies 
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
			$where[] = 'LOWER(tbl.img) LIKE '.$key;
			
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
    
	public function getList($refresh = false)
	{
		$list = parent::getList($refresh); 
		foreach(@$list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=ticketstates&task=edit&id='.$item->id;
			unset($parent);
			$parent = JTable::getInstance( 'Ticketstates', 'BilletsTable' );
			$parent->load( $item->parentid );
			$item->parent_title = $parent->title;
		}
		return $list;
	}
	
	public function getParents()
	{
		// TODO get rid of this method and just use the query builder
		$db = JFactory::getDBO();
		
		$query = "
			SELECT 
				DISTINCT (`parentid`) AS id
			FROM 
				#__billets_ticketstates
			ORDER BY
				parentid ASC
		";
		
		$db->setQuery( $query );
		$results = $db->loadObjectList();
		return $results;
	}
}
