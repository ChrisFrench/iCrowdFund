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
defined('_JEXEC') or die('Restricted access');

Billets::load( 'BilletsPluginBase', 'library.plugin.base' );
jimport('joomla.utilities.string');


class plgBilletsTool_RSTicketsMigration extends BilletsPluginBase
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename, 
	 *                         forcing it to be unique 
	 */
    var $_element   = 'tool_rsticketsmigration';
    
    /**
     * @var $_tablename  string  A required tablename to use when verifying the provided prefix  
     */    
    var $_tablename = 'rstickets_tickets';
    
	function plgBilletsTool_RSTicketsMigration(& $subject, $config) 
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}
    
    /**
     * Checks to make sure that this plugin is the one being triggered by the extension
     *
     * @access public
     * @return mixed Parameter value
     * @since 1.5
     */
    function _isMe( $row ) 
    {
        $element = $this->_element;
        $success = false;
        if (is_object($row) && !empty($row->element) && $row->element == $element )
        {
            $success = true;
        }
        
        if (is_string($row) && $row == $element ) {
            $success = true;
        }
        
        return $success;
    }
    
    /**
     * Wraps the given text in the HTML
     *
     * @param string $text
     * @return string
     * @access protected
     */
    function _renderMessage($message = '')
    {
        $vars = new JObject();
        $vars->message = $message;
        $html = $this->_getLayout('message', $vars);
        return $html;
    }
    
    /**
     * Gets the parsed layout file
     * 
     * @param string $layout The name of  the layout file
     * @param object $vars Variables to assign to
     * @param string $plugin The name of the plugin
     * @param string $group The plugin's group
     * @return string
     * @access protected
     */
    function _getLayout($layout, $vars = false, $plugin = '', $group = 'billets' )
    {
        if (empty($plugin)) 
        {
            $plugin = $this->_element;
        }
        
        ob_start();
        $layout = $this->_getLayoutPath( $plugin, $group, $layout ); 
        include($layout);
        $html = ob_get_contents(); 
        ob_end_clean();
        
        return $html;
    }
    
    
    /**
     * Get the path to a layout file
     *
     * @param   string  $plugin The name of the plugin file
     * @param   string  $group The plugin's group
     * @param   string  $layout The name of the plugin layout file
     * @return  string  The path to the plugin layout file
     * @access protected
     */
    function _getLayoutPath($plugin, $group, $layout = 'default')
    {
        $app = JFactory::getApplication();

        // get the template and default paths for the layout
        $templatePath = JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'plugins'.DS.$group.DS.$plugin.DS.$layout.'.php';
        $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.'tmpl'.DS.$layout.'.php';

        // if the site template has a layout override, use it
        jimport('joomla.filesystem.file');
        if (JFile::exists( $templatePath )) 
        {
            return $templatePath;
        } 
        else 
        {
            return $defaultPath;
        }
    }

    /**
     * This displays the content article
     * specified in the plugin's params
     * 
     * @return unknown_type
     */
    function _displayArticle()
    {
        $html = '';
        
        $articleid = $this->params->get('articleid');
        if ($articleid)
        {
            Billets::load( 'BilletsArticle', 'library.article' );
            $html = BilletsArticle::display( $articleid );
        }
        
        return $html;
    }
    
    /**
     * Checks for a form token in the request
     * Using a suffix enables multi-step forms
     * 
     * @param string $suffix
     * @return boolean
     */
    function _checkToken( $suffix='', $method='post' )
    {
        $token  = JUtility::getToken();
        $token .= ".".strtolower($suffix);
        if (JRequest::getVar( $token, '', $method, 'alnum' ))
        {
            return true;
        }
        return false;
    }
    
    /**
     * Generates an HTML form token and affixes a suffix to the token
     * enabling the form to be identified as a step in a process 
     *  
     * @param string $suffix
     * @return string HTML
     */
    function _getToken( $suffix='' )
    {
        $token  = JUtility::getToken();
        $token .= ".".strtolower($suffix);
        $html  = '<input type="hidden" name="'.$token.'" value="1" />';
        $html .= '<input type="hidden" name="tokenSuffix" value="'.$suffix.'" />';
        return $html;
    }
    
    /**
     * Gets the suffix affixed to the form's token
     * which helps identify which step this is
     * in a multi-step process 
     *  
     * @return string
     */
    function _getTokenSuffix( $method='post' )
    {
        $suffix = JRequest::getVar( 'tokenSuffix', '', $method );
        if (!$this->_checkToken($suffix, $method))
        {
            // what to do if there isn't this suffix's token in the request?
            // anything?
        }
        return $suffix;
    }
    	
    /**
     * Overriding 
     * 
     * @param $options
     * @return unknown_type
     */
    function onGetToolView( $row )
    {
        if (!$this->_isMe($row)) 
        {
            return null;
        }
        
        // go to a "process suffix" method
        // which will first validate data submitted,
        // and if OK, will return the html?
        $suffix = $this->_getTokenSuffix();
        $html = $this->_processSuffix( $suffix );

        return $html;
    }
    
    /**
     * Validates the data submitted based on the suffix provided
     * 
     * @param $suffix
     * @return html
     */
    function _processSuffix( $suffix='' )
    {
        $html = "";
        
        switch($suffix)
        {
            case"2":
                if (!$verify = $this->_verifyDB())
                {
                    JError::raiseNotice('_verifyDB', $this->getError());
                    $html .= $this->_renderForm( '1' );
                }
                    else
                {
                    $this->getTicketStatusMap();
                    // migrate the data and output the results
                    $html .= $this->_doMigration();
                }
                break;
            case"1":
                if (!$verify = $this->_verifyDB())
                {
                    JError::raiseNotice('_verifyDB', $this->getError());
                    $html .= $this->_renderForm( '1' );
                }
                    else
                {
                    $suffix++;
                    // display a 'connection verified' message
                    // and request confirmation before migrating data
                    $this->getTicketStatusMap();
                    $html .= $this->_renderForm( $suffix );
                    $html .= $this->_renderView( $suffix );                    
                }
                break;
            default:
                $html .= $this->_renderForm( '1' );
                break;
        }
        
        return $html;
    }
	
    /**
     * Prepares the 'view' tmpl layout
     *  
     * @return unknown_type
     */
    function _renderView( $suffix='' )
    {
        $vars = new JObject();
        $layout = 'view_'.$suffix;
        $html = $this->_getLayout($layout, $vars);
        
        return $html;
    }
    
    /**
     * Prepares variables for the form
     * 
     * @return unknown_type
     */
    function _renderForm( $suffix='' )
    {
        $vars = new JObject();
        $vars->token = $this->_getToken( $suffix );
        $vars->state = $this->_getState();
        
        $layout = 'form_'.$suffix;
        $html = $this->_getLayout($layout, $vars);
        
        return $html;
    }

    /**
     * Gets the appropriate values from the request
     * 
     * @return unknown_type
     */
    function _getState()
    {
        if (empty($this->_state))
        {
            $state = new JObject();
            $state->host = '';
            $state->user = '';
            $state->password = '';
            $state->database = '';
            $state->prefix = 'jos_';
            $state->rs_prefix = 'rstickets_';
            $state->driver = 'mysql';
            $state->port = '3306';
            
            foreach ($state->getProperties() as $key => $value)
            {
                $new_value = JRequest::getVar( $key );
                $value_exists = array_key_exists( $key, $_POST );
                if ( $value_exists && !empty($key) )
                {
                    $state->$key = $new_value;
                }
            }
            
            $this->_state = $state;
        }
        
        return $this->_state;
    }

    /**
     * Perform the data migration
     * 
     * @return html
     */
    function _doMigration()
    {
        $html = "";
        $vars = new JObject();
        
        // perform the data migration
        // grab all the data and insert it into the billets tables
        // if the host or database names are diff from the joomla one
        $state = $this->_getState();

        $vars->results = $this->_migrateExternal($state->prefix, $state->rs_prefix);
        
        $suffix = $this->_getTokenSuffix();
        $suffix++;
        $layout = 'view_'.$suffix;
                
        $html = $this->_getLayout($layout, $vars);
        return $html;
    }

    /**
     * 
     * Enter description here ...
     * @return unknown_type
     */
    function getTicketStatusMap()
    {
        $state = $this->_getState();
        $sourceDB = $this->_verifyDB();
        
        $p = $state->prefix . $state->rs_prefix;
        
        $sourceDB->setQuery( "SELECT DISTINCT(`TicketStatus`) FROM {$p}tickets" );
        $existing_states = $sourceDB->loadResultArray();
        $state->existing_states = $existing_states;
        
        $post_states = JRequest::getVar( 'status_map', array(), 'post', 'array' );
        $state->status_map = $post_states;

        $this->_state = $state;
    }
    
    /**
     * Do the migration
     * where the target and source db are not the same 
     * 
     * @return array
     */
    function _migrateExternal($prefix = 'jos_', $rs_prefix = 'rstickets_')
    {
        $queries = array();
        
        $p = $prefix.$rs_prefix;
        
        // migrate categories
        $queries[0]->title = "DEPARTMENTS";
        $queries[0]->select = "
            SELECT c.DepartmentId, c.DepartmentName, 1 AS enabled
            FROM {$p}departments AS c;
        ";
        $queries[0]->insert = "
            INSERT IGNORE INTO #__billets_categories ( id, title, enabled )
            VALUES ( %s )
        ";

        // migrate staff
        $queries[1]->title = "STAFF";
        $queries[1]->select = "
            SELECT u2c.UserId, u2c.DepartmentId
            FROM {$p}staff_to_department AS u2c;
        ";
        $queries[1]->insert = "
            INSERT IGNORE INTO #__billets_u2c ( userid, categoryid )
            VALUES ( %s )
        ";
                
        $results = array();
        $jDBO = JFactory::getDBO();
        $sourceDB = $this->_verifyDB();        
        $n=0;
        foreach ($queries as $query)
        {
            $errors = array();
            $sourceDB->setQuery($query->select);
            if ($rows = $sourceDB->loadObjectList())
            {                
                foreach ($rows as $row)
                {
                    $values = array();
                    foreach (get_object_vars($row) as $key => $value)
                    {
                        $values[] = $jDBO->Quote( $value );
                    }
                    $string = implode( ",", $values );
                    $insert_query = sprintf( $query->insert, $string );
                    $jDBO->setQuery( $insert_query );
                    if (!$jDBO->query())
                    {
                        $errors[] = $jDBO->getErrorMsg();
                    }
                }
            }
            $results[$n]->title = $query->title;
            $results[$n]->query = $query->insert;
            $results[$n]->error = implode('\n', $errors);
            $results[$n]->affectedRows = count( $rows );
            $n++; 
        }

        // the following need a separate function
        $results[$n] = $this->migrateTickets($prefix, $rs_prefix);
        $n++;
        $results[$n] = $this->migrateTicketComments($prefix, $rs_prefix);
        $n++;
        $results[$n] = $this->migrateCustomFields($prefix, $rs_prefix);
        $n++;
        $results[$n] = $this->migrateCustomFieldValues($prefix, $rs_prefix);
        
        return $results;
    }
    
    /**
     * Migrates the tickets
     * 
     * @param $prefix
     * @param $rs_prefix
     * @return unknown_type
     */
    function migrateTickets($prefix = 'jos_', $rs_prefix = 'rstickets_')
    {
        $p = $prefix.$rs_prefix;
        
        $query = new JObject();
        
        $query->title = "TICKETS";
        $query->select = "
            SELECT t.TicketId, t.CustomerId, t.DepartmentId, t.TicketSubject, t.TicketStatus, t.TicketTime
            FROM {$p}tickets AS t;
        ";
        $query->insert = "
            INSERT IGNORE INTO #__billets_tickets ( id, sender_userid, categoryid, title, stateid, created_datetime )
            VALUES ( %s )
        ";

        $state = $this->_getState();
        $jDBO = JFactory::getDBO();
        $sourceDB = $this->_verifyDB();
        
        $errors = array();
        $sourceDB->setQuery($query->select);
        if ($rows = $sourceDB->loadObjectList())
        {                
            foreach ($rows as $row)
            {
                // convert the time
                $row->TicketTime = date('Y-m-d', $row->TicketTime);
                
                // convert the status
                $row->TicketStatus = @$state->status_map[$row->TicketStatus];
                
                $values = array();
                foreach (get_object_vars($row) as $key => $value)
                {
                    $values[] = $jDBO->Quote( $value );
                }
                $string = implode( ",", $values );
                $insert_query = sprintf( $query->insert, $string );
                $jDBO->setQuery( $insert_query );
                if (!$jDBO->query())
                {
                    $errors[] = $jDBO->getErrorMsg();
                }
            }
        }
        
        $result = new JObject();
        $result->title = $query->title;
        $result->query = $query->insert;
        $result->error = implode('\n', $errors);
        $result->affectedRows = count( $rows );
        
        return $result;
    }
    
    /**
     * Migrates the tickets comments
     * 
     * @param $prefix
     * @param $rs_prefix
     * @return unknown_type
     */
    function migrateTicketComments($prefix = 'jos_', $rs_prefix = 'rstickets_')
    {
        $p = $prefix.$rs_prefix;
        
        $query = new JObject();
        
        $query->title = "TICKET COMMENTS";
        $query->select = "
            SELECT t.TicketId, t.UserId, t.TicketMessage, t.TicketTime
            FROM {$p}ticket_message AS t;
        ";
        $query->insert = "
            INSERT IGNORE INTO #__billets_messages ( ticketid, userid_from, message, datetime )
            VALUES ( %s )
        ";

        $state = $this->_getState();
        $jDBO = JFactory::getDBO();
        $sourceDB = $this->_verifyDB();
        
        $errors = array();
        $sourceDB->setQuery($query->select);
        if ($rows = $sourceDB->loadObjectList())
        {                
            foreach ($rows as $row)
            {
                // convert the time
                $row->TicketTime = date('Y-m-d', $row->TicketTime);
                
                $values = array();
                foreach (get_object_vars($row) as $key => $value)
                {
                    $values[] = $jDBO->Quote( $value );
                }
                $string = implode( ",", $values );
                $insert_query = sprintf( $query->insert, $string );
                $jDBO->setQuery( $insert_query );
                if (!$jDBO->query())
                {
                    $errors[] = $jDBO->getErrorMsg();
                }
            }
        }
        
        $result = new JObject();
        $result->title = $query->title;
        $result->query = $query->insert;
        $result->error = implode('\n', $errors);
        $result->affectedRows = count( $rows );
        
        return $result;
    }
    
    /**
     * Migrates the custom fields definitions
     * 
     * @param $prefix
     * @param $rs_prefix
     * @return unknown_type
     */
    function migrateCustomFields($prefix = 'jos_', $rs_prefix = 'rstickets_')
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        
        $p = $prefix.$rs_prefix;
        
        $query = new JObject();
        
        $query->title = "CUSTOM FIELDS";
        $query->select = "
            SELECT t.CustomFieldId, t.CustomFieldName, t.CustomFieldLabel, t.CustomFieldType, t.CustomFieldValues
            FROM {$p}custom_fields AS t;
        ";
        $query->insert = "
            INSERT IGNORE INTO #__billets_fields ( id, title, description, typeid, options )
            VALUES ( %s )
        ";

        $state = $this->_getState();
        $jDBO = JFactory::getDBO();
        $sourceDB = $this->_verifyDB();
        
        $errors = array();
        $sourceDB->setQuery($query->select);
        if ($rows = $sourceDB->loadObjectList())
        {                
            foreach ($rows as $row)
            {
                // convert the time
                $row->CustomFieldType = $this->getTypeID( $row->CustomFieldType );
                
                $values = array();
                foreach (get_object_vars($row) as $key => $value)
                {
                    $values[] = $jDBO->Quote( $value );
                }
                $string = implode( ",", $values );
                $insert_query = sprintf( $query->insert, $string );
                $jDBO->setQuery( $insert_query );
                if (!$jDBO->query())
                {
                    $errors[] = $jDBO->getErrorMsg();
                }
                
                // use the table class's store method to force billets to create the field in the database
                $table = JTable::getInstance( 'Fields', 'BilletsTable' );
                $table->load( $row->CustomFieldId );
                $table->store();
            }
        }
        
        $result = new JObject();
        $result->title = $query->title;
        $result->query = $query->insert;
        $result->error = implode('\n', $errors);
        $result->affectedRows = count( $rows );
        
        return $result;
    }

    /**
     * Migrates the tickets comments
     * 
     * @param $prefix
     * @param $rs_prefix
     * @return unknown_type
     */
    function migrateCustomFieldValues($prefix = 'jos_', $rs_prefix = 'rstickets_')
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        
        $p = $prefix.$rs_prefix;
        
        $query = new JObject();
        
        $query->title = "CUSTOM FIELD VALUES";
        $query->select = "
            SELECT t.TicketId, t.CustomFieldId, t.CustomFieldValue
            FROM {$p}custom_fields_value AS t
            WHERE t.TicketId = '%s'
            ;
        ";
        $query->insert = "";

        $state = $this->_getState();
        $jDBO = JFactory::getDBO();
        $sourceDB = $this->_verifyDB();
        
        // get the unique tickets with cf values
        $sourceDB->setQuery( "SELECT DISTINCT(`TicketId`) FROM {$p}custom_fields_value" );
        $tickets = $sourceDB->loadResultArray();
        
        $errors = array();        
        foreach ($tickets as $ticket_id)
        {
            $select_query = sprintf( $query->select, $ticket_id );
            $sourceDB->setQuery($select_query);

            if ($rows = $sourceDB->loadObjectList())
            {
                $ticket = JTable::getInstance( 'Tickets', 'BilletsTable' );
                $ticket->load( $ticket_id );
                    
                foreach ($rows as $row)
                {
                    // load the field object to find the db_fieldname
                    $field = JTable::getInstance( 'Fields', 'BilletsTable' );
                    $field->load( $row->CustomFieldId );
                    
                    if (!empty($field->db_fieldname))
                    {
                        $fieldname = $field->db_fieldname;
                        // then set the $ticket->db_fieldname = value
                        $ticket->$fieldname = $row->CustomFieldValue;
                    }
                    
                }
                
                $ticket->store();
            }
        }
        
        $result = new JObject();
        $result->title = $query->title;
        $result->query = $query->insert;
        $result->error = implode('\n', $errors);
        $result->affectedRows = count( $rows );
        
        return $result;
    }
    
    /**
     * Convert the rsticket cf type to a billets type
     * @param $CustomFieldType
     * @return unknown_type
     */
    function getTypeID( $CustomFieldType )
    {
        // TODO Figure out what other custom field types RSTickets uses
        switch ($CustomFieldType)
        {
            case "textarea":
                $typeid = '2';
                break;
            case "textbox":
            default:
                $typeid = '1';
                break;
        }
        
        return $typeid;
    }
    
    /**
     * Attempts to connect to a DB using provided info
     * Will often be extended
     *   
     * @return boolean if fail, JDatabase object if success 
     */
    function _verifyDB()
    {
        $state = $this->_getState();
        
        // verify connection
        $option = array();
        $option['driver']   = $state->driver;           // Database driver name
        $option['host']     = $state->host;             // Database host name
        if ($state->port != '3306') 
        { 
            $option['host'] .= ":".$state->port;        // alternative ports 
        }
        $option['user']     = $state->user;             // User for database authentication
        $option['password'] = $state->password;         // Password for database authentication
        $option['database'] = $state->database;         // Database name
        $option['prefix']   = $state->prefix;           // Database prefix (may be empty)

        if (!$option['host'] || !$option['database'] || !$option['user'] || !$option['password'] || !$option['driver'] ) 
        {
            $this->setError( JText::_('PLEASE PROVIDE ALL REQUIRED INFORMATION') );
            return false;
        }

        $database = & JDatabase::getInstance( $option );
            
        // check that $newdatabase is_object and has method setQuery
        if (!is_object($database) || !method_exists($database, 'setQuery'))
        {
            $this->setError( JText::_('COULD NOT CREATE DATABASE INSTANCE') );
            return false;
        }
            
        $database->setQuery(" SELECT NOW(); ");
        if (!$result = $database->loadResult()) 
        {
            $this->setError( JText::_('COULD NOT PROPERLY QUERY THE DATABASE') );
            return false;
        }

        if (!empty($option['prefix']))
        {
            // Check that the prefix is valid by checking a table name on the target DB
            $tables = $database->getTableList();
            $table_name = $option['prefix'].$this->_tablename;
            
            if (!in_array($table_name, $tables))
            {
                $this->setError( JText::_('COULD NOT FIND APPROPRIATE TABLES USING PROVIDED PREFIX') );
                return false;
            }
        }
        
        return $database;
    }
   
}
