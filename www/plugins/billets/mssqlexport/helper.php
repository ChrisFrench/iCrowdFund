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
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( dirname(__FILE__).DS.'params.php' );

class plgBilletsMSSqlHelper extends plgBilletsMSSqlParams {
	
	/**
    * plgBilletsMSSqlHelper Constructor
    * Adds a call to the parent plgBilletsMSSqlParams
    * object and passes the params object to it.
    *
    * Initializes other member variables.
    *
    * @param mixed $params
    * @return plgBilletsMSSqlHelper
    */
    function __construct( &$params )
    {
        parent::__construct( $params );
    }
    
    /**
     * Returns an ODBC connection id or 0 (FALSE) on error. 
     * 
     * @return int 
     */
    function DBconnect()
    {
    	$odbcstring  = "Driver=";
    	$odbcstring .= $this->getODBCDriver();
    	$odbcstring .= ";Server=";
    	$odbcstring .= $this->getServer();
    	$odbcstring .= ";Database=";
    	$odbcstring .= $this->getDBName();
    	$odbcstring .= ";";
    	
    	$connection = odbc_connect( $odbcstring, $this->getUser(), $this->getPass() );
    	
    	return $connection;
    }
    
    /**
     * Inserts data into Notes table
     * 
     * @param int
     * @param object
     * @return bool
     */    
    function insertNote( $connection, $data )
    {
    	// Check if ticket_params fieldv (column) exsists in __billets_tickets table 
    	$this->checkTicketParamsField();
    	
    	// Get user data from Ambra because we need CaseId    	 	
    	JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
    	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' ); 
    	JTable::getInstance('Users', 'AmbraTable');
        $model = JModel::getInstance('Users', 'AmbraModel');
        $model->setId( $data->sender_userid );
        $userdata = $model->getItem();
    	
        
        if( empty($userdata->casetablecaseid) )
        {
        	$app = JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_BILLETS_THERE_IS_NO_CASE_FOR_THIS_USER' ) . Billets::dump($data) , 'notice');
					
        	return false;
        }
        
    	    	    	
    	// completed - closed value
    	if( $data->stateid == 2 )
			$completed = 'True';
		else  
			$completed = 'False';			 
    	
		// insert record into Notes table
    	$sqlstring  = "INSERT INTO 
    						Notes ( 
    							CaseId, 
    							Date, 
    							Topic, 
    							Author, 
    							Notes, 
    							[User], 
    							Time, 
    							Problem, 
    							Completed 
    							) 
					   VALUES ( 
    	                      ".$userdata->casetablecaseid.",
    	                     '".$data->created_datetime."', 
    	                       'Support Ticket', 
    	                       'Billets', 
    	                     '".$data->title."', 
    	                       'Billets', 
    	                     '".$data->last_modified_datetime."', 
    	                     '".$data->description."', 
    	                     '".$completed."' 
    	                     )";
    	   	
    	$result = odbc_exec( $connection, $sqlstring );
    	
    	// If record is saved
    	if( $result )
    	{
    		$sqlstring = "SELECT IDENT_CURRENT('Notes');";
    		// get NotesID for inserted ticket    		  		
    		$notes_id = odbc_result( odbc_exec( $connection, $sqlstring ), 1 );
    		    		
			$this->insertParam( $data->id, 'NotesId', $notes_id );
    	}	
    	    	    	
    	return $result;    	
    }
    
	/**
     * Update note row in Notes with selected fields in $fields array
     * 
     * @param int
     * @param object
     * @return bool
     */    
    function updateNoteComments( $connection, $data )
    {		
    	// form a string from the comments object
    	$messages = BilletsHelperTicket::getMessages( $data->id );    	
    	$str = '';
    	foreach ( $messages as $message )
    	{
    		$str .= "User: ".$message->user_username." Message: ".$message->message."\n";
    	}
		
    	// update Notes row with new comments, last comment is first in the list
		$sqlstring  = "UPDATE Notes SET Solution = '";
		$sqlstring .= $str."' ";
		$sqlstring .= "WHERE NotesId = ".$this->getParamValue( $data->id, 'NotesId' );
    	    	
    	odbc_exec( $connection, $sqlstring );
    	    	
    	return odbc_errormsg( $connection );    	
    }
    
    /**
     * Update Completed status in Notes Table
     * 
     * @param int
     * @param object
     * @return bool
     */
    function updateNoteCompleted( $connection, $data )
    {
    	// completed - closed value
    	if( $data->stateid == 2 )
			$completed = 'True';
		else  
			$completed = 'False';		
    	
    	// update Notes row with new comments, last comment is first in the list
		$sqlstring  = "UPDATE Notes SET Completed = '".$completed;
		$sqlstring .= "' WHERE NotesId = ".$this->getParamValue( $data->id, 'NotesId' );
    	    	
    	odbc_exec( $connection, $sqlstring );
    	    	
    	return odbc_errormsg( $connection );    	
    }
    
    /**
     * Check if __billets_tickets table have ticket_params column, if not then create it. 
     *  
     * @return void
     */
    function checkTicketParamsField()
    {
    	$result = true;
    	$exists = false;
    	
    	$database = JFactory::getDBO();
    	$database->setQuery("SHOW COLUMNS FROM #__billets_tickets");    	
    	$columns = $database->loadResultArray();
    	foreach ( $columns as $key => $value ) {
    		if( $value == "ticket_params" ) {
    			$exists = true;
                break;
    		}
    	}
    	
        if( !$exists ) {
        	$database->setQuery("ALTER TABLE #__billets_tickets ADD ticket_params TEXT");
        	$result = $database->query();
        }
          	
        if( !$result )
        {
        	$app = JFactory::getApplication();
			$app->enqueueMessage( JText::_('COM_BILLETS_MYSQL_ERROR'.$result->getErrorMsg() ), 'notice');
        }
        
        return;
    }     

    /**
     *  Insert paramter in ticket_params field
     *  
     *  @param int
     *  @param string
     *  @param unknown_type
     *  @return bool
     */
    function insertParam( $ticket_id, $param_key, $param_value )
    {
    	// Get database
    	$database = JFactory::getDBO();
    	// Get values from ticket_params field into $params variable
    	$query = "SELECT ticket_params FROM #__billets_tickets 
    	          WHERE id = ".$ticket_id;    	
    	$database->setQuery( $query );    	
    	$params = new DSCParameter( trim($database->loadResult()) ); 
		// Set parameter value
    	$params->set( $param_key, $param_value );
		
		// Insert values into the ticket_params field
		$query = "UPDATE #__billets_tickets 
    	          SET ticket_params = '".trim( $params->toString() )."' WHERE id = ".$ticket_id;  	
    	$database->setQuery( $query );
		$database->query();
    }
    
    /**
     *  Get paramter value
     *  
     *  @param string
     *  @param unknown_type
     *  @return bool
     */
    function getParamValue( $ticket_id, $param_key )
    {
    	// Get database
    	$database = JFactory::getDBO();
    	
    	// Get values from ticket_params field into $params variable
    	$query = "SELECT ticket_params FROM #__billets_tickets 
    	          WHERE id = ".$ticket_id;    	
    	$database->setQuery( $query );    	
    	$params = new DSCParameter( trim($database->loadResult()) ); 
		
    	// Get parameter value
    	$param_value = $params->get( $param_key );
    	//$param_value = str_replace( $param_key.'=', '', $param_value);
        	
    	return $param_value;
    }
}