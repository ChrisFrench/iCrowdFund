<?php
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosControllerScopes extends TosController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'scopes');
	}
	
    /**
     * Sets the model's default state based on values in the request
     *
     * @return array()
     */
    function _setModelState()
    {
    	$state = parent::_setModelState();
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $state = array();

        $state['filter_client']       = $app->getUserStateFromRequest($ns.'client', 'filter_client', '', '');
        $state['filter_identifier']       = $app->getUserStateFromRequest($ns.'identifier', 'filter_identifier', '', '');
        $state['filter_url']       = $app->getUserStateFromRequest($ns.'url', 'filter_url', '', '');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );
        }
        return $state;
    }
}