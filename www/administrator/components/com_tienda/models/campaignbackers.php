<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Tienda::load( 'TiendaModelBase', 'models._base' );

class TiendaModelCampaignBackers extends TiendaModelBase
{   
  public $cache_enabled = true;
  
    protected function _buildQueryWhere(&$query)
    {
        $filter     	= $this->getState('filter');
		$filter_id     	= $this->getState('filter_id');
		$filter_userid = $this->getState('filter_userid');
		$filter_orderid = $this->getState('filter_orderid');
        $filter_id_from	= $this->getState('filter_id_from');
        $filter_id_to	= $this->getState('filter_id_to');
        $filter_name	= $this->getState('filter_name');
       	$enabled		= $this->getState('filter_enabled');
       
       	if ($filter)
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.campaign_id) LIKE '.$key;
			

			$query->where('('.implode(' OR ', $where).')');
       	}
		if ($filter_id)
       	{
			$query->where('tbl.campaign_id = '.(int) $filter_id);
       	}
		if ($filter_userid)
       	{
			$query->where('tbl.user_id = '.(int) $filter_userid);
       	}
		
        if (strlen($filter_id_from))
        {
        	if (strlen($filter_id_to))
        	{
        		$query->where('tbl.campaign_id >= '.(int) $filter_id_from);
        	}
        		else
        	{
        		$query->where('tbl.campaign_id = '.(int) $filter_id_from);
        	}
       	}
		if (strlen($filter_id_to))
        {
        	$query->where('tbl.campaign_id <= '.(int) $filter_id_to);
       	}

		if (strlen($filter_orderid))
        {
        	$query->where('tbl.order_id = '.(int) $filter_orderid);
       	}

    	if (strlen($filter_name))
        {
        	$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
        	$query->where('LOWER(tbl.campaign_name) LIKE '.$key);
       	}
    	if (strlen($enabled))
        {
        	$query->where('tbl.campaign_enabled = '.$this->_db->Quote($enabled));
       	}
	
    }

	/**
     * Builds FROM tables list for the query
     */
   protected function _buildQueryFrom(&$query)
    {
    	$name = $this->getTable()->getTableName();
    	$query->from($name.' AS tbl');
		
    	
    }
	 protected function _buildQueryJoins( &$query )
    {
        $query->join( 'LEFT', '#__users AS u ON u.id = tbl.user_id' );
        if( $this->getState('settings.orders', 0) ) {
        	$query -> join( 'LEFT', "#__tienda_orders as tbl_order ON tbl_order.order_id = tbl.order_id" );
        }
    }

	protected function _buildQueryFields(&$query)
	{
      
		$field = array();
		$field[] = " u.* ";
	 
		$query->select( $this->getState( 'select', 'DISTINCT tbl.user_id' ) );
		$query->select( $field );
	}

    /**
     * Builds a GROUP BY clause for the query
     */
    protected function _buildQueryGroup(&$query)
    {
    	//$query->group('tbl.campaign_id');
    }
    
	/**
     * Builds a generic SELECT COUNT(*) query
     */
    protected function _buildResultQuery()
    {
    
        
        
       // Allow plugins to edit the query object
     //   $suffix = ucfirst( $this->getName() );
     //   $dispatcher = JDispatcher::getInstance();
	//	$dispatcher->trigger( 'onAfterBuildResultQuery'.$suffix, array( &$query ) );

    //    return $query;
    }

	
	public function getListIDs($refresh = false)
	{
		$list = parent::getList($refresh);
	
		// If no item in the list, return an array()
        if( empty( $list ) ){
        	return array();
        }
		$array = array();
		foreach($list as $item)
		{
		  
			$array[] = $item->campaign_id; 
		}
		return $array;
	}
	
	public function getCampaignsList($refresh = false) {
		$list = $this->getListIDs($refresh);
		if(!empty($list)) {
			echo count($list);
		$set = implode(',', $list);
		Tienda::load( 'TiendaModelCampaigns', 'models.campaigns' );
		$model = JModel::getInstance( 'Campaigns', 'TiendaModel' );
		$model->setState( 'filter_id_set', $set );   
		
	
		$campaigns = $model->getList($refresh);} else{
			$campaigns = array();
		}
		return $campaigns;
	}
}
