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
        $state['filter_terms']       = $app->getUserStateFromRequest($ns.'terms', 'filter_terms', '', '');
        $state['filter_scope_id']       = $app->getUserStateFromRequest($ns.'scope_id', 'filter_scope_id', '', '');
        $state['filter_created_date']       = $app->getUserStateFromRequest($ns.'created_date', 'filter_created_date', '', '');
        $state['filter_expires_date']       = $app->getUserStateFromRequest($ns.'expires_date', 'filter_expires_date', '', '');
        $state['filter_modified_date']       = $app->getUserStateFromRequest($ns.'modified_date', 'filter_modified_date', '', '');

       
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );
        }
        return $state;
    }


    /**
     * Displays a single Terms and Condtions form
     * @see ambra/site/AmbraController#display($cachable)
     */
    function display($cachable=false, $urlparams = false)
    {	
    	

        $model  = $this->getModel( $this->get('suffix') );
        $state = $this->_setModelState();
        $id = $model->getId();
        $item = $model->getItem();
    
      	
      	


        $view = $this->getView( 'terms', 'html' );
        if(Tos::getClass('ToshelperTos','helpers.tos')->checkAccepted($item->scope_id,null, false)) {
            $view->assign( 'accepted', 1);
        } 
        $view->set( '_controller', 'terms' );
        $view->set( '_view', 'terms' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->assign( 'row', $item);
        $view->assign( 'action', 'index.php?option=com_tos&view=terms&task=accept');
        $return = base64_decode(JRequest::getVar('return'));
        $view->assign( 'return', $return );
        $view->setModel( $model, true );
      
        $view->display();

	}

  /**
     * Displays a single Terms and Condtions form
     * @see ambra/site/AmbraController#display($cachable)
     */
    function view($cachable=false, $urlparams = false)
    { 
      

  }

	function accept() {
		$return = JRequest::getVar('return');
		
		

       
		$app = JFactory::getApplication();
		if($this->saveAccept()) {
			
			if(empty($return)) {
				$return = JURI::base();
			}
			$app->redirect($return);
		} else {
			//TODO what to do if we fail?

		}
		

	}



	function acceptAjax() {

		$response = array( );
        $response['msg'] = '';
        $response['error'] = '';

		if($this->saveAccept()) {
			$response['error'] = '0';
			 $response['msg'] = 'Success';
        // encode and echo (need to echo to send back to browser)
        echo json_encode( $response );
 
		} else {
			 $response['error'] = '1';
			 $response['msg'] = 'FAILED';
			echo json_encode( $response );
		}
		

	}

	function saveAccept() {
		$post = JRequest::get( 'post' );
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_tos/tables');
		$table = JTable::getInstance('accepts', 'TosTable');
	
		$table->user_id = JFactory::getUser()->id;
		$table->bind($post); 
		$table->ip_address = $_SERVER['REMOTE_ADDR'];
		$table->enabled = '1';

		if($table->save()) {
			return true;
		}  else {
			return false;
		}
	}


}

