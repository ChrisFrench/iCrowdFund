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

Billets::load('BilletsHelperBase','helpers._base' );
jimport('joomla.application.component.model');

class BilletsHelperDiagnostics extends BilletsHelperBase
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
        // Check DB Table
        if (!$this->fixTables()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_FIXTABLES_FAILED') .' :: '. $this->getError(), 'error');
        }
        
        // Check DB Table
        if (!$this->checkFieldsTable()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTABLEFIELDS_FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkTicketStates()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTICKETSTATES_FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->rebuildCategoryTreeOnUpgrade()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_REBUILDCATEGORYTREEONUPGRADE_FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->fixTicketStatuses()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_FIXTICKETSTATUSES_FAILED') .' :: '. $this->getError(), 'error' );
        }
        
        if (!$this->checkTicketsCheckedOut()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTICKETSCHECKEDOUT_FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->checkTicketsTiendaOrder()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTICKETSTIENDAORDER_FAILED') .' :: '. $this->getError(), 'error' );
        }
        
    	if (!$this->checkTicketsHours()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTICKETSHOURS_FAILED') .' :: '. $this->getError(), 'error' );
        }
        
    	if (!$this->checkTicketsHoursSpent()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKTICKETSHOURSSPENT_FAILED') .' :: '. $this->getError(), 'error' );
        }

        if (!$this->checkFilesMessageId()) 
        {
            return $this->redirect( JText::_('COM_BILLETS_DIAGNOSTIC_CHECKFILESMESSAGEID_FAILED') .' :: '. $this->getError(), 'error');
        }
        
        
        // Never fails, because some fields won't exist if this is a clean v4 installation.
        // Those are inherited from v1.5.3 or older DB schemma
    	$this->mysqlStripSlashes();
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
     * 
     * @return unknown_type
     */
    function checkFieldsTable()
    {
        // if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkFieldsTable', '0'))
        {
            return true;
        }
        
        $errors = array();
        
        // _billets_fields (the TINYINT on checked_out => INT)
        $database = JFactory::getDBO();
        $query = " SHOW COLUMNS FROM #__billets_fields LIKE 'checked_out' ";
        $database->setQuery( $query );
        $row = $database->loadObject();
        $type = strtoupper( $row->Type );

        if ($type != 'INT(11)') 
        {       
            $query = "ALTER TABLE `#__billets_fields` MODIFY `checked_out` INT(11) NOT NULL; ";
            $database->setQuery( $query );
            if (!$database->query())
            {
                $errors[] = $database->getErrorMsg();
            }
        }
        
        if (empty($errors))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( 'checkFieldsTable');
            $config->title = 'checkFieldsTable';
            $config->value = '1';
            $config->save();
            return true;
        }
        
        $this->setError( implode('<br/>', $errors) );
        return false;
    }
    
	/**
	 * Performs DB table upgrades
	 * 
	 * @return boolean
	 */
	function fixTables()
	{
		if (Billets::getInstance()->get('fixtables', '0'))
		{
			return true;
		}
		
		$database = JFactory::getDBO();
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_frequents';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'parentid' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{		
			$query = "ALTER TABLE `".$table."` ADD `parentid` INT( 11 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_frequents';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'created_datetime' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{		
			$query = "ALTER TABLE `".$table."` ADD `created_datetime` DATETIME; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_frequents';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'published' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if ($rows && !$database->getErrorNum()) 
		{		
			$query = " ALTER TABLE `".$table."` CHANGE `published` `enabled` TINYINT( 1 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_categories';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'published' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if ($rows && !$database->getErrorNum()) 
		{		
			$query = " ALTER TABLE `".$table."` CHANGE `published` `enabled` TINYINT( 1 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_categories';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'lft' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum())  
		{		
			$query = "ALTER TABLE `".$table."` ADD `lft` INT( 11 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_categories';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'rgt' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum())  
		{		
			$query = "ALTER TABLE `".$table."` ADD `rgt` INT( 11 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_categories';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'isroot' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum())  
		{		
			$query = "ALTER TABLE `".$table."` ADD `isroot` TINYINT( 1 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check that a root row is defined with id = 0, parentid = -1
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance('Categories', 'BilletsTable');
		$table->rebuildTree();
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_fields';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'listdisplayed' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{		
			$query = "ALTER TABLE `".$table."` ADD `listdisplayed` TINYINT( 1 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_ticketdata';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'ticketdata_id' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{
			$query = " ALTER TABLE `".$table."` CHANGE `id` `ticketdata_id` INT( 11 ) NOT NULL AUTO_INCREMENT ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_u2c';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'emails' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{		
			$query = "ALTER TABLE `".$table."` ADD `emails` TINYINT( 1 ) NOT NULL DEFAULT '1'; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		// TODO Check config to see if this has been done already
		$table = '#__billets_tickets';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'status' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if ($rows && !$database->getErrorNum()) 
		{		
			$query = " ALTER TABLE `".$table."` CHANGE `status` `stateid` INT( 11 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
			// TODO Check config to see if this has been done already
		$table = '#__billets_tickets';
		
		// add the scope field
		$query = " SHOW COLUMNS FROM ".$table. "\n LIKE 'labelid' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if (!$rows && !$database->getErrorNum()) 
		{		
			$query = "ALTER TABLE `".$table."` ADD `labelid` TINYINT( 1 ) NOT NULL; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				// TODO Update Config to say this has been done already
			}
		}
		
		$query = "UPDATE #__billets_tickets AS tbl SET tbl.title = tbl.subject WHERE tbl.title IS NULL OR tbl.title = '' ";
		$database->setQuery( $query );
		if ($database->query())
		{
            //TODO Update Config to say this has been done already
		}

		// update config to say this has been done
			JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $table = JTable::getInstance( 'Config', 'BilletsTable' );
            $table->load( 'fixtables' );
            $table->title = 'fixtables';
            $table->value = '1';
            $table->save();
            
        return true;
	}
	
    /**
     * Checks that at least one ticket state is created
     * and if not, creates default ticket states
     * 
     * @return boolean
     */
    function checkTicketStates()
    {
        if (Billets::getInstance()->get('checkTicketStates', '0'))
        {
            return true;
        }
        
        $errors = array();
        
        // check if the _ticketstates table has any rows
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $model = JModel::getInstance( 'Ticketstates', 'BilletsModel' );
        $items = $model->getList();
        // if it has no rows, create the following:
        if (empty($items))
        {
            //- Open Tickets (id 1)
            $table = $model->getTable();
            $table->title = 'Open Tickets';
            $table->enabled = 1;
            if (!$table->save())
            {
                $errors[] = $table->getError();
            }
                else
            {
                $open = $table;
            }
            
            //- Closed Tickets (id 2)
            $table = $model->getTable();
            $table->title = 'COM_BILLETS_CLOSED_TICKETS';
            $table->enabled = 1;
            $table->img = 'closed.png';
            if (!$table->save())
            {
                $errors[] = $table->getError();
            }
            
            if (!empty($open))
            {
                // Requires Feedback (id 3, is a child of Open Tickets)
                $table = $model->getTable();
                $table->title = 'Requires Feedback';
                $table->enabled = 1;
                $table->img = 'requiresfeedback.png';
                $table->parentid = $open->id; 
                if (!$table->save())
                {
                    $errors[] = $table->getError();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }

        // update config to say this has been done
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
        $table->load( 'checkTicketStates' );
        $table->title = 'checkTicketStates';
        $table->value = '1';
        $table->save();
        return true;    
    }
    
    /**
     * We switched from parent_id to lft-rgt, 
     * so the category tree should be rebuilt on upgrade
     * 
     * @return boolean
     */
    function rebuildCategoryTreeOnUpgrade()
    {
        if (Billets::getInstance()->get('rebuildCategoryTreeOnUpgrade', '0'))
        {
            return true;
        }
        
        $errors = array();
        
        // check if the _ticketstates table has any rows
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        JModel::getInstance('Categories', 'BilletsModel')->getTable()->updateParents();
        JModel::getInstance('Categories', 'BilletsModel')->getTable()->rebuildTree();
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }

        // update config to say this has been done
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
        $table->load( 'rebuildCategoryTreeOnUpgrade' );
        $table->title = 'rebuildCategoryTreeOnUpgrade';
        $table->value = '1';
        $table->save();
        return true;    
    }
    
    /**
     * We switched to using a db table to store ticket statuses 
     * so now all tickets with status=0 must be changed to status=1
     * only on upgrade
     * 
     * @return boolean
     */
    function fixTicketStatuses()
    {
        if (Billets::getInstance()->get('fixTicketStatuses', '0'))
        {
            return true;
        }
        
        $errors = array();
        
        $database = JFactory::getDBO();
        
        $query = "
            UPDATE 
                #__billets_tickets AS tbl 
            SET 
                tbl.stateid = '1' 
            WHERE 
                tbl.stateid IS NULL 
                OR tbl.stateid = ''
                OR tbl.stateid = '0'
        ";
        
        $database->setQuery( $query );
        if (!$database->query())
        {
            $errors[] = $database->getErrorMsg(); 
        }
                
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }

        // update config to say this has been done
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
        $table->load( 'fixTicketStatuses' );
        $table->title = 'fixTicketStatuses';
        $table->value = '1';
        $table->save();
        return true;    
    }
    
	/*
	 * Check-in a ticket which is checked-out for more than a specified amount of time
	 * in the configuration. (To prevent tickets checked-out by a manager and then forgotten)
	 */
	public static function tryCheckInTicket($ticket_id)
	{
		$db = JFactory::getDBO();
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
		
		$table->load( 'maxCheckOutTime' );
		if (!empty($table->id) && !empty($table->value))
		{
            $query = "UPDATE `#__billets_tickets` SET ".
                    "`checked_out` = '0' , ".
                    "`checked_out_time` = '0000-00-00 00:00:00' ".
                    " WHERE `id` = '".intval($ticket_id)."' ".
                    " AND `checked_out` != '0' ".
                    " AND `checked_out_time` < '".gmdate('Y-m-d H:i:s', time()-intval($table->value))."'"
            ;
            
            $db->setQuery($query);
            if (!$db->query())
            {
                JFactory::getApplication()->enqueueMessage( JText::_('COM_BILLETS_TRYCHECKINTICKET_FAILED').": ".$db->getErrorMsg() );
            }		    
		}
	}
    
    
	/*
	 * Check-in tickets which are checked-out for more than a specified amount of time
	 * in the configuration. (To prevent tickets checked-out by a manager and then forgotten)
	 */
	public static function tryCheckInAllTickets()
	{
		$db = JFactory::getDBO();
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Config', 'BilletsTable' );
		
		$table->load( 'maxCheckOutTime' );
		
		// Find old checked-out tickets and check-in them
		$query = "UPDATE `#__billets_tickets` SET".
				"`checked_out`= '0', ".
				"`checked_out_time` = '0000-00-00 00:00:00'".
				" WHERE `checked_out` != '0' AND ".
				"`checked_out_time` < '".gmdate('Y-m-d H:i:s', time()-intval($table->value))."'"
		;
		
		$db->setQuery($query);
		$db->query();
	}
	
	
	/**
	 * Executes an equivalent of stripslashes() using MySQL code in tables' columns
	 * 
	 * @param array $tf Array of objects with members table & field value
	 */
	function mysqlStripSlashes()
	{
		if (Billets::getInstance()->get('mysqlStripSlashes', '0'))
		{
			return true;
		}
		
		$db = JFactory::getDBO();
		
        $tf = array(); // Tables / Fields array of objects
        $tf[] = (object) array('table'=>'#__billets_tickets', 'field'=>'title');
        $tf[] = (object) array('table'=>'#__billets_tickets', 'field'=>'subject');
        $tf[] = (object) array('table'=>'#__billets_tickets', 'field'=>'description');
        $tf[] = (object) array('table'=>'#__billets_messages', 'field'=>'subject');
        $tf[] = (object) array('table'=>'#__billets_messages', 'field'=>'message');
        $tf[] = (object) array('table'=>'#__billets_comments', 'field'=>'message');
				
		foreach ($tf as $obj)
		{
		    $query = " SHOW COLUMNS FROM {$obj->table} LIKE '{$obj->field}' ";
            $db->setQuery( $query );
            $rows = $db->loadObjectList();
            if (!$rows || $db->getErrorNum()) 
            {
                // if we're here, the field doesn't exist in the table, so skip it
                continue;
            }
		    
			$table = $db->getEscaped($obj->table);
			$field = $db->getEscaped($obj->field);
			
			// Replaces:	\\ => \
			//				\' => '
			//				\" => "\
			
			$query = "UPDATE `$table` SET ".
					"`$field`=REPLACE(REPLACE(REPLACE(`$field`, '\\\\\\\\', '\\\\'), '\\\\\'', '\''), '\\\\\"', '\"')";
			
			$db->setQuery($query);
			if (!$db->query())
			{
				// never fail in case table/fields are missing.
				// NOTE: take special care the above very tricky query is correct!!!
				continue;
			}
		}
		
		// update config to say this has been done
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
        $table->load( 'mysqlStripSlashes' );
        $table->title = 'mysqlStripSlashes';
        $table->value = '1';
        $table->save();
		
		return true;
	}
	
    /**
     * 
     * @return unknown_type
     */
    function checkTicketsCheckedOut()
    {
        // if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkTicketsCheckedOut', '0'))
        {
            return true;
        }
        
        $errors = array();
        
        // _billets_fields (the TINYINT on checked_out => INT)
        $database = JFactory::getDBO();
        $query = " SHOW COLUMNS FROM #__billets_tickets LIKE 'checked_out' ";
        $database->setQuery( $query );
        $row = $database->loadObject();
        $type = strtoupper( $row->Type );

        if ($type != 'INT(11)') 
        {       
            $query = "ALTER TABLE `#__billets_tickets` MODIFY `checked_out` INT(11) NOT NULL; ";
            $database->setQuery( $query );
            if (!$database->query())
            {
                $errors[] = $database->getErrorMsg();
            }
        }
        
        if (empty($errors))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( 'checkTicketsCheckedOut');
            $config->title = 'checkTicketsCheckedOut';
            $config->value = '1';
            $config->save();
            return true;
        }
        
        $this->setError( implode('<br/>', $errors) );
        return false;
    }
    
/**
     * Checks the products table to confirm it has the params and layout fields
     * 
     * return boolean
     */
    function checkTicketsTiendaOrder()
    {
        // if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkTicketsTiendaOrder', '0'))
        {
            return true;
        }
        
        $table = '#__billets_tickets';
        $definitions = array();
        $fields = array();
        
        $fields[] = "tienda_orderid";
            $definitions["tienda_orderid"] = "int(11) DEFAULT '0' COMMENT 'Tienda Order ID this Ticket Refers to'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( array( 'title'=>'checkTicketsTiendaOrder') );
            $config->title = 'checkTicketsTiendaOrder';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;        
    }
    
    /**
     * Check the userdata table for hour_max and hour_count fields
     * Needed for per_hour tickets
     */
    function checkTicketsHours()
    {
    	// if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkTicketsHours', '0'))
        {
            return true;
        }
        
        $table = '#__billets_userdata';
        $definitions = array();
        $fields = array();
        
        $fields[] = "hour_max";
            $definitions["hour_max"] = "int(11) DEFAULT '0' COMMENT 'Max number of hours of support user has'";
        $fields[] = "hour_count";
            $definitions["hour_count"] = "int(11) DEFAULT '0' COMMENT 'Number of hours of support the user has'"; 
        $fields[] = "limit_hours";
            $definitions["limit_hours"] = "tinyint(1) NOT NULL COMMENT 'Can this user only open a limited number of hours?'";
        $fields[] = "limit_hours_exclusion";
            $definitions["limit_hours_exclusion"] = "tinyint(1) NOT NULL COMMENT 'Is this user excluded from the global hour limiting?'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( array( 'title'=>'checkTicketsHours') );
            $config->title = 'checkTicketsHours';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;
    }
    
	/**
     * Check the tickets table for hours_spent field
     * Needed for per_hour tickets
     */
    function checkTicketsHoursSpent()
    {
    	// if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkTicketsHoursSpent', '0'))
        {
            return true;
        }
        
        $table = '#__billets_tickets';
        $definitions = array();
        $fields = array();
        
        $fields[] = "hours_spent";
            $definitions["hours_spent"] = "int(11) DEFAULT '0'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( array( 'title'=>'checkTicketsHoursSpent') );
            $config->title = 'checkTicketsHoursSpent';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;
    }
    
    /**
     * Check the files table for message_id field
     */
    function checkFilesMessageId()
    {
        // if this has already been done, don't repeat
        if (Billets::getInstance()->get('checkFilesMessageId', '0'))
        {
            return true;
        }
        
        $table = '#__billets_files';
        $definitions = array();
        $fields = array();
        
        $fields[] = "message_id";
            $definitions["message_id"] = "int(11) DEFAULT '0'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'BilletsTable' );
            $config->load( array( 'title'=>'checkFilesMessageId') );
            $config->title = 'checkFilesMessageId';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;
    }
}