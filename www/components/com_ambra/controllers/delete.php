<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerDelete extends AmbraController
{
	/**
	 * constructor
	 */
	function __construct() 
	{
        
        DSCAcl::validateUser(JText::_('COM_AMBRA_REDIRECT_LOGIN'), 'index.php?option=com_ambra&view=login');
		
		parent::__construct();
		$this->set('suffix', 'dashboard');
	}


	function doDelete() {
	// Check for request forgeries
        JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
        $confirmed =  JRequest::getVar('confirm', null, 'post', 'string','string');
        
        //TODO should we add a config option?
        //Ambra::getInstance()->get('user_can_delete_account');

        if($confirmed) {
        	$user = JFactory::getUser();
        	
        	//this is just so they can do checks  to make it fail
        	$dispatcher = JDispatcher::getInstance( );
        	$result = $dispatcher->trigger( "onValidateUserDeleteOwnAccount", array($user) );

        	if($result) {
        		//FAIL
        		return False;
        	} else {
        		if($user->delete()){
        			$app = JFactory::getApplication();
        			$url = JURI::base();
        			$app->redirect($url,JText::_('COM_AMBRA_SUCCESSFULLY_DELETED_ACCOUNT') );
        		}

        		//There is a built in onUserDelete plugin action in the core so we don't need one
        	}



        }

	}

}