<?php
/**
 * @version    1.5
 * @package    Tienda
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class modTiendaCampaignsHelper extends JObject
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
	}


	function getCampaigns() {
		Tienda::load('TiendaTableCampaigns', 'tables.campaigns');
		Tienda::load('TiendaModelCampaigns', 'models.campaigns');
		// get the model
		$model = DSCModel::getInstance( 'Campaigns', 'TiendaModel' );
		$model->emptyState();
		if($this->params->get('filter_category')) {
		$model->setState('filter_category', $this->params->get('filter_category')); 
		}

		$order = $this->params->get('order');
		$direction = $this->params->get('direction', 'ASC');
		switch ($order)
		{
			case "2":
			case "name":
				$model->setState('order', 'tbl.campaign_name');
				break;
			case "1":
			case "created":
				$model->setState('order', 'tbl.created_date');
				break;
			case "0":
			case "ordering":
			default:
				$model->setState('order', 'tbl.campaign_raised');
				break;
		}
		$model->setState('direction', $direction);

		$model->setState('filter_published', '1'); 
		$model->setState('filter_enabled', '1');
		$model->setState('filter_active', '1');
		$model->setState('filter_group_states', 0);
		//setting refresh to true to disable caching on module items so that they can  always up to date
		$items = $model->getList(true);

	return $items;
	}




	/**
	 * Sample use of the products model for getting products with certain properties
	 * See admin/models/products.php for all the filters currently built into the model
	 *
	 * @param $parameters
	 * @return unknown_type
	 */
	 


	private function getUserGroups()
	{
		$user = JFactory::getUser();
		$database = JFactory::getDBO();
		Tienda::load( 'TiendaQuery', 'library.query' );
		$query = new TiendaQuery();
		$query->select( 'tbl.group_id' );
		$query->from('#__tienda_usergroupxref AS tbl');
		$query->join('INNER', '#__tienda_groups AS g ON g.group_id = tbl.group_id');
		$query->where("tbl.user_id = ".(int) $user->id);
		$query->order('g.ordering ASC');

		$database->setQuery( (string) $query );
		return $database->loadResultArray();
	}
}
?>
