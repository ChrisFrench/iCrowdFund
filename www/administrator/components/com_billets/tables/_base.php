<?php
/**
 * @version	0.1
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
Billets::load( 'BilletsQuery', 'library.query' );

class BilletsTable extends DSCTable
{
	/**
    * Flags a table's changes to be logged
    * and defines the table that stores the logs
    * if it hasn't already been
    * 
    * @param bool To log or not to log.
    * @return void
    */
    function setLogged( $log=true ) 
    {

        $this->_isLogged = $log;

        if ( empty( $this->_logTable ) )
        {
        	$this->setLogTable();
        }
    }
    
    /**
     * Defines the JTable object that does the logging and the field name map
     * 
     * @param void
     * @return bool true
     * 
     */
    function setLogTable()
    {
    	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance('Logs', 'BilletsTable');
		
   		if ( empty( $table ) )
        {
        	// set error and return null because this table isn't supposed to be logged
            $this->setError( JText::_('COM_BILLETS_JTABLE_CLASS_FOR_LOGS_DOESNT_EXIST') );
            return false;
        }
        
		$this->_logTable = $table;		
		return true;
    }
    
    /**
     * Returns the JTable object that does the logging and the field name map
     * 
     * @param void
     * @return JTable object log table 
     */    
    function getLogTable()
    {
    	return $this->_logTable;
    }
    
	/**
    * Logs a table's changes in the defined log table
    * if there is one
    * 
    * @param object $original record before change
    * 
    */
    function logChanges( $original, $properties, $object_id = 0, $object_type = 'default', $log_description = 'default' ) 
    {   
        if ( !$this->_isLogged )
        {
        	// set error and return null because this table isn't supposed to be logged
            $this->setError( JText::_('COM_BILLETS_THIS_TABLE_IS_NOT_LOGGED') );
            return false;
        }

        if ( empty( $this->_logTable ) )
        {
            // set error and return null because the log table isn't defined
            $this->setError( JText::_('COM_BILLETS_THE_LOG_TABLE_IS_NOT_DEFINED') );
            return false;
        }

        // log the changes by using the field map, loop through the properties of row 
        // and compare them to the same property in $original, keeping track of the changes 
        // in an array of objects, a new property in the JTable object:
        $changes = array();
       	foreach( $original as $key=>$value)
       	{
       		if( array_key_exists( $key, $this ) && in_array( $key, $properties ) )
       		{
       			if( $value != $this->$key )
       			{ 
       				$change->user_id = & JFactory::getUser()->id;
       				$change->object_id = $object_id;
       				$change->object_type = $object_type;
       				$change->property_name = $key;
       				$change->value_from = $value;
       				$change->value_to = $this->$key;
       				$change->log_description = $log_description;
       				
       				$changes[] = $change;
       				unset( $change );
       			}
       		}
       	}
    
       	if( !empty($changes) )
       	{
       		foreach ( $changes as $change )
       		{    
	       		$this->_logTable->bind( $change );
	       		$log = clone $this->_logTable;
	       		if( !$log->save() )
	       		{
		            $this->setError( JText::_('COM_BILLETS_LOG_SAVE_ERROR') );
		            return false;
	       		}
       		}
       	}
       	
       	return true;
    }
}