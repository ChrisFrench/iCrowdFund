<?php
/**
 * @version		0.1.0
 * @package		Wepay
 * @copyright	Copyright (C) 2011 DT Design Inc. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link 		http://www.dioscouri.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class Wepay extends DSC {
	protected $_name = 'wepay';
	protected $_version = '1.0';
	protected $_build = 'r100';
	protected $_versiontype = '';
	protected $_copyrightyear = '2012';
	protected $_min_php = '5.3';

	// View Options
	public $show_linkback = '1';
	public $include_site_css = '1';
	public $amigosid = '';
	public $page_tooltip_dashboard_disabled = '0';
	public $page_tooltip_config_disabled = '0';
	public $page_tooltip_tools_disabled = '0';
	//WEPAY ACCOUNT
	public $use_production = '';
	public $client_id = '';
	public $client_secret = '';
	public $access_token = ''; 
	public $account_id = '';
	//CHECKOUT
	public $platform_fee = '';
	public $platform_fee_option = '0';
	public $oauth_itemid = '0';
	public $dashboard_itemid = '0';
	//Success Redirect
	public $oauth_redirect = '104';

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	public function _buildQuery() {
		$query = "SELECT * FROM #__wepay_config";
		return $query;
	}

	/**
	 * Retrieves the data, using a cached set if possible
	 *
	 * @return array Array of objects containing the data from the database
	 */
	public function getData() {
		$cache = JFactory::getCache('com_wepay.defines');
		$cache -> setCaching(true);
		$cache -> setLifeTime('86400');
		$data = $cache -> call(array($this, 'loadData'));
		return $data;
	}

	/**
	 * Loads the data from the database
	 */
	public function loadData() {
		$data = array();

		$database = JFactory::getDBO();
		if ($query = $this -> _buildQuery()) {
			$database -> setQuery($query);
			$data = $database -> loadObjectList();
		}

		return $data;
	}

	/**
	 * Get component config
	 *
	 * @acces	public
	 * @return	object
	 */
	public static function getInstance() {
		static $instance;

		if (!is_object($instance)) {
			$instance = new Wepay();
		}

		return $instance;
	}

	/**
	 * Intelligently loads instances of classes in framework
	 *
	 * Usage: $object = Wepay::getClass( 'WepayHelperCarts', 'helpers.carts' );
	 * Usage: $suffix = Wepay::getClass( 'WepayHelperCarts', 'helpers.carts' )->getSuffix();
	 * Usage: $categories = Wepay::getClass( 'WepaySelect', 'select' )->category( $selected );
	 *
	 * @param string $classname   The class name
	 * @param string $filepath    The filepath ( dot notation )
	 * @param array  $options
	 * @return object of requested class (if possible), else a new JObject
	 */
	public static function getClass($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_wepay' )) {
		return parent::getClass($classname, $filepath, $options);
	}

	/**
	 * Method to intelligently load class files in the framework
	 *
	 * @param string $classname   The class name
	 * @param string $filepath    The filepath ( dot notation )
	 * @param array  $options
	 * @return boolean
	 */
	public static function load($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_wepay' )) {
		return parent::load($classname, $filepath, $options);
	}

}



Wepay::load('WepayLib','library.wepay');
define("CLIENT_ID", Wepay::getInstance()->get('client_id'));
define("CLIENT_SECRET", Wepay::getInstance()->get('client_secret'));

if(Wepay::getInstance()->get('use_production')) {
    WePayLib::useProduction(CLIENT_ID,CLIENT_SECRET);
} else {
	WePayLib::useStaging(CLIENT_ID,CLIENT_SECRET);
}



?>
