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

class BilletsControllerFrequents extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'frequents');
		$this->registerTask( 'enabled.enable', 'boolean' );
		$this->registerTask( 'enabled.disable', 'boolean' );
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

      	$state['filter_parentid'] 	= $app->getUserStateFromRequest($ns.'parentid', 'filter_parentid', '', '');
      	$state['filter_scope'] 		= 'frequents';
      	
      	if ($state['filter_scope'] && $state['filter_parentid'])
      	{
      		$table = $model->getTable();
      		$table->load( $state['filter_parentid'] );
      		if ( $table->scope != $state['filter_scope'] ) 
      		{
      			$state['filter_parentid'] = '';
      		}
      	}

    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
	
}

?>