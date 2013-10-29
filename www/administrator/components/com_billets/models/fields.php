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

class BilletsModelFields extends BilletsModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$enabled	= $this->getState('filter_enabled');
       	$categoryid	= $this->getState('filter_categoryid');
       	$listdisplayed	= $this->getState('filter_listdisplayed');
       	$typeid	= $this->getState('filter_typeid');

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
			$query->where('tbl.published = '.$enabled);
       	}
    	if ( strlen( $categoryid ) ) 
    	{
    	    $root = JTable::getInstance('Categories', 'BilletsTable')->getRoot();
			$query->join('LEFT', '#__billets_f2c AS f2c ON tbl.id = f2c.fieldid');
    		switch( $categoryid ) {
				case $root->id:
					$query->where( 'f2c.categoryid IS NULL' );
					break;
				default:
					$query->where( 'f2c.categoryid = '.$categoryid );
					break;
			}
       	}
        if (strlen($listdisplayed))
        {
          	$query->where('tbl.listdisplayed = '.$listdisplayed);
       	}
        if (strlen($typeid))
        {
          	$query->where('tbl.typeid = '.$typeid);
       	}
    }
        	
	public function getList($refresh = false)
	{
		$list = parent::getList($refresh); 
		foreach(@$list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=fields&task=edit&id='.$item->id;
			$item->link_selectcategories = "index.php?option=com_billets&view=fields&task=selectcategories&tmpl=component&id=".$item->id;
		}
		return $list;
	}
}
