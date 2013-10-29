<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Daniele Rosario
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Tienda::load( 'TiendaPluginBase', 'library.plugins._base' );

class plgTiendaCrowdFunding extends TiendaPluginBase
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename, 
	 *                         forcing it to be unique 
	 */
	var $_element = 'crowdfunding';
	
	function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}

	
	function onBeforeDisplayAdminComponentTienda() {
		DSC::load('DSCMenu', 'library.menu');
		
		$url = 'index.php?option=com_tienda&view=campaigns';
		
		
		$bar = DSCMenu::getInstance('submenu');
		
		$bar -> addEntry('Campaigns',  $url);
		
		$bar = DSCMenu::getInstance('leftmenu_campaign');
		$bar -> addEntry('Campaigns',  $url);
		$url = 'index.php?option=com_categories&extension=com_tienda.campaigns';
		
		$bar = DSCMenu::getInstance('leftmenu_campaign');
		$bar -> addEntry('Campaigns Categories',  $url);
		
	}
	
	function doCompletedOrderTasks ($order_id) {
		Tienda::load('TiendaModelOrders', 'models.orders');
		$model = JModel::getInstance( 'Orders', 'TiendaModel' );
        $model->setId( $order_id );
        $order = $model->getItem();

        
		  foreach ($order->orderitems as $orderitem)
     	   {
        	$model = JModel::getInstance( 'Products', 'TiendaModel' );
            $model->setId( $orderitem->product_id );
            $product = $model->getItem();
			
			if($product->product_id) {
				Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
				$product->campaign = TiendaHelperCampaign::getCampaignFromProduct($product->product_id);
				if($product->campaign->campaign_id && $order->anonymous == 0) {
				$backer = TiendaHelperCampaign::campaignBackersStore($product->campaign->campaign_id, $product->campaign->user_id, $order->order_id , $product->product_id);
				}
				//TODO make the campaigns raised amount increase based off the order?? do we do this here? 
				$table = JTable::getInstance( 'Campaigns', 'TiendaTable' );
				$table->load($product->campaign->campaign_id);
				$table->campaign_raised = $table->campaign_raised + $orderitem->orderitem_final_price;
				$table->store();

				$this->onAddUserToFollower($order->user_id, $product);

			}
		   }
		
	}

	function onAddUserToFollower($user_id, $product) {
		
		JLoader::register( 'FavoritesTableItems', JPATH_ADMINISTRATOR.'/components/com_favorites/tables/items.php');
	    
		$table = JTable::getInstance('Items', 'FavoritesTable');
		$keys = array('user_id' => $user_id, 'object_id' => $product->campaign->campaign_id);
		$table->load($keys);
		$table->user_id =  $user_id;
		$table->object_id = $product->campaign->campaign_id;
		$table->name = $product->campaign->campaign_name;
		$table->enabled = 1;
		$table->scope_id = 1;
		$table->store();

	}

	

	
	function onPrepareProducts ($row) {
		Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
		//TODO do we need the entire object?
		$row->campaign = TiendaHelperCampaign::getCampaignFromProduct($row->product_id);
		if(@$row->campaign->wepay_account_id)
			$row->campaign->wepay = $this->wepay($row->campaign);
		if(@$row->campaign->wepay->wepay_account_id)
			$row->vendor_id = $row->campaign->wepay->wepay_account_id;
		//do query to get the number of people who bought this item
	}
	function onPrepareCarts ($row) {
	
		Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
		//TODO do we need the entire object?
		$row->campaign = TiendaHelperCampaign::getCampaignFromProduct($row->product_id);

		//do query to get the number of people who bought this item
	}
	function onPrepareCampaignChecks($row){
		$object = new JObject();
		$object->title = 'Wepay Account';
		$object->status = false;
		$object->msg = '';

		if($row->wepay_account_id) {
 		$object->status = true;
 		$object->msg = 'Wepay account Valid';
		}
		$object->edit_link = JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);
		
		
		return $object;
	}
	
	function onPrepareCampaigns ($row) {
		
		if($row->campaign_id) {
		$row->levels = $this->levels($row);
		//mapping the category
		//Mapping Backers
		$row->backers = $this->backers($row);
		//mapping the articles
	
		$row->category = $this->category($row);
		
		
		$row->updates = $this->updates($row);
		$row->owner = $this->owner($row);
		if($row->wepay_account_id) {
			$row->wepay = $this->wepay($row);
		}
		}
		$row->fundingstart = new DateTime($row->fundingstart_date);
		$row->fundingend = new DateTime($row->fundingend_date);
		
		return $row;
	}


	function onPrepareOrders ($row) {
		if(isset($row->campaign_id) && !empty($row->campaign_id)) {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
		Tienda::load( 'TiendaModelCampaigns', 'models.campaigns' );
		$model = JModel::getInstance( 'Campaigns', 'TiendaModel' );
		$row->campaign = $model->getItem($row->campaign_id);	
		} else {
			$row->campaign = 'Undefined';
		}
		
	}



	function levels($row) {
		//JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
		JModel::addIncludePath( JPATH_SITE.'/components/com_tienda/models' );
		// get the products model
		$model = JModel::getInstance( 'Products', 'TiendaModel' );
		
        $ids = $this->getCampaignProducts($row);
		
		if($ids) {
	    $filter_id_set = array();
	    foreach($ids as $id){ $filter_id_set[] = $id->product_id; }
	 	$filter_id_set = implode(",", $filter_id_set);
	    $model->setState('filter_id_set',$filter_id_set);
		$model->setState('order','price');
		$model->setState('direction','asc');
		$model->setState('filter_enabled','1');
	    return $model->getList();
		}
	}


	function category($row) {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_categories/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_categories/models' );
		$model = JModel::getInstance('Category','CategoriesModel');
		return $model->getItem($row->category_id);
	}


	function updates($row) {
		$app = JFactory::getApplication();
		if(!$app->isAdmin()) {
		//JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_content/tables' );
		JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		$model->setState('filter.published', 1);
		$appParams = $app->getParams();
		$model->setState('params', $appParams);
		$model->setState('filter.category_id',$row->content_cat_id);
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);
		return  $model->getItems();
		}
	}
	
	function backers($row) {
		
		//Mapping Backers
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
		Tienda::load( 'TiendaModelCampaignBackers', 'models.campaignbackers' );
		$model = JModel::getInstance( 'CampaignBackers', 'TiendaModel' );
		$model->setState('filter_id', $row->campaign_id);
		return $model->getList();
	}
	function wepay($row) {
		if(empty($row->wepay_account_id))
			return NULL;
		//Mapping Backers
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_wepay/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_wepay/models' );
		$model = JModel::getInstance( 'Accounts', 'WepayModel' );
		
		//$model->setState('filter_id', $row->wepay_account_id);
		$item =  $model->getItem($row->wepay_account_id);
		
		return $item;
	}


	function owner($row) {

		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
        $model = JModel::getInstance('Users', 'AmbraModel');
        $model->setId( $row->user_id );
        return $model->getItem();
		
	}
	/**
	 * Adds the levels of a campaign
	 * Enter description here ...
	 * @param unknown_type $tabs
	 * @param unknown_type $row
	 */
	
	function onAfterDisplayCampaignFormRightColumn(  $row )
	{
		if(!@$row->campaign_id) 
		return;
		//var_dump($row);
		$vars = $this->getCampaignProducts($row);
	
		if(@$vars)
		{
				$html = $this->_getLayout( 'products', $vars );
				echo $html;
			
		}
		
		$html = $this->_getLayout( 'products_form', $vars );
				echo $html;
		
		
	}
	
	function getCampaignProducts(  $row)
	{
		if(!$row->campaign_id) 
		return;

		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
		Tienda::load( 'TiendaModelCampaignProducts', 'models.campaignproducts' );
		$model = JModel::getInstance( 'CampaignProducts', 'TiendaModel' );
		
		$model->setState( 'filter_id', $row->campaign_id );   
		//$model->setState( 'filter_id_to', $row->campaign_id );   
	
		$products = $model->getProductsList();
		
		return $products;
	}
	
	
	/**
	 * adds a tab with Extra Fields on products if needed
	 * Enter description here ...
	 * @param unknown_type $tabs
	 * @param unknown_type $row
	 */
	 
	 
	function onAfterDisplayProductFormTabs( $tabs, $row )
	{
		if(@$row->product_id)
		{
			$vars = new JObject( );
			$vars->tabs = $tabs;
			$vars->row = $row;
			
				
				$html = $this->_getLayout( 'product', $vars );
				echo $html;
			
		}
	}
	function onDisplayProductFormTabs( $tabs, $row )
	{
		if(@$row->product_id)
		{
			$vars = new JObject( );
			$vars->tabs = $tabs;
			$vars->row = $row;
			
			// Get extra fields for products
			//$fields = $this->getCampaignsProductHTML( 'campaign', $row->product_id, true, array( 0, 1 ) );
			
			// If there are any extra fields, show them as an extra tab
			//if ( count( $tabs ) )
			//{
				$html = '';
				$html .= '<li class=""><a href="#campaigns" data-toggle="tab"> '.JText::_('Campaigns') .'</a></li>';
				echo $html;
			//}
		}
	}
	
	
	function onGetAdditionalOrderitemKeyValues( $item )
	{
		
	}
	
	/**
	 * Adds eav details if the user has entered them 
	 * in the order view
	 *
	 * @param unknown_type $i
	 * @param unknown_type $item
	 */
	function onDisplayOrderItem( $i, $item )
	{
	
	}
	
	/**
	 * Validation
	 * 
	 * @param unknown_type $item
	 * @param unknown_type $values
	 */
	function onValidateAddToCart($item, $values)
	{
		//we only every want one item in the cart, so we delete everything and add the new item	
		Tienda::load( 'TiendaHelperCarts', 'helpers.cart' );
		Tienda::load( 'TiendaHelperCampaign', 'helpers.campaign' );
		$helper = new TiendaHelperCarts();
		$session = JFactory::getSession();
		
		Tienda::load( 'TiendaHelperCampaign', 'helpers.campaign' );
		$campaign = TiendaHelperCampaign::getCampaignFromProduct($item->product_id);
		$wepay = $this->wepay($campaign);
		$item->vendor_id = $wepay->wepay_account_id;
		if(empty($wepay->wepay_account_id)) {
			return false;
		}
		$session->set('tienda.wepay.vendor_id', $item->vendor_id );
		
		//QTY is always going to be one
		$item->product_qty = 1;
	}
	
	function onBeforeAddToCart( $item, $values )
	{
		
		$session = JFactory::getSession();
		$this->deleteAllCartItems( $session->getId());

//		$params = new DSCParameter(trim(@$item->cartitem_params));
//        $params->set( 'price', $values['price'] );
//        $item->cartitem_params = trim( $params->toString( ) );

		//var_dump($values);
		
	
	}



	function onAddOrderitemFromCart ($orderItem, $cartItem) {
	

		//TODO add check to make sure cart price is higher product price
		$params = new DSCParameter(trim(@$cartItem->cartitem_params));
       	FB::log($params, 'trying to do price');
       	$price = $params->get( 'price', '');
      	
     // 	if($price > $orderItem->orderitem_price) {
      		 $orderItem->orderitem_price =  $params->get( 'price', '');
      		 FB::log($orderItem, 'trying to do price');
     // 	}	

       
       


	}


	/**
	 *
	 * @param $session_id
	 * @return unknown_type
	 */
	function deleteAllCartItems( $session_id )
	{
		$db = JFactory::getDBO();

		$query = new DSCQuery();

		$query->delete();
		$query->from( "#__tienda_carts" );
		$query->where( "`session_id` = '$session_id' " );
	
		$db->setQuery( (string) $query );
		if (!$db->query())
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
		$uid = JFactory::getUser()->id;
		
		
		if( $uid )
		{
			$query = new DSCQuery();
			$query->delete();
			$query->from( "#__tienda_carts" );
			$query->where( "`user_id` = '".$uid."' " );
			$db->setQuery( (string) $query );
			if (!$db->query())
			{
				$this->setError( $db->getErrorMsg() );
				return false;
			}
		}
		return true;
	}

	function onBeforeStoreOrderItems( $item )
	{	

		Tienda::load( 'TiendaHelperCampaign', 'helpers.campaign' );
		$campaign = TiendaHelperCampaign::getCampaignFromProduct($item->product_id);
		$wepay = $this->wepay($campaign);
		$item->vendor_id = $wepay->wepay_account_id;

		
		//QTY is always going to be one
		$item->orderitem_quantity = 1;
	}
	function onBeforeStoreCarts( $item )
	{	
	

		$campaign = TiendaHelperCampaign::getCampaignFromProduct($item->product_id);
	
		$wepay = $this->wepay($campaign);

		$item->vendor_id = $wepay->wepay_account_id;
		
		//QTY is always going to be one
		$item->product_qty = 1;


	}

	function onAfterStoreCarts( $item )
	{
		
	}





	
	/**
	 * Adds eav details if the user has entered them 
	 * in the order view
	 *
	 * @param unknown_type $i
	 * @param unknown_type $item
	 */
	function onDisplayCartItem( $i, $item )
	{
		
	}
	
	/**
	 * Event to allow plugins to add keys to the loading of cart items
	 * to make the cartitem also unique based on extra carts column(s).
	 */
    function onGetAdditionalCartKeyValues($item, $posted_values, $index)
    {
    
    }
	
	/**
	 * Displays the custom fields on the site product view
	 * @param int $product_id
	 */
	function onAfterDisplayProduct( $product_id )
	{
		
	}
	
	/**
	 * Displays the user editable custom fields on the site product view
	 * @param int $product_id
	 */
	function onDisplayProductAttributeOptions( $product_id )
	{
		
	}
	
	/**
	 * Saves the extra info in the cart item
	 * 
	 * @param unknown_type $item
	 * @param unknown_type $values
	 * @param unknown_type $files
	 */
	function onAfterCreateItemForAddToCart( $item, $values, $files )
	{
		
	}
	
	

	function onRemoveFromCart( $item )
	{
		
	}
}
