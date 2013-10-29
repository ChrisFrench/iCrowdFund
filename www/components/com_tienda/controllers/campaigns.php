<?php
/**
 * @version 1.5
 * @package Tienda
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
if (!class_exists('Wepay')) {
	JLoader::register("Wepay", JPATH_ADMINISTRATOR . "/components/com_wepay/defines.php");
}

class TiendaControllerCampaigns extends TiendaController {

	/**
	 * constructor
	 */
	function __construct() {
		parent::__construct();


		$this -> set('suffix', 'campaigns');
		$this->registerTask('validateFundingPrice', 'validateFundingPrice');
		$this->registerTask( 'update_status', 'updateStatus' ); 
	}

	/**
	 * Sets the model's state
	 *
	 * @return array()
	 */
	function _setModelState() {
		$state = parent::_setModelState();
		$app = JFactory::getApplication();
		$model = $this -> getModel($this -> get('suffix'));
		$ns = $this -> getNamespace();

		$state['filter_campaign'] = $app -> getUserStateFromRequest($ns . '.campaign', 'filter_campaign', '', 'int');
		$state['filter_category'] = $app -> getUserStateFromRequest($ns . '.category', 'filter_category', '', 'int');
		$state['filter_user_id'] = $app -> getUserStateFromRequest($ns . '.user_id', 'filter_completed', '', 'int');
		$state['filter_completed'] = $app -> getUserStateFromRequest($ns . '.completed', 'filter_user_id', '', 'int');
		$state['filter_active'] = $app -> getUserStateFromRequest($ns . '.active', 'filter_active', '', 'int');
		//$state['filter_fundingstart_date'] = $app -> getUserStateFromRequest($ns . '.fundingstart', 'filter_fundingstart_date', '', );
		//$state['filter_fundingend_date'] = $app -> getUserStateFromRequest($ns . '.fundingend', 'filter_fundingend_date', '', );
		//var_dump($state);


		foreach (@$state as $key => $value) {	
			$model -> setState($key, $value);
		}

		return $state;
	}

	/**
	 * Displays a single product
	 * (non-PHPdoc)
	 * @see tienda/site/TiendaController#view()
	 */
	function view() {

		$layout = JRequest::getVar('layout', 'campaign');
		JRequest::setVar('view', $this -> get('suffix'));
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$model -> setState('task', 'campaign');

		$row = $model -> getItem(true, false);

		// use the state
		$view = $this -> getView($this -> get('suffix'), JFactory::getDocument() -> getType());

		$view -> set('_doTask', true);
		$view -> setModel($model, true);

		$view -> assign('row', $row);
		$view -> setLayout($layout);
		$view -> display();
		// $this->footer( );
	}


	function ready() {

		$layout = JRequest::getVar('layout', 'campaign');
		JRequest::setVar('view', $this -> get('suffix'));
		
		$model = $this -> getModel($this -> get('suffix'));
		$id = $model -> getId();
		$row = $model -> getTable();
		$row -> load($id );
		$row -> campaign_ready = 1;
		
		if($row -> save()) {
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterUserReadyCamapaign', array( $row ) );

			//TODO move to plugin
			if ( !class_exists('Messagebottle') ) 
    			JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
    		$lang = JFactory::getLanguage();
			$lang->load('com_tienda', JPATH_ADMINISTRATOR);
			$mail = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
			$mail->addRecipient((int) $row->user_id );
			$mail->setSubject( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_READY_TITLE'), $row->campaign_name ) );

			//$url = JURI::root() .'index.php?option=com_tienda&view=campaigns&layout=mycampaigns&Itemid=122';
			//$link = JRoute::_($url);
			//not sure the good way to route from the admin
			$link = JURI::root() .'my-projects/campaigns/stats/'.$row->campaign_id;
			$link = '<a href="'.$link.'">Here</a>';
			$mail->setBody( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_READY_BODY'), $row->campaign_name , $link ) );
			$mail->setScope('2');
			$mail->setView('campaign');
			$mail->setOption('com_tienda');
			//var_dump($mail);
 			
 			if($mail->queue()) {
 				//do stuff

 			}
		}
		// $this->footer( );
		$msg = 'Your Project has been submitted for approval';
		$this->setRedirect ('index.php?option=com_tienda&view=campaigns&task=checks&id='.$id, $msg);

	}


	/**
	 *
	 * @return unknown_type
	 */
	function stats($cachable = false, $urlparams = false) {
		Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
		$user_id = $this -> validateUser();
		$view = $this -> getView($this -> get('suffix'), 'html');
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$row = $model -> getItem(true, false);
		$row->orders = TiendaHelperCampaign::getCampaignOrders( $row -> campaign_id, 5 ); 		
		// use the state
		$this -> canAccess($user_id, $row -> user_id);
		$view -> set('_doTask', true);
		$view -> setModel($model, true);
		$view -> assign('row', $row);
		$view -> assign('user_id', $user_id);
		$view -> set('hidemenu', false);
		$view -> setLayout('stats');
		$view -> display();
	}

	/**
	 *
	 * @return unknown_type
	 */
	function stats_orders($cachable = false, $urlparams = false) {
		Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
		$user_id = $this -> validateUser();
		$view = $this -> getView($this -> get('suffix'), 'html');
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$row = $model -> getItem(true, false);
		$row->orders = TiendaHelperCampaign::getCampaignOrders( $row -> campaign_id, 0 );
		// use the state
		$this -> canAccess($user_id, $row -> user_id);
		$view -> set('_doTask', true);
		$view -> setModel($model, true);
		$view -> assign('row', $row);
		$view -> assign('user_id', $user_id);
		$view -> set('hidemenu', false);
		$view -> setLayout('stats_orders');
		$view -> display();
	}

	/**
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see tienda/site/TiendaController#view()
	 */
	function addlevels() {
		$user_id = $this -> validateUser();

		$layout = JRequest::getVar('layout', 'campaign_products');
		JRequest::setVar('view', $this -> get('suffix'));
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();

		$row = $model -> getItem(true, false);
		// use the state
		//$this->canAccess($user_id, $row->user_id);

		$view = $this -> getView($this -> get('suffix'), JFactory::getDocument() -> getType());

		$view -> set('_doTask', true);
		$view -> setModel($model, true);

		$view -> assign('row', $row);
		$view -> assign('user_id', $user_id);
		$view -> assign('action', 'index.php?option=com_tienda&view=campaigns&task=addlevel');
		Tienda::load('TiendaModelCampaignProducts', 'models.campaignproducts');
		$model = JModel::getInstance('CampaignProducts', 'TiendaModel');
		$model -> setState('filter_id', $row -> campaign_id);
//$model->setState( 'filter_id_to', $row->campaign_id );

		$products = $model -> getProductsList(TRUE);
		

		$view -> assign('products', $products);
		$level = JRequest::getVar('level', '');

		if($level){
		Tienda::load( 'TiendaModelProducts', 'models.products' );
		$model = JModel::getInstance( 'Products', 'TiendaModel' );
		$model->setId( $level );  
		$active = $model->getItem( );  
		$view -> assign('active', $active);
		}


		$view -> setLayout($layout);
		$view -> display();
		// $this->footer( );
	}

	/**
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see tienda/site/TiendaController#view()
	 */
	function wepay() {
		$user_id = $this -> validateUser();
		JRequest::setVar('view', $this -> get('suffix'));
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$wepayTask = JRequest::getVar('wepayTask', '');
		switch ($wepayTask) {
			case 'register':
				Wepay::load('WepayHelperWepay','helpers.wepay');
				$wePayUser = WepayHelperWepay::canCreate();
		
				$row = $model -> getTable();
				$row -> load($model -> getId());
				$row -> wepay_account_id = WepayHelperWepay::createAccount($wePayUser,$row -> campaign_name, $row ->campaign_shortdescription, 1);
				$row -> save();
		
		
				break;
			
			default:
				
				
	
				break;
		}
		$layout = JRequest::getVar('layout', 'campaign_wepay');
		$view = $this -> getView($this -> get('suffix'), JFactory::getDocument() -> getType());
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$row = $model -> getItem(true, false);
		$view -> set('_doTask', true);
		$view -> setModel($model, true);
		$view -> assign('row', $row);
		$view -> assign('user_id', $user_id);
		$view -> assign('action', 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);
		$view -> setLayout($layout);
		$view -> display();
		// $this->footer( );
	}

	/**
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see tienda/site/TiendaController#view()
	 */
	function checks() {
		$user_id = $this -> validateUser();

		JRequest::setVar('view', $this -> get('suffix'));
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$row = $model -> getItem(true, false);

		$wepayTask = JRequest::getVar('wepayTask', '');

		$layout = JRequest::getVar('layout', 'campaign_checks');
		$view = $this -> getView($this -> get('suffix'), JFactory::getDocument() -> getType());

		$view -> set('_doTask', true);
		$view -> setModel($model, true);

		//do the checks


		$view -> assign('row', $row);
		$view -> assign('user_id', $user_id);
		$view -> assign('action', 'index.php?option=com_tienda&view=campaigns&task=checks');
		

		
		$view -> setLayout($layout);
		$view -> display();
		
		// $this->footer( );
	}

	function newproject() {
		$user_id = $this -> validateUser();
		$model = $this -> getModel($this -> get('suffix'));
		$error = false;
		$row = $model -> getTable();
		$row -> bind(JRequest::get('POST'));
		$row -> user_id = $user_id;
		$row ->store();

		if($row ->store()) {
			// now, create default funding level with cost $1, no shipping and no image
			$m_product = $this -> getModel('products');
			$product = $m_product -> getTable();
			$product -> product_sku = '';
			$product -> product_model = '';
			$product -> product_description = JText::_("COM_TIENDA_CAMPAIGNS_DEFAULT_FUNDING_LEVEL_DESC");
			$product -> product_description_short = JText::_("COM_TIENDA_CAMPAIGNS_DEFAULT_FUNDING_LEVEL_DESC_SHORT");
			$product -> product_name = JText::_("COM_TIENDA_CAMPAIGNS_DEFAULT_FUNDING_LEVEL_NAME");
			$product -> product_ships = 0;
			$product -> product_enabled = 1;
			$product -> quantity_restriction = 0;
			$product -> _isNew = true;
			
			if( $product->store() )
			{
				$tblCampaign = JTable::getInstance('CampaignProducts', 'TiendaTable');
				$keys = array('campaign_id' => $row -> campaign_id, 'product_id' => $product -> product_id);
			
				$tblCampaign -> load($keys);
				//$table -> bind();
				$tblCampaign -> campaign_id = $row -> campaign_id;
				$tblCampaign -> product_id = $product -> product_id;
	
				$tblCampaign -> store();
			
	
				$price = JTable::getInstance('Productprices', 'TiendaTable');
				$price -> product_id = $product -> product_id;
				$price -> product_price = 1;
				$price -> group_id = Tienda::getInstance() -> get('default_user_group', '1');
				if (!$price -> save()) {
					$this -> messagetype = 'notice';
					$this -> message .= " :: " . $price -> getError();
				}
				
			}

			
			$redirect = "index.php?option=com_tienda&view=campaigns&task=edit&id=" . $row->campaign_id;

			$redirect = JRoute::_($redirect, false);
			$this -> setRedirect($redirect, $this -> message, $this -> messagetype);
		}
	}


	function edit($cachable = false, $urlparams = false) {
		$view = $this -> getView($this -> get('suffix'), 'html');
		$model = $this -> getModel($this -> get('suffix'));
		
		$view -> set('hidemenu', false);
	

		$view -> setModel($model, true);
		
		$view -> setLayout('form');
		$view -> setTask(true);
		$view -> display();

	}

	//adds a single product level to a campaign
	function addlevel() {

		$user_id = $this -> validateUser();
		$task = JRequest::getVar('task');

		$model = $this -> getModel('products');

		$row = $model -> getTable();
		
		$row -> load($model -> getId());

		$row -> bind(JRequest::get('POST'));

		$row -> product_name = JRequest::getVar('campaign_name', '', 'post', 'string');
		$campaign_id = JRequest::getVar('campaign_id', '', 'post', 'string');
		$row -> product_sku = '';
		$row -> product_model = '';
		$row -> product_description = JRequest::getVar('product_description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$row -> product_description_short = JRequest::getVar('product_description_short', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$row->_isNew = empty($row->product_id);
		$product_quantity = JRequest::getVar('product_quantity', '', 'post', 'string');
		if($product_quantity) {
		$row -> product_check_inventory = 1;	
		}



		if ($row -> store()) {
			
			//clearing products cache
			$model -> clearCache();
			//add the product to the cross reference list

			$table = JTable::getInstance('CampaignProducts', 'TiendaTable');
			$keys = array('campaign_id' => $campaign_id, 'product_id' => $row -> product_id);
		
			$table -> load($keys);
			//$table -> bind();
			$table -> campaign_id = $campaign_id;
			$table -> product_id = $row -> product_id;

			$table -> store();
			
			//add qty if there is one
			$product_quantity = JRequest::getVar('product_quantity', '', 'post', 'string');
			if($product_quantity) {
				$table = JTable::getInstance('ProductQuantities', 'TiendaTable');
				$keys = array('product_id' => $row -> product_id);
			
				$table -> load($keys);
				//$table -> bind();
				$table -> quantity = $product_quantity;
				$table -> product_id = $row -> product_id;

				$table -> store();
			}
			




			$price = JTable::getInstance('Productprices', 'TiendaTable');
			$price -> product_id = $row -> id;
			$price -> product_price = JRequest::getVar('product_price');
			$price -> group_id = Tienda::getInstance() -> get('default_user_group', '1');
			if (!$price -> save()) {
				$this -> messagetype = 'notice';
				$this -> message .= " :: " . $price -> getError();
			}




			/* $price = JTable::getInstance( 'Productprices', 'TiendaTable' );
			 $price->product_id = $row->id;
			 $price->product_price = JRequest::getVar( 'product_price' );
			 $price->group_id = Tienda::getInstance()->get('default_user_group', '1');
			 if (!$price->save())
			 {
			 $this->messagetype 	= 'notice';
			 $this->message .= " :: ".$price->getError();
			 } */

			//redirect to add products view

			//TODO instead of just adding and redirecting the page, we could  make this call the getProducts method in the campaignProducts model and than  put it through a view return all the HTML so we can do that updating in AJAX
		}

		$redirect = "index.php?option=com_tienda&view=campaigns&task=addlevels&id=" . $campaign_id;

		$redirect = JRoute::_($redirect, false);
		$this -> setRedirect($redirect, $this -> message, $this -> messagetype);

	}

	function csv() {

	

		Tienda::load('TiendaHelperCampaign', 'helpers.campaign');

		$input = JFactory::getApplication()->input;

		$user_id = $this -> validateUser();



		$view = $this -> getView($this -> get('suffix'), 'csv');
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();
		$row = $model -> getItem(true, false);

		
		if($row->user_id == JFactory::getUser()->id) {
			$items = TiendaHelperCampaign::getCampaignOrders( $row -> campaign_id, 0 ); 		
			// use the state
			$this -> canAccess($user_id, $row -> user_id);
			$view -> set('_doTask', true);
			$view -> setModel($model, true);
			$view -> assign('items', $items);
		
			$view -> set('hidemenu', false);
		
			$view -> display();
		}

	}

	function setAppFee($row) {
		
	
		$allornothing =  (string) '1';
		$charity =  (string) '2';
		$cause =  (string) '3';

		$allornothing_fundingtype =  (string) '1';
		$instant_fundingtype =  (string) '2';
		$Variable_fundingtype =  (string) '3';

				 
		$fee = (float)$row->app_fee;

				if($row->type == $charity)  {
					//Chartity
					return '0.02';
				
				}	

				if($row->fundingtype == $allornothing_fundingtype)  {
					//All Or Nothing
					return '0.05';
					
				}

				if($row->fundingtype == $Variable_fundingtype)  {
					//Variable
					return '0.09';
					
				}


				if(	$row->fundingtype == $instant_fundingtype)  {
					//$instant_fundingtype
					return '0.07';
					
				}		
		 //if nothing else we set it to 5, because it should never be 0 unless set 0
		 return '0.05';

	}
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	function save() {
		$user_id = $this -> validateUser();


		
		$task = JRequest::getVar('task');
		$model = $this -> getModel($this -> get('suffix'));
		$error = false;
		$row = $model -> getTable();
		$row -> load($model -> getId());
		$row -> bind(JRequest::get('POST'));
		$row -> user_id = $user_id;

		$row -> app_fee = $this->setAppFee($row);
		$row -> campaign_description = JRequest::getVar('campaign_description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$row -> campaign_shortdescription = JRequest::getVar('campaign_shortdescription', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		if ($row -> save()) {
			$fieldname = 'campaign_full_image_new';
			$userfile = JRequest::getVar($fieldname, '', 'files', 'array');
	

		if (!empty($userfile['size'])) {
				if ($upload = $this -> addfile($fieldname, $row)) {
					$row -> campaign_full_image = $upload -> getPhysicalName();
				} else {
					$error = true;
				}
			}
		}
		if ($row -> save()) {
			$model -> setId($row -> id);
			$this -> messagetype = 'message';
			$this -> message = JText::_('COM_TIENDA_SAVED');
			if ($error) {
				$this -> messagetype = 'notice';
				$this -> message .= " :: " . $this -> getError();
			}

			$dispatcher = JDispatcher::getInstance();
			$dispatcher -> trigger('onAfterSave' . $this -> get('suffix'), array($row));
			$redirect = "index.php?option=com_tienda&view=campaigns&task=addlevels&id=" . $row -> campaign_id; 
		} else {
			$this -> messagetype = 'notice';
			$this -> message = JText::_('COM_TIENDA_SAVE_FAILED') . " - " . $row -> getError();


			$redirect = "index.php?option=com_tienda&view=campaigns&layout=form&id=".$row -> campaign_id; 
		}
		//redirect to add products view
	

		$redirect = JRoute::_($redirect, false);
		$model->clearCache();
		$this -> setRedirect($redirect);

	}

	/**
	 * Adds a thumbnail image to item
	 * @return unknown_type
	 */
	/**
	 * Adds a image to item
	 * @return unknown_type
	 */
	function addfile($fieldname = 'campaign_full_image_new', $row) {
		$upload = new DSCImage();

		$upload -> handleUpload($fieldname);

		$upload -> setDirectory(Tienda::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/');

		$upload -> upload();

		// resize
		Tienda::load('TiendaHelperImage', 'helpers.image');
		$imgHelper = TiendaHelperBase::getInstance('Image', 'TiendaHelper');
		$options = array();
		$options['width'] = '530';
		$options['height'] = '340';
		$options['maxheight'] = '340';
		$options['thumb_path'] = Tienda::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/';

		if (!$imgHelper -> resizeImage($upload, 'manufacturer', $options)) {
			JFactory::getApplication() -> enqueueMessage($imgHelper -> getError(), 'notice');
		}

		// thumb

		$imgHelper = TiendaHelperBase::getInstance('Image', 'TiendaHelper');
		$options = array();
		$options['width'] = '190';
		$options['height'] = '140';
		$options['maxheight'] = '140';
		$options['thumb_path'] = Tienda::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/thumbs';

		if (!$imgHelper -> resizeImage($upload, 'manufacturer', $options)) {
			JFactory::getApplication() -> enqueueMessage($imgHelper -> getError(), 'notice');
		}

		return $upload;
	}

	function validateUser($msg = null) {
		if(empty($msg)) {
			$msg = 'You must login first';
		}
		$user = JFactory::getUser();
		$userId = $user -> get('id');
		if (!$userId) {
			$app = JFactory::getApplication();
			$return = JFactory::getURI() -> toString();
			$url = 'index.php?option=com_users&view=login';
			$url .= '&return=' . base64_encode($return);
			$app -> redirect($url, $msg);
			return false;
		}
		return $userId;
	}
	
	function validateFundingPrice()
	{
        $helper =  Tienda::getClass( 'TiendaHelperBase', 'helpers._base' );

        $response = array( );
        $response['msg'] = '';
        $response['error'] = '';
        $cart_helper = TiendaHelperBase::getInstance( 'Carts' );
        $product_helper = TiendaHelperBase::getInstance( 'Product' );
		$items = $cart_helper->getProductsInfo();
		
		if( count($items) == 0 )
		{
	        $response['error'] = '1';
			$response['msg'] = $helper->generateMessage( JText::_('COM_TIENDA_COULD_NOT_PROCESS_FORM') );
	
	        echo ( json_encode( $response ) );
	        return;
		}
		$item = $items[0];
		$price_raw = JRequest::getVar('price', $item);
		$price = (int)str_replace(',', '', $price_raw);
		$orig_price = $product_helper->getPrice($item->product_id);
		if( $price < $orig_price->product_price )
		{
	        $response['error'] = '1';
			$response['msg'] = $helper->generateMessage( JText::_('COM_TIENDA_FUNDING_PRICE_TOO_LOW') );
	
	        echo ( json_encode( $response ) );
	        return;			
		}

		$c_item = DSCTable::getInstance('Carts', 'TiendaTable');
		$c_item->load($item->cart_id);
		$params = new DSCParameter(trim(@$c_item->cartitem_params));
        $params->set( 'price', $price );
        $c_item->cartitem_params = trim( $params->toString( ) );
		$c_item->save();

        $response['error'] = '0';
        echo ( json_encode( $response ) );
        return;
		
	}

	function canAccess($uid, $eid) {

		if ($uid == $eid) {
			return true;
		} else {
			$app = JFactory::getApplication();

			$url = '/';

			$app -> redirect($url, JText::_('You don\'t have access to edit that page '));
			return false;
		}
		return $userId;
	}

	function canAccessOrder($oid) {
		Tienda::load('TiendaModelCampaignBackers', 'models.campaignbackers');
		$model_backers = JModel::getInstance("CampaignBackers", "TiendaModel" );
		$model_backers -> setState( "select", " tbl.campaign_id" );
		$model_backers -> setState( "filter_orderid", $oid );
		$cid = $model_backers->getList();
		if( count( $cid ) == 0 )
		{
			$app = JFactory::getApplication();

			$url = '/';

			$app -> redirect($url, JText::_('You don\'t have access to edit that page '));
			return false;			
		}
		$user_id = $this->validateUser();
		$db = $model_backers->getDbo();
		
		$q = "SELECT tbl.campaign_id FROM #__tienda_campaigns AS tbl WHERE `tbl`.`user_id` = ".(int)$user_id;
		$db->setQuery($q);
		$res = $db->loadAssocList();
		$c = count( $res );
		if( $c == 0 )
		{
			$app = JFactory::getApplication();

			$url = '/';

			$app -> redirect($url, JText::_('You don\'t have access to edit that page '));
			return false;			
		}
		
		$found = false;
		for( $i = 0; !$found && $i < $c; $i++ )
		{
			$found = $res[$i]['campaign_id'] == $cid[0]->campaign_id;
		}

		if ($found) {
			return true;
		} else {
			$app = JFactory::getApplication();

			$url = '/';

			$app -> redirect($url, JText::_('You don\'t have access to edit that page '));
			return false;
		}
	}

	/**
	 * Displays item
	 * @return void
	 */
	function display_order($cachable=false, $urlparams = false)
	{
		Tienda::load( 'TiendaUrl', 'library.url' );

		$model = $this->getModel( 'Orders' );
		Tienda::load('TiendaTableOrders', 'tables.orders');
		$order = DSCTable::getInstance('Orders', 'TiendaTable');
		$order->load( $model->getId() );
		$orderitems = $order->getItems();
		$row = $model->getItem();

		$this -> validateUser();
		$this -> canAccessOrder($row -> order_id);
		
		// Get the shop country name
		$row->shop_country_name = "";
		Tienda::load('TiendaModelCountries', 'models.countries');
		$countryModel = DSCModel::getInstance('Countries', 'TiendaModel');
		$countryModel->setId(Tienda::getInstance()->get('shop_country'));
		$countryItem = $countryModel->getItem();
		if ($countryItem && Tienda::getInstance()->get('shop_country'))
		{
			$row->shop_country_name = $countryItem->country_name;
		}

		// Get the shop zone name
		$row->shop_zone_name = "";
		Tienda::load('TiendaModelZones', 'models.zones');
		$zoneModel = DSCModel::getInstance('Zones', 'TiendaModel');
		$zoneModel->setId(Tienda::getInstance()->get('shop_zone'));
		$zoneItem = $zoneModel->getItem();
		if ($zoneItem && Tienda::getInstance()->get('shop_zone'))
		{
			$row->shop_zone_name = $zoneItem->zone_name;
		}

		//retrieve user information and make available to page
		if (!empty($row->user_id))
		{	
			//get the user information from jos_users and jos_tienda_userinfo
			Tienda::load('TiendaModelUsers', 'models.users');
			$userModel  = DSCModel::getInstance( 'Users', 'TiendaModel' );
			$userModel->setId($row->user_id);
			$userItem = $userModel->getItem();
			if ($userItem)
			{
				$row->userinfo = $userItem;
			}
		}

		$view = $this->getView('campaigns', 'html'  );
		
		$view->set( '_controller', 'campaigns' );
		$view->set( '_view', 'campaigns' );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'order' );

		$model->emptyState();
		$this->_setModelState();

		//START onDisplayOrderItem: trigger plugins for extra orderitem information
		if (!empty($orderitems))
		{
			Tienda::load( 'TiendaHelperOrder', 'helpers.order' );
			$onDisplayOrderItem = TiendaHelperOrder::onDisplayOrderItems($orderitems);

			$view->assign( 'onDisplayOrderItem', $onDisplayOrderItem );
		}
		//END onDisplayOrderItem

		$config = Tienda::getInstance();
		$show_tax = $config->get('display_prices_with_tax');
		$view->assign( 'show_tax', $show_tax );
		$view->assign( 'using_default_geozone', false );

		if ($show_tax)
		{
			$geozones = $order->getBillingGeoZones();
			if (empty($geozones))
			{
				// use the default
				$view->assign( 'using_default_geozone', true );
				Tienda::load('TiendaTableGeozones', 'tables.geozones');
				$table = DSCTable::getInstance('Geozones', 'TiendaTable');
				$table->load(array('geozone_id'=>$config->get('default_tax_geozone')));
				$geozones = array( $table );
			}

			Tienda::load( "TiendaHelperProduct", 'helpers.product' );
			foreach ($orderitems as &$item)
			{
				$taxtotal = ($item->orderitem_tax / $item->orderitem_quantity);
				$item->orderitem_price = $item->orderitem_price + floatval( $item->orderitem_attributes_price ) + $taxtotal;
				$item->orderitem_final_price = $item->orderitem_price * $item->orderitem_quantity;
				$order->order_subtotal += ($taxtotal * $item->orderitem_quantity);
			}
		}
		$view->assign( 'order', $order );
		$view->setTask(true); 

		JModel::addIncludePath( JPATH_ADMINISTRATOR .'/components/com_tienda/models' );
		$model_backers = JModel::getInstance("CampaignBackers", "TiendaModel" );
		$model_backers -> setState( "select", " tbl.campaign_id" );
		$model_backers -> setState( "filter_orderid", $order -> order_id );
		$cid = $model_backers->getList();
		$view->assign( "campaign_id", $cid[0]->campaign_id );

		$view->display();
	}

	/**
	 * Updates an order's status, adding a record to its history
	 * and redirects back to the view order page
	 *
	 * @return void
	 */
	function updateStatus()
	{
		$model  = $this->getModel( 'Orders' );
		$row = $model->getTable();
		$row->load( $model->getId() );

		$this -> validateUser();
		$this -> canAccessOrder($row -> order_id);

		$row->order_state_id = JRequest::getVar('new_orderstate_id');
		$completed_tasks = JRequest::getVar('completed_tasks');
			

		if ($completed_tasks == "on" && empty($row->completed_tasks) )
		{
			Tienda::load( 'TiendaHelperOrder', 'helpers.order' );
			TiendaHelperOrder::doCompletedOrderTasks( $row->order_id );
			$row->completed_tasks = 1;
		}

		if ( $row->save())
		{
			$model->setId( $row->order_id );
			$this->messagetype  = 'message';
			$this->message      = JText::_('COM_TIENDA_ORDER_SAVED');

			$history = DSCTable::getInstance('OrderHistory', 'TiendaTable');
			$history->order_id             = $row->order_id;
			$history->order_state_id       = $row->order_state_id;
			$history->notify_customer      = JRequest::getVar('new_orderstate_notify');
			$history->comments             = JRequest::getVar('new_orderstate_comments');

			if (!$history->save())
			{
				$this->setError( $history->getError() );
				$this->messagetype  = 'notice';
					
				$this->message      .= " :: ".JText::_('COM_TIENDA_ORDERHISTORY_SAVE_FAILED');
			}
			
			$model->clearCache();

			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterUpdateStatus'.$this->get('suffix'), array( $row ) );
		}
		else
		{
			$this->messagetype  = 'notice';
			$this->message      = JText::_('COM_TIENDA_SAVE_FAILED')." - ".$row->getError();
		}

		$redirect = "index.php?option=com_tienda";
		$redirect .= '&view=campaigns&task=display_order&id='.$model->getId();
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
}
