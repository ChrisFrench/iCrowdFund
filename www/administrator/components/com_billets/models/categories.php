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

class BilletsModelCategories extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     	= $this->getState('filter');
        $filter_id_from	= $this->getState('filter_id_from');
        $filter_id_to	= $this->getState('filter_id_to');
        $filter_name	= $this->getState('filter_name');
       	$enabled		= $this->getState('filter_enabled');
       	$parentid		= $this->getState('filter_parentid');

       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.title) LIKE '.$key;
			$where[] = 'LOWER(tbl.description) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
        if (strlen($filter_id_from))
        {
        	$query->where('tbl.id >= '.(int) $filter_id_from);
       	}
		if (strlen($filter_id_to))
        {
        	$query->where('tbl.id <= '.(int) $filter_id_to);
       	}
    	if (strlen($filter_name))
        {
        	$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
        	$query->where('LOWER(tbl.title) LIKE '.$key);
       	}
    	if (strlen($enabled))
        {
        	$query->where('tbl.enabled = '.$enabled);
       	}
        if (strlen($parentid))
        {
        	$parent = $this->getTable();
        	$parent->load( $parentid );
        	if ($parent->isroot == '1')
        	{
        		$query->where("tbl.parentid = '{$parent->id}'" );
        	}
        		elseif (!empty($parent->id))
        	{
        		$query->where('tbl.lft BETWEEN '.$parent->lft.' AND '.$parent->rgt );	
        	}
       	}
       	
       	$query->where('tbl.isroot != 1');
       	$query->where('tbl.lft BETWEEN parent.lft AND parent.rgt');
		
    }
    
	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(&$query)
    {
    	$name = $this->getTable()->getTableName();
    	$query->from($name.' AS tbl');
    	$query->from($name.' AS parent');
    }
    
	protected function _buildQueryFields(&$query)
	{
		$field = array();
		$field[] = " COUNT(parent.id)-1 AS level ";
		$field[] = " CONCAT( REPEAT(' ', COUNT(parent.title) - 1), tbl.title) AS name ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );		
		$query->select( $field );
	}
	
    /**
     * Builds a GROUP BY clause for the query
     */
    protected function _buildQueryGroup(&$query)
    {
    	$query->group('tbl.id');
    }
        	
	public function getList($refresh = false)
	{
		$list = parent::getList($refresh); 
		foreach(@$list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=categories&task=edit&id='.$item->id;
			$item->parent_title = "(To Be Removed)";
			$item->link_selectfields = "index.php?option=com_billets&view=categories&task=selectfields&tmpl=component&id=".$item->id;
			$item->link_selectusers = "index.php?option=com_billets&view=categories&task=selectusers&tmpl=component&id=".$item->id;
		}
		return $list;
	}
}
