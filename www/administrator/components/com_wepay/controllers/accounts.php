<?php
/**
* @package		Wepay
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class WepayControllerAccounts extends WepayController 
{
	
	
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'accounts');
	    $this->registerTask( 'enabled.enable', 'boolean' );
        $this->registerTask( 'enabled.disable', 'boolean' );
		

	}
	
	function _setModelState()
    {
    	$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();

    	$state['filter_name']   = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
		$state['filter_id_from']   = $app->getUserStateFromRequest($ns.'name', 'filter_id_from', '', '');
		$state['filter_id_to']   = $app->getUserStateFromRequest($ns.'name', 'filter_id_to', '', '');
      	$state['filter_userid'] 	= $app->getUserStateFromRequest($ns.'user_id', 'filter_userid', '', '');
      	$state['filter_wepay_userid'] = $app->getUserStateFromRequest($ns.'wepay_userid', 'filter_wepay_userid', '', '');
		$state['filter_wepay_account_id'] = $app->getUserStateFromRequest($ns.'wepay_account_id', 'filter_wepay_account_id', '', '');
		$state['filter_wepay_account_uri'] = $app->getUserStateFromRequest($ns.'wepay_account_uri', 'filter_wepay_account_uri', '', '');
      	$state['filter_wepay_access_token'] = $app->getUserStateFromRequest($ns.'wepay_access_token', 'filter_wepay_access_token', '', '');
      	$state['filter_wepay_token_type'] = $app->getUserStateFromRequest($ns.'wepay_token_type', 'filter_wepay_token_type', '', '');
		$state['filter_wepay_expires_in'] = $app->getUserStateFromRequest($ns.'wepay_expires_in', 'filter_wepay_expires_in', '', '');
		$state['filter_oauth_code'] = $app->getUserStateFromRequest($ns.'oauth_code', 'filter_oauth_code', '', '');
		$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
		
      	
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
	
	
	
}