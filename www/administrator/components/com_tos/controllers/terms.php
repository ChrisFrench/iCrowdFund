<?php
/**
 * @package	Terms of Service
 * @author 	Ammonite Networks
 * @link 	http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosControllerTerms extends TosController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'terms');
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

        $state['filter_terms_title']       = $app->getUserStateFromRequest($ns.'terms_title', 'filter_terms_title', '', '');
        $state['filter_terms']       =      $app->getUserStateFromRequest($ns.'terms', 'filter_terms', '', '');
        $state['filter_scope_id']       =       $app->getUserStateFromRequest($ns.'scope_id', 'filter_scope_id', '', '');
        $state['filter_created_date']       = $app->getUserStateFromRequest($ns.'created_date', 'filter_created_date', '', '');
        $state['filter_expires_date']       = $app->getUserStateFromRequest($ns.'expires_date', 'filter_expires_date', '', '');
        $state['filter_modified_date']       = $app->getUserStateFromRequest($ns.'modified_date', 'filter_modified_date', '', '');

       
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );
        }
        return $state;
    }


}

?>