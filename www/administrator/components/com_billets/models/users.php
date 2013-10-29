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


class BilletsModelUsers extends BilletsModelBase 
{	
    protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$block	 	= $this->getState('filter_block');

       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.name) LIKE '.$key;
			$where[] = 'LOWER(tbl.username) LIKE '.$key;
			$where[] = 'LOWER(tbl.email) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
        if (strlen($block))
        {
        	$query->where('tbl.block = '.$block);
       	}
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__billets_userdata AS userdata ON userdata.user_id = tbl.id');
    }

    protected function _buildQueryFields(&$query)
    {
        $field = array();
        $field[] = " userdata.* ";
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $field );
    }
    
	public function getList($refresh = false)
	{
		$list = parent::getList($refresh); 
		foreach($list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=users&task=view&id='.$item->id;
			$item->link_selectcategories = "index.php?option=com_billets&view=users&task=selectcategories&tmpl=component&id=".$item->id;
			$item->link_createticket = 'index.php?option=com_billets&view=tickets&view=tickets&layout=form&userid='.$item->id;
		}
		return $list;
	}
}
