<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load( 'AmbraHelperBase', 'helpers._base' );

class AmbraHelperRule extends AmbraHelperBase
{

    /**
     * Reads the scope storage folder and tries to find the new scope events
     * 
     * @return unknown_type
     */
    function getScopes()
    {
        $success = null;
        
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.archive');
        jimport('joomla.filesystem.path');
        jimport('joomla.installer.installer' );
        jimport('joomla.installer.helper' );

        // get xml files
        $folder = Ambra::getPath( 'scopes' );
        if (!JFolder::exists($folder)) 
        {
            $this->setError( JText::_( "Folder Does Not Exist" ) );
            return false;
        }
        $xmlFilesInDir = JFolder::files($folder, '.xml$');

        if (empty($xmlFilesInDir))
        {
            $this->setError( JText::_( "No Files in Dir" ) );
            return false;
        }
        
        //if there were any xml files found
        foreach ($xmlFilesInDir as $xmlfile)
        {
            $xml =  JFactory::getXMLParser('Simple');

            if (!$xml->loadFile($folder.DS.$xmlfile)) {
                continue;
            }
            
            if ( !is_object($xml->document) || ($xml->document->name() != 'events')) 
            {
                continue;
            }
            
            if ($events = $xml->document->children())
            {
                $scope = JFile::stripExt( $xmlfile );
                
                $count = 0;
                foreach ($events as $xmlevent)
                {
                    $event = $xmlevent->attributes('title');
                    
                    // if the event doesn't exist in the DB, create it disabled
                    JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
                    $pointrule = JTable::getInstance('PointRules', 'AmbraTable');
                    $pointrule->load( array( 'pointrule_scope'=>$scope, 'pointrule_event'=>$event ) );
                    if (!empty($pointrule->pointrule_id))
                    {
                        continue;
                    }
                    
                    $new = array();
                    $new['pointrule_scope']     = $scope;
                    $new['pointrule_event']     = $event;
                    
                    if ($properties = $xmlevent->children())
                    {
                        foreach ($properties as $prop)
                        {
                            switch ($prop->name())
                            {
                                case "name":
                                    $new['pointrule_name'] = $prop->data();
                                    break;
                                case "description":
                                    $new['pointrule_description'] = $prop->data();
                                    break;
                                case "params":
                                    if ($params = $prop->children())
                                    {
                                        $pointrule_params = array();
                                        foreach ($params as $param)
                                        {
                                            $pointrule_params[$param->attributes('name')] = $param->attributes('default');
                                        }
                                        $registry = new JRegistry();
                                        $registry->loadArray($pointrule_params);
                                        $new['pointrule_params'] = $registry->toString();
                                    }
                                    break;
                                case "auto_approve":
                                    $new['pointrule_auto_approve'] = $prop->data();
                                    break;
                                case "uses":
                                    $new['pointrule_uses'] = $prop->data();
                                    break;
                                case "uses_max":
                                    $new['pointrule_uses_max'] = $prop->data();
                                    break;
                                case "uses_per_user":
                                    $new['pointrule_uses_per_user'] = $prop->data();
                                    break;
                                case "uses_per_user_per_day":
                                    $new['pointrule_uses_per_user_per_day'] = $prop->data();
                                    break;
                                case "type_id":
                                    $new['pointtype_id'] = $prop->data();
                                    break;
                                case "value":
                                    $new['pointrule_value'] = $prop->data();
                                    break;
                            }
                        }
                    }
                    $pointrule->bind( $new );
                    if (!$pointrule->save())
                    {
                        JError::raiseNotice('getScopes', $pointrule->getError() );
                    }
                        else
                    {
                        JFactory::getApplication()->enqueueMessage( sprintf( JText::_( "Installed Event x for x" ), $event, $scope ) );
                        $success = true;
                    }
                }
            }
        }
        return $success;
    }
    
    /**
     * Gets the number of uses for a pointrule
     * 
     * @param $pointrule_id
     * @param $user_id
     * @param $type
     * @return unknown_type
     */
    function getUses( $pointrule_id, $user_id=null, $type='total' )
    {
        JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
        $model = JModel::getInstance('PointHistory', 'AmbraModel');
        $model->setState( 'filter_enabled', 1 );
        $model->setState( 'filter_rule', $pointrule_id );
        switch ($type)
        {
            case "today":
                $today = Ambra::getClass( "AmbraHelperBase", "helpers._base" )->getToday();
                $model->setState( 'filter_date_from', $today );
                break;
            default:
                break;
        }
        
        if (!empty($user_id))
        {
            $model->setState( 'filter_user', $user_id );
        }
        
        $count = $model->getTotal();
        return $count;
    }
}