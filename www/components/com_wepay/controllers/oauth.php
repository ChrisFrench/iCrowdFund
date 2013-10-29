<?php
/**
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class WepayControllerOauth extends WepayController {

	var $error = NULL;
	var $code = NULL;
	var $info = NULL;
	var $user = NULL;

	function __construct() {
		/*
		 * ALL THIS FILE DOES AUTOMATICALLY REDIRECT THE USER TO ACCEPT THE TERMS OF WEPAY AND GRANT ACCESS,
		 * YOU CAN USE DASHBOARD IF YOU WANT A SPLASH PAGE TO LAUNCH THIS, OR MORE LIKELY YOU BUILD THAT INTO SOME OTHER PART OF YOUR SITE
		 * 
		 * */
		$this -> error = JRequest::getVar('error', '');
		$this -> code = JRequest::getVar('code', '');
		$redirect_uri = JRoute::_('index.php?option=com_wepay&view=oauth&Itemid=' . Wepay::getInstance() -> get('oauth_itemid', ''), true, -1);

		$this -> user = JFactory::getUser();

		if (!empty($this -> error)) {
			// user did not grant permissions
		} elseif (empty($this -> code)) {
			// set $scope and $redirect_uri before doing this
			// this will send the user to WePay to authenticate

			$options = array();
			$options['state'] = '';
			$options['user_name,'] = $this -> user -> name;
			$options['user_email'] = $this -> user -> email;
			//TODO the scopes should probably be a config option? I think all we really need to create accounts nothing else. 
			$uri = WePayLib::getAuthorizationUri(WepayLib::$all_scopes, $redirect_uri, $options);
		//	$this -> setRedirect($uri);
			$app = JFactory::getApplication();
			$app->redirect($uri, $msg); 
			//$app = JApplication::getInstance();
			//$app->close();
		} else {
			$this -> info = WePayLib::getToken($this -> code, $redirect_uri);
			
			if ($this -> info-> access_token) {
			//IF we got this far, we have a valid Access token  lets store it and redirect the user. 	
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_wepay/tables');
			$table = JTable::getInstance('Users', 'WepayTable');
			$keys = array('joomla_user_id' => $this -> user -> id); //ONLY ONE USSER PER JOOMLA USER
			$table -> load($keys, true);
			$table -> joomla_user_id = $this -> user -> id;
			$table -> wepay_userid = $this -> info -> user_id;
			$table -> wepay_access_token = $this -> info -> access_token;
			$table -> wepay_token_type = $this -> info -> token_type;
			//if(!empty($this -> info -> expires_in)) {
			//		$table -> wepay_expires_in = $this -> info -> expires_in;
			//}
			$table -> oauth_code = $this -> code;
			$table -> store();
			}

			if(Wepay::getInstance() -> get('oauth_redirect')) {
				$redirect_uri = JRoute::_('index.php?Itemid=' . Wepay::getInstance() -> get('oauth_redirect'), true, -1);
				$msg = Wepay::getInstance() -> get('oauth_success_message','authenticated');
			} else {
				$redirect_uri = JURI::base();
				$msg = Wepay::getInstance() -> get('oauth_success_message','authenticated');

			}
				


			$app = JFactory::getApplication();
			$app->redirect($redirect_uri,$msg); 
		}
		

	}

	

}
