<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class BilletsControllerLogs extends BilletsController 
{
    var $message = "";
    var $messagetype = "";
	
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'logs');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
    function _setModelState()
    {
    	$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();

      	$state['filter_object_type'] 	= $app->getUserStateFromRequest($ns.'object_type', 'filter_object_type', '', '');
      	$state['filter_property_name'] 	= $app->getUserStateFromRequest($ns.'property_name', 'filter_property_name', '', '');
      	$state['filter_object_id'] 	= $app->getUserStateFromRequest($ns.'object_id', 'filter_object_id', '', '');
		
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
}