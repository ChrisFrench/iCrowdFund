<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load( 'AmbraHelperBase', 'helpers._base' );

class AmbraHelperDiagnostics extends AmbraHelperBase 
{
    /**
     * Redirects with message
     * 
     * @param object $message [optional]    Message to display
     * @param object $type [optional]       Message type
     */
    function redirect($message = '', $type = '')
    {
        $mainframe = JFactory::getApplication();
        
        if ($message) 
        {
            $mainframe->enqueueMessage($message, $type);
        }
        
        JRequest::setVar('controller', 'dashboard');
        JRequest::setVar('view', 'dashboard');
        JRequest::setVar('task', '');
        return;
    }    

    /**
     * Performs basic checks on your installation to ensure it is OK
     * @return unknown_type
     */
    function checkInstallation() 
    {
        if (!$this->checkConfigTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_CONFIG_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkRolesTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_ROLES_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkProfilesTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_PROFILES_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkActsTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_ACTS_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkActionsTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_ACTIONS_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkCategoriesTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_CATEGORIES_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkFieldsTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_FIELDS_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkUserdataTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_USERDATA_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkUserRolesTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_USERROLES_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkRoleActionsTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_ROLEACTIONS_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
            
        if (!$this->checkProfileCategoriesTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_PROFILECATEGORIES_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkCategoryFieldsTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_CATEGORYFIELDS_TABLE FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->checkUserdataTablePoints()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_USERDATA_TABLE_POINTS FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkProfilesTablePoints()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_PROFILES_TABLE_POINTS FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->checkPointHistoryTable()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_POINTHISTORY_TABLE_POINTS FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->checkSocialNetworkFields()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_SOCIALNETWORKFIELDS FAILED') .' :: '. $this->getError(), 'error' );
        }        
         if (!$this->insertIsApprovalUserdata()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_SOCIALNETWORKFIELDS FAILED') .' :: '. $this->getError(), 'error' );
        } 
        if (!$this->insertIsApprovalUserdata()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECK_SOCIALNETWORKFIELDS FAILED') .' :: '. $this->getError(), 'error' );
        }  
        if (!$this->insertExpiredPointhistory())
        {
        return $this->redirect( JText::_('DIAGNOSTIC CHECK_SOCIALNETWORKFIELDS FAILED') .' :: '. $this->getError(), 'error' );	
        }
    }
    
    /**
     * Inserts fields into a table
     * 
     * @param string $table
     * @param array $fields
     * @param array $definitions
     * @return boolean
     */
    function insertTableFields($table, $fields, $definitions)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if (!$rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` ADD `{$field}` {$definitions[$field]}; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
    /**
     * Changes fields in a table
     * 
     * @param string $table
     * @param array $fields
     * @param array $definitions
     * @param array $newnames
     * @return boolean
     */
    function changeTableFields($table, $fields, $definitions, $newnames)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if ($rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` CHANGE `{$field}` `{$newnames[$field]}` {$definitions[$field]}; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
    /**
     * Drops fields in a table
     * 
     * @param string $table
     * @param array $fields
     * @return boolean
     */
    function dropTableFields($table, $fields)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if ($rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` DROP `{$field}` ; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
    /**
     * Check if the config table is correct
     * 
     * @return boolean
     */
    function checkConfigTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkConfigTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_config';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "config_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "config_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT ''";
        
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "description";
            $fields[] = "ordering";
            $fields[] = "published";
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkConfigTable') );
            $config->config_name = 'checkConfigTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the roles table is correct
     * 
     * @return boolean
     */
    function checkRolesTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkRolesTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_roles';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "role_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "role_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT '' ";

        $fields[] = "description";
            $newnames["description"] = "role_description";
            $definitions["description"] = "TEXT NOT NULL DEFAULT '' ";

        $fields[] = "parentid";
            $newnames["parentid"] = "parent_id";
            $definitions["parentid"] = "int(11) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "ordering";
            $fields[] = "created_datetime";
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            $fields[] = "site_option";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            $fields = array();
            $fields[] = "role_enabled";
                $definitions["role_enabled"] = "tinyint(1) NOT NULL ";
                
            $fields[] = "isroot";
                $definitions["isroot"] = "tinyint(1) NOT NULL ";
                
            $fields[] = "role_params";
                $definitions["role_params"] = "TEXT NOT NULL DEFAULT '' ";

            if (!$this->insertTableFields( $table, $fields, $definitions ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkRolesTable') );
            $config->config_name = 'checkRolesTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkProfilesTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkProfilesTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_profiles';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "profile_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "profile_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT '' ";

        $fields[] = "description";
            $newnames["description"] = "profile_description";
            $definitions["description"] = "TEXT NOT NULL DEFAULT '' ";

        $fields[] = "published";
            $newnames["published"] = "profile_enabled";
            $definitions["published"] = "tinyint(1) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "created_datetime";
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            $fields = array();
            $fields[] = "profile_params";
                $definitions["profile_params"] = "TEXT NOT NULL DEFAULT '' ";
                
            $fields[] = "profile_img";
                $definitions["profile_img"] = "varchar(255) NOT NULL DEFAULT '' ";

            if (!$this->insertTableFields( $table, $fields, $definitions ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkProfilesTable') );
            $config->config_name = 'checkProfilesTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkActsTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkActsTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_acts';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "act_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "act_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT '' ";

        $fields[] = "description";
            $newnames["description"] = "act_params";
            $definitions["description"] = "TEXT NOT NULL DEFAULT '' ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "published";
            $fields[] = "value";
            $fields[] = "ordering";
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkActsTable') );
            $config->config_name = 'checkActsTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkActionsTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkActionsTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_actions';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "action_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "actid";
            $newnames["actid"] = "act_id";
            $definitions["actid"] = "int(11) NOT NULL ";

        $fields[] = "ispublic";
            $newnames["ispublic"] = "is_public";
            $definitions["ispublic"] = "tinyint(1) NOT NULL ";
            
        $fields[] = "site_option";
            $newnames["site_option"] = "option";
            $definitions["site_option"] = "varchar(255) NOT NULL DEFAULT '' ";
            
        $fields[] = "site_view";
            $newnames["site_view"] = "view";
            $definitions["site_view"] = "varchar(255) NOT NULL DEFAULT '' ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkActionsTable') );
            $config->config_name = 'checkActionsTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkCategoriesTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkCategoriesTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_categories';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "category_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "category_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT '' ";

        $fields[] = "description";
            $newnames["description"] = "category_description";
            $definitions["description"] = "TEXT NOT NULL DEFAULT '' ";

        $fields[] = "published";
            $newnames["published"] = "category_enabled";
            $definitions["published"] = "tinyint(1) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "created_datetime";
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            $fields = array();
            $fields[] = "category_params";
                $definitions["category_params"] = "TEXT NOT NULL DEFAULT '' ";

            if (!$this->insertTableFields( $table, $fields, $definitions ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkCategoriesTable') );
            $config->config_name = 'checkCategoriesTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkFieldsTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkFieldsTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_fields';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "field_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "title";
            $newnames["title"] = "field_name";
            $definitions["title"] = "varchar(255) NOT NULL DEFAULT '' ";

        $fields[] = "description";
            $newnames["description"] = "field_description";
            $definitions["description"] = "TEXT NOT NULL DEFAULT '' ";

        $fields[] = "published";
            $newnames["published"] = "field_enabled";
            $definitions["published"] = "tinyint(1) NOT NULL ";

        $fields[] = "params";
            $newnames["params"] = "field_params";
            $definitions["params"] = "TEXT NOT NULL DEFAULT '' ";
            
        $fields[] = "typeid";
            $newnames["typeid"] = "type_id";
            $definitions["typeid"] = "tinyint(1) NOT NULL ";
            
        $fields[] = "iscore";
            $newnames["iscore"] = "is_core";
            $definitions["iscore"] = "tinyint(1) NOT NULL ";

        $fields[] = "description_published";
            $newnames["description_published"] = "description_enabled";
            $definitions["description_published"] = "tinyint(1) NOT NULL ";
            
        $fields[] = "image_published";
            $newnames["image_published"] = "image_enabled";
            $definitions["image_published"] = "tinyint(1) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            $fields = array();
            $fields[] = "checked_out";
            $fields[] = "checked_out_time";
            if (!$this->dropTableFields( $table, $fields ))
            {
                // track error?
            }
            
            $fields = array();
            $fields[] = "list_displayed";
                $definitions["list_displayed"] = "tinyint(1) NOT NULL ";
            $fields[] = "profile_displayed";
                $definitions["profile_displayed"] = "tinyint(1) NOT NULL DEFAULT '1' ";

            if (!$this->insertTableFields( $table, $fields, $definitions ))
            {
                // track error?
            }
            
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkFieldsTable') );
            $config->config_name = 'checkFieldsTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkUserdataTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkUserdataTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_userdata';
        $definitions = array();
        $fields = array();
        
        $fields[] = "id";
            $newnames["id"] = "userdata_id";
            $definitions["id"] = "int(11) NOT NULL AUTO_INCREMENT";

        $fields[] = "userid";
            $newnames["userid"] = "user_id";
            $definitions["userid"] = "int(11) NOT NULL ";
            
        $fields[] = "profileid";
            $newnames["profileid"] = "profile_id";
            $definitions["profileid"] = "int(11) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkUserdataTable') );
            $config->config_name = 'checkUserdataTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkUserRolesTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkUserRolesTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_roles2users';
        $definitions = array();
        $fields = array();

        $fields[] = "userid";
            $newnames["userid"] = "user_id";
            $definitions["userid"] = "int(11) NOT NULL ";
            
        $fields[] = "roleid";
            $newnames["roleid"] = "role_id";
            $definitions["roleid"] = "int(11) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkUserRolesTable') );
            $config->config_name = 'checkUserRolesTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkRoleActionsTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkRoleActionsTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_roles2actions';
        $definitions = array();
        $fields = array();

        $fields[] = "actionid";
            $newnames["actionid"] = "action_id";
            $definitions["actionid"] = "int(11) NOT NULL ";
            
        $fields[] = "roleid";
            $newnames["roleid"] = "role_id";
            $definitions["roleid"] = "int(11) NOT NULL ";

        $fields[] = "ispublic";
            $newnames["ispublic"] = "is_public";
            $definitions["ispublic"] = "tinyint(1) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkRoleActionsTable') );
            $config->config_name = 'checkRoleActionsTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkProfileCategoriesTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkProfileCategoriesTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_categories2profiles';
        $definitions = array();
        $fields = array();

        $fields[] = "categoryid";
            $newnames["categoryid"] = "category_id";
            $definitions["categoryid"] = "int(11) NOT NULL ";
            
        $fields[] = "profileid";
            $newnames["profileid"] = "profile_id";
            $definitions["profileid"] = "int(11) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkProfileCategoriesTable') );
            $config->config_name = 'checkProfileCategoriesTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkCategoryFieldsTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkCategoryFieldsTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_fields2categories';
        $definitions = array();
        $fields = array();

        $fields[] = "fieldid";
            $newnames["fieldid"] = "field_id";
            $definitions["fieldid"] = "int(11) NOT NULL ";
            
        $fields[] = "categoryid";
            $newnames["categoryid"] = "category_id";
            $definitions["categoryid"] = "int(11) NOT NULL ";
            
        if ($this->changeTableFields( $table, $fields, $definitions, $newnames ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkCategoryFieldsTable') );
            $config->config_name = 'checkCategoryFieldsTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkUserdataTablePoints() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkUserdataTablePoints', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_userdata';
        $definitions = array();
        $fields = array();

        $fields[] = "points_total";
            $definitions["points_total"] = "int(11) NOT NULL";
        
        $fields[] = "points_current";
            $definitions["points_current"] = "int(11) NOT NULL";

        $fields[] = "points_maximum";
            $definitions["points_maximum"] = "varchar(11) NOT NULL DEFAULT '-1' ";
            
        $fields[] = "points_maximum_per_day";
            $definitions["points_maximum_per_day"] = "varchar(11) NOT NULL DEFAULT '' ";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkUserdataTablePoints') );
            $config->config_name = 'checkUserdataTablePoints';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    /**
     * 
     */
	function insertIsApprovalUserdata() 
    {
       
        
        $table = '#__ambra_userdata';
        $definitions = array();
        $fields = array();
        $fields[] = "is_manual_approval";
        $definitions["is_manual_approval"] = "int(1) NOT NULL";
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'insertIsApprovalUserdata') );
            $config->config_name = 'insertIsApprovalUserdata';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    function insertExpiredPointhistory() 
    {
        $table = '#__ambra_pointhistory';
        $definitions = array();
        $fields = array();
        $fields[] = "is_manual_approval";
        $definitions["is_manual_approval"] = "int(1) NOT NULL";
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'insertExpiredPointhistory') );
            $config->config_name = 'insertExpiredPointhistory';
            $config->value = '1';
            $config->save();
            return true;
        }
    

        return false;  
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkProfilesTablePoints() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkProfilesTablePoints', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_profiles';
        $definitions = array();
        $fields = array();

        $fields[] = "profile_max_points_per_day";
            $definitions["profile_max_points_per_day"] = "varchar(11) NOT NULL DEFAULT '' ";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkProfilesTablePoints') );
            $config->config_name = 'checkProfilesTablePoints';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkPointHistoryTable() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkPointHistoryTable', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_pointhistory';
        $definitions = array();
        $fields = array();

        $fields[] = "pointcoupon_id";
            $definitions["pointcoupon_id"] = "int(11) NOT NULL ";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkPointHistoryTable') );
            $config->config_name = 'checkPointHistoryTable';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;        
    }
    
    /**
     * Check if the table is correct
     * 
     * @return boolean
     */
    function checkSocialNetworkFields() 
    {
        // if this has already been done, don't repeat
        if (AmbraConfig::getInstance()->get('checkSocialNetworkFields', '0'))
        {
            return true;
        }
        
        $table = '#__ambra_userdata';
        $definitions = array();
        $fields = array();

        $fields[] = "profile_linkedin";
            $definitions["profile_linkedin"] = "varchar(255) NOT NULL DEFAULT '' ";
        $fields[] = "profile_facebook";
            $definitions["profile_facebook"] = "varchar(255) NOT NULL DEFAULT '' ";
        $fields[] = "profile_twitter";
            $definitions["profile_twitter"] = "varchar(255) NOT NULL DEFAULT '' ";
        $fields[] = "profile_youtube";
            $definitions["profile_youtube"] = "varchar(255) NOT NULL DEFAULT '' ";
                        
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'AmbraTable' );
            $config->load( array( 'config_name'=>'checkSocialNetworkFields') );
            $config->config_name = 'checkSocialNetworkFields';
            $config->value = '1';
            $config->save();
            return true;
        }

        return false;
    }
}
