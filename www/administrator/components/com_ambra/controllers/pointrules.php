<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerPointRules extends AmbraController 
{
    /**
     * constructor
     */
    function __construct() 
    {
        parent::__construct();
        
        $this->set('suffix', 'pointrules');
        $this->registerTask( 'pointrule_enabled.enable', 'boolean' );
        $this->registerTask( 'pointrule_enabled.disable', 'boolean' );
        //$this->findScopes();
    }

    /**
     * 
     * @return unknown_type
     */
    function findScopes()
    {
        $lastCheckedScopes = AmbraConfig::getInstance()->get('lastCheckedScopes');
        $today = Ambra::getClass( "AmbraHelperBase", "helpers._base" )->getToday();
        if ($lastCheckedScopes < $today || JRequest::getVar('task') == 'checkScopes' )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( "Checking for New Scopes" ) );
            $checkScopes = Ambra::getClass( "AmbraHelperRule", "helpers.rule" )->getScopes();
            if ($checkScopes === null)
            {
                JFactory::getApplication()->enqueueMessage( JText::_( "Nothing to Do" ) );
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'lastCheckedScopes') );
            $config->config_name = 'lastCheckedScopes';
            $config->value = $today;
            $config->save();
        }
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

        $state['filter_id_from']    = $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
        $state['filter_id_to']      = $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
        $state['filter_name']       = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
        $state['filter_enabled']    = $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
        $state['filter_date_from'] = $app->getUserStateFromRequest($ns.'date_from', 'filter_date_from', '', '');
        $state['filter_date_to'] = $app->getUserStateFromRequest($ns.'date_to', 'filter_date_to', '', '');
        $state['filter_datetype']   = $app->getUserStateFromRequest($ns.'datetype', 'filter_datetype', '', '');
        $state['filter_scope']       = $app->getUserStateFromRequest($ns.'scope', 'filter_scope', '', '');
        $state['filter_event']       = $app->getUserStateFromRequest($ns.'event', 'filter_event', '', '');
        $state['filter_points_from']    = $app->getUserStateFromRequest($ns.'points_from', 'filter_points_from', '', '');
        $state['filter_points_to']      = $app->getUserStateFromRequest($ns.'points_to', 'filter_points_to', '', '');
        $state['filter_profile']       = $app->getUserStateFromRequest($ns.'profile', 'filter_profile', '', '');
                
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }
    
    /**
     * Saves an item and redirects based on task
     * @return void
     */
    function save()
    {
        $model  = $this->getModel( $this->get('suffix') );
        $row = $model->getTable();
        $row->load( $model->getId() );
        $row->bind( $_POST );

        if ($pointrule_event_new = JRequest::getVar('pointrule_event_new', ''))
        {
            $row->pointrule_event = $pointrule_event_new;
        }
        
        if ($pointrule_scope_new = JRequest::getVar('pointrule_scope_new', ''))
        {
            $row->pointrule_scope = $pointrule_scope_new; 
        }
        
        if ( $row->save() )
        {
            $model->setId( $row->id );
            $this->messagetype  = 'message';
            $this->message      = JText::_( 'Saved' );

            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
        }
            else
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( 'Save Failed' )." - ".$row->getError();
        }

        $redirect = "index.php?option=com_ambra";
        $task = JRequest::getVar('task');
        switch ($task)
        {
            case "savenew":
                $redirect .= '&view='.$this->get('suffix').'&task=add';
              break;
            case "apply":
                $redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$model->getId();
              break;
            case "save":
            default:
                $redirect .= "&view=".$this->get('suffix');
              break;
        }

        $redirect = JRoute::_( $redirect, false );
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
}

?>