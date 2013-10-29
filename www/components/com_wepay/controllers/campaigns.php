<?php
/**
 * @version 1.5
 * @package Wepay
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

class WepayControllerCampaigns extends WepayController {

	/**
	 * constructor
	 */
	function __construct() {
		parent::__construct();

		$this -> set('suffix', 'campaigns');
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
		$state['filter_user_id'] = $app -> getUserStateFromRequest($ns . '.user_id', 'filter_user_id', '', 'int');

		foreach (@$state as $key => $value) {
			$model -> setState($key, $value);
		}

		return $state;
	}


	/**
	 *
	 * @return unknown_type
	 */
	function stats($cachable = false, $urlparams = false) {
		$user_id = $this -> validateUser();

		$view = $this -> getView($this -> get('suffix'), 'html');
		$model = $this -> getModel($this -> get('suffix'));
		$model -> getId();

		$row = $model -> getItem(true, false);

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
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see wepay/site/WepayController#view()
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
		$view -> assign('action', 'index.php?option=com_wepay&view=campaigns&task=addlevel');
		Wepay::load('WepayModelCampaignProducts', 'models.campaignproducts');
		$model = JModel::getInstance('CampaignProducts', 'WepayModel');
		$model -> setState('filter_id', $row -> campaign_id);
		//$model->setState( 'filter_id_to', $row->campaign_id );

		$products = $model -> getProductsList(TRUE);
		$view -> assign('products', $products);
		$view -> setLayout($layout);
		$view -> display();
		// $this->footer( );
	}

	/**
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see wepay/site/WepayController#view()
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
		$view -> assign('action', 'index.php?option=com_wepay&view=campaigns&task=wepay');
		

		
		$view -> setLayout($layout);
		$view -> display();
		
		// $this->footer( );
	}

	/**
	 * Displays a single Campaign form to add products as levels
	 * (non-PHPdoc)
	 * @see wepay/site/WepayController#view()
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
		$view -> assign('action', 'index.php?option=com_wepay&view=campaigns&task=checks');
		

		
		$view -> setLayout($layout);
		$view -> display();
		
		// $this->footer( );
	}




	function edit($cachable = false, $urlparams = false) {
		$view = $this -> getView($this -> get('suffix'), 'html');
		$model = $this -> getModel($this -> get('suffix'));
		$view -> set('hidemenu', false);
		$view -> setModel($model, true);
		//$view->assign( 'product_relations', $this->getRelationshipsHtml($view, $model->getId()) );
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

		if ($row -> store()) {
			//clearing products cache
			$model -> clearCache();
			//add the product to the cross reference list

			$table = JTable::getInstance('CampaignProducts', 'WepayTable');
			$table -> load();
			$table -> bind();
			$table -> campaign_id = $campaign_id;
			$table -> product_id = $row -> product_id;
			$table -> store();

			$price = JTable::getInstance('Productprices', 'WepayTable');
			$price -> product_id = $row -> id;
			$price -> product_price = JRequest::getVar('product_price');
			$price -> group_id = Wepay::getInstance() -> get('default_user_group', '1');
			if (!$price -> save()) {
				$this -> messagetype = 'notice';
				$this -> message .= " :: " . $price -> getError();
			}
			/* $price = JTable::getInstance( 'Productprices', 'WepayTable' );
			 $price->product_id = $row->id;
			 $price->product_price = JRequest::getVar( 'product_price' );
			 $price->group_id = Wepay::getInstance()->get('default_user_group', '1');
			 if (!$price->save())
			 {
			 $this->messagetype 	= 'notice';
			 $this->message .= " :: ".$price->getError();
			 } */

			//redirect to add products view

			//TODO instead of just adding and redirecting the page, we could  make this call the getProducts method in the campaignProducts model and than  put it through a view return all the HTML so we can do that updating in AJAX
		}

		$redirect = "index.php?option=com_wepay&view=campaigns&task=addlevels&id=" . $campaign_id;

		$redirect = JRoute::_($redirect, false);
		$this -> setRedirect($redirect, $this -> message, $this -> messagetype);

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
		} else {
			$this -> messagetype = 'notice';
			$this -> message = JText::_('COM_TIENDA_SAVE_FAILED') . " - " . $row -> getError();
		}
		//redirect to add products view
		$redirect = "index.php?option=com_wepay&view=campaigns&task=addlevels&id=" . $row -> campaign_id; ;

		$redirect = JRoute::_($redirect, false);
		//$this -> setRedirect($redirect, $this -> message, $this -> messagetype);
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

		$upload -> setDirectory(Wepay::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/');

		$upload -> upload();

		// resize
		Wepay::load('WepayHelperImage', 'helpers.image');
		$imgHelper = WepayHelperBase::getInstance('Image', 'WepayHelper');
		$options = array();
		$options['width'] = '530';
		$options['height'] = '340';
		$options['thumb_path'] = Wepay::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/';

		if (!$imgHelper -> resizeImage($upload, 'manufacturer', $options)) {
			JFactory::getApplication() -> enqueueMessage($imgHelper -> getError(), 'notice');
		}

		// thumb

		$imgHelper = WepayHelperBase::getInstance('Image', 'WepayHelper');
		$options = array();
		$options['width'] = '190';
		$options['height'] = '80';
		$options['thumb_path'] = Wepay::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/thumbs';

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

}
