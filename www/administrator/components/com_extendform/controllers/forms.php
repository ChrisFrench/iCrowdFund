<?php
/**
 * @version	1.5
 * @package	Extendform
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class ExtendformControllerForms extends ExtendformController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'forms');
	}
	
	function _setModelState()
    {
    	$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();

		$state['filter']   = $app->getUserStateFromRequest($ns.'name', 'filter', '', '');
    	$state['filter_title']   = $app->getUserStateFromRequest($ns.'name', 'filter_title', '', '');
      	$state['filter_component'] 	= $app->getUserStateFromRequest($ns.'user_id', 'filter_component', '', '');
      	$state['filter_form'] = $app->getUserStateFromRequest($ns.'type', 'filter_form', '', '');
      	$state['filter_xmlfile'] = $app->getUserStateFromRequest($ns.'datecreated', 'filter_xmlfile', '', '');
      	$state['filter_path'] = $app->getUserStateFromRequest($ns.'lastmodified', 'filter_path', '', '');
		$state['filter_formname'] = $app->getUserStateFromRequest($ns.'lastmodified', 'filter_formname', '', '');
		$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
		
      	
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }
	
}

?>