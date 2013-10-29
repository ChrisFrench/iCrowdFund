<?php
/**
 * @version	1.5
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Wepay::load('WepayModelBase', 'models.base');

class WepayModelCampaigns extends WepayModelBase {
	protected function _buildQueryWhere(&$query) {
		$filter = $this -> getState('filter');
		$filter_id = $this -> getState('filter_id');
		$filter_id_from = $this -> getState('filter_id_from');
		$filter_id_to = $this -> getState('filter_id_to');
		$filter_id_set = $this -> getState('filter_id_set');
		$filter_name = $this -> getState('filter_name');
		$filter_user_id = $this -> getState('filter_user_id');
		$enabled = $this -> getState('filter_enabled');
		$filter_category = $this -> getState('filter_category');
		$filter_fundingstart_date = $this->getState( 'filter_fundingstart_date' );
		$filter_fundingend_date = $this->getState( 'filter_fundingend_date' );
		$groupStates = $this -> getState('filter_group_states');

		if ($filter) {
			$key = $this -> _db -> Quote('%' . $this -> _db -> getEscaped(trim(strtolower($filter))) . '%');

			$where = array();
			$where[] = 'LOWER(tbl.campaign_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.campaign_name) LIKE ' . $key;
			$where[] = 'LOWER(tbl.campaign_description) LIKE ' . $key;

			$query -> where('(' . implode(' OR ', $where) . ')');
		}
		if (strlen($filter_id_from)) {
			if (strlen($filter_id_to)) {
				$query -> where('tbl.campaign_id >= ' . (int)$filter_id_from);
			} else {
				$query -> where('tbl.campaign_id = ' . (int)$filter_id_from);
			}
		}
		if (strlen($filter_id)) {
			$query -> where('tbl.campaign_id = ' . (int)$filter_id);
		}
		if (strlen($filter_id_to)) {
			$query -> where('tbl.campaign_id <= ' . (int)$filter_id_to);
		}
		if (strlen($filter_user_id)) {
			$query -> where('tbl.user_id = ' . (int)$filter_user_id);
		}
		if (strlen($filter_id_set)) {
			$query -> where('tbl.campaign_id IN (' . $filter_id_set . ')');
		}
		if (strlen($filter_name)) {
			$key = $this -> _db -> Quote('%' . $this -> _db -> getEscaped(trim(strtolower($filter_name))) . '%');
			$query -> where('LOWER(tbl.campaign_name) LIKE ' . $key);
		}

		if (strlen($filter_category)) {
			$query -> where('tbl.category_id = ' . $this -> _db -> Quote($filter_category));
		}

		if (strlen($enabled)) {
			$query -> where('tbl.campaign_enabled = ' . $this -> _db -> Quote($enabled));
		}
		
		if ( strlen( $filter_fundingstart_date ) )
        {
            $query->where( "tbl.fundingstart_date <= '" . $filter_fundingstart_date . "'" );
        }
		if ( strlen( $filter_fundingend_date ) )
        {
            $query->where( "tbl.fundingend_date >= '" . $filter_fundingend_date . "'" );
        }

	}

	/**
	 * Builds FROM tables list for the query
	 */
	protected function _buildQueryFrom(&$query) {
		$name = $this -> getTable() -> getTableName();
		$query -> from($name . ' AS tbl');

	}

	protected function _buildQueryFields(&$query) {

		$field = array();

		$query -> select($this -> getState('select', 'tbl.*'));

		$query -> select($field);

	}

	/**
	 * Builds a GROUP BY clause for the query
	 */
	protected function _buildQueryGroup(&$query) {
		//$query->group('tbl.campaign_id');
	}

	/**
	 * Builds a generic SELECT COUNT(*) query
	 */
	protected function _buildResultQuery() {

		// Allow plugins to edit the query object
		//   $suffix = ucfirst( $this->getName() );
		//   $dispatcher = JDispatcher::getInstance();
		//	$dispatcher->trigger( 'onAfterBuildResultQuery'.$suffix, array( &$query ) );

		//    return $query;
	}

	protected function prepareItem(&$item, $key = 0, $refresh = false) {
		$item -> view_link = 'index.php?option=com_tienda&view=campaigns&task=view&id=' . $item -> campaign_id;
		$item -> link = 'index.php?option=com_tienda&view=campaigns&task=edit&id=' . $item -> campaign_id;
		$item -> manage = 'index.php?option=com_tienda&view=campaigns&task=edit&id=' . $item -> campaign_id;
		$item -> stats = 'index.php?option=com_tienda&view=campaigns&task=stats&id=' . $item -> campaign_id;
		if (empty($item -> itemid)) {
			$item -> itemid = '128';
		}
		parent::prepareItem($item,$key,$refresh);
	}

	public function getList($refresh = false) {
		if (empty($this -> _list)) {

			$list = parent::getList($refresh);
			if (empty($list)) {
				return array();
			}
			$groupStates = $this -> getState('filter_group_states');

			if ($groupStates) {
				// If no item in the list, return an array()
				$items = array();
				$items['active'] = array();
				$items['pending'] = array();
				$items['completed'] = array();

				foreach ($list as $item) {

					$type = $this -> checktype($item);
					$items[$type][] = $item;
				}
				$this -> _list = $items;
			} else {
				$this -> _list = $list;
			}
		}

		return $this -> _list;
	}

	public function checktype($item) {
		$date = new DateTime();
		$fundingend_date = new DateTime($item -> fundingend_date);
		if ($item -> campaign_enabled == 1) {
			if ($fundingend_date > $date) {
				return 'active';
			} else {
				return 'completed';
			}

		} else {
			return 'pending';
		}

	}

}
