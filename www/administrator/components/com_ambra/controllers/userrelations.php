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

class AmbraControllerUserRelations extends AmbraController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'userrelations');		
	}
	
    /**
     * Sets the model's state
     * 
     * @return array()
     */
    function _setModelState()
    {
        $state = parent::_setModelState();      
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $state['filter_id']    	   = $app->getUserStateFromRequest($ns.'id', 'filter_id', '', '');
        $state['filter_user']      = $app->getUserStateFromRequest($ns.'user', 'filter_user', '', '');
        $state['filter_relation']  = $app->getUserStateFromRequest($ns.'relation', 'filter_relation', '', '');
        $state['filter_relations'] = $app->getUserStateFromRequest($ns.'relations', 'filter_relations', '', '');
        $state['filter_user_from'] = $app->getUserStateFromRequest($ns.'user_from', 'filter_user_from', '', '');
        $state['filter_user_to']   = $app->getUserStateFromRequest($ns.'user_to', 'filter_user_to', '', '');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }    
    
    /**
     * Saves the relationship
     * and fires the post-save plugin event
     * 
     * @see ambra/admin/AmbraController#save()
     */
    function save()
    {
    	$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		$row->bind( $_POST );
        $isNew = ($row->id < 1);
        
        $saved = $this->addRelationship();

    	switch ($saved)
    	{
    		case "Invalid User":
    			$this->messagetype 	= 'notice';
				$this->message 		= JText::_( 'Invalid User' );
    		  break;
    		case "Relationship Already Exists":
    			$this->messagetype 	= 'notice';
				$this->message 		= JText::_( 'Relationship Already Exists' );
    		  break;
    		case "Saved":
    		default:
    			$this->messagetype 	= 'message';
				$this->message 		= JText::_( 'Saved' );
    		  break;
    	}
    	    	
    	$redirect = "index.php?option=com_ambra";
    	$task = JRequest::getVar('task');
    	switch ($task)
    	{
    		case "savenew":
    			$redirect .= '&view='.$this->get('suffix').'&task=add';
    		  break;
    		case "save":
    		default:
    			$redirect .= "&view=".$this->get('suffix');
    		  break;
    	}
    	        
        // redirect to wherever is set in redirect
        $redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    
    /**
     * Verifies the fields in a submitted form.  Uses the table's check() method.
     * Will often be overridden. Is expected to be called via Ajax 
     * 
     * @return void
     */
    function validate()
    {
    	$response = array();
        $response['msg'] = '';
        $response['error'] = '';
            
        // get elements from post
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
        
        if (empty($elements))
        {
            $response['error'] = '1';
            $response['msg'] = JText::_( "Unable to Process Form" ); 
            echo ( json_encode( $response ) );
            return;
        }
        
        // if all is good, return ok
        echo ( json_encode( $response ) );
        return;
    }

	/**
	 *
	 * Adds a user relationship
	 * @return unknown_type
	 */
	function addRelationship()
	{
		$response = array();
		$response['msg'] = '';
		$response['error'] = '';
				
		$submitted_values = JRequest::get('post');

		$user_id = JFactory::getUser($submitted_values['new_relationship_user_from']);
		$user_to = JFactory::getUser($submitted_values['new_relationship_user_to']);
		$relation_type = $submitted_values['new_relationship_type'];
		
		// verify user id exists
		$user =& $user_id;
		if (empty($user->id) || $user_id->id == $user_to->id)
		{
			return "Invalid User";
		}

		// and that relationship doesn't already exist
		Ambra::load( 'AmbraHelperUser', 'helpers.user' );
		if (AmbraHelperUser::relationshipExists( $user_id->id, $user_to->id, $relation_type ))
		{
			return ( "Relationship Already Exists" );
		}

		$table = JTable::getInstance('UserRelations', 'AmbraTable');
		$table->user_id_from = $user_id->id;
		$table->user_id_to = $user_to->id;
		$table->relation_type = $relation_type;
		$table->save();
				
		return "Saved"; 
	}
	
}
?>