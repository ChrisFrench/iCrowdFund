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

if ( !class_exists('Tos') ) 
             JLoader::register( "Tos", JPATH_ADMINISTRATOR."/components/com_tos/defines.php" );

class WepayControllerRules extends WepayController { 

/**
	 * constructor
	 */
	function __construct() {
		parent::__construct();

		$this -> set('suffix', 'rules');

		Tos::getClass('TosHelperTos','helpers.tos')->checkAccepted(2);
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

}
