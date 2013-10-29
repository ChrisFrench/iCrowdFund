<?php 
/**
 * @version	0.1.0
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( dirname(__FILE__).DS.'params.php' );
jimport('joomla.application.component.model');

class AmbraTWLoginHelper extends AmbraTWLoginParams {
	
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
     * Returns FirmInfo.ID for given Serial and Registration numbers
     * 
     * @param array $credentials Serial and Registraton numbers
     * @param int $connection ODBC connection id or 0 (FALSE) on error
     * @return int $firminfo_id FirmInfo.ID
     */
    function getFirmInfoID( $credentials, $connection )
    {
    	$sqlstring = "SELECT ID FROM FirmInfo
    				  WHERE SerialNumber = '" . $credentials['serialnumber'] . "' 
    				  AND   RegistrationNumber = '" . $credentials['registrationnumber'] . "'";		    		  		
		$firminfo_id = odbc_result( odbc_exec( $connection, $sqlstring ), 1 );
			    		
		return $firminfo_id;
    }
    
    /**
     * Returns custom field id
     * 
     * @param void
     * @return int
     */
    function getCustomFieldId( $field_name )
    {
    	JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );   		
		$model = JModel::getInstance('Fields', 'AmbraModel');			    	
		$model->setState( 'select', 'tbl.field_id' );
		$model->setState( 'filter_name', $field_name );
		$field_id = $model->getResult();
				
		return 	$field_id;	
    }  
    
    /**
     *  Get paramter value
     *  
     *  @param int
     *  @param string
     *  @return unknown_type
     */
    function getParamValue( $userdata_id, $param_key )
    {
    	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
        $table = JTable::getInstance('Userdata', 'AmbraTable');	
		$table->load( $userdata_id );
		   	
    	$params = new JParameter( trim($table->user_params) );		
    	// Get parameter value
    	$param_value = $params->get( $param_key );
        	
    	return $param_value;
    }   

    /**
     * Get user id by parameter value
     *
     * @param unknow_type $param_value
     * @param string $param_key
     * @return object $user
     */
    function getUserByParam( $param_value, $param_key )
    {
    	$db =& JFactory::getDBO();    	
    	$query = "SELECT user_id FROM #__ambra_userdata 
    			  WHERE user_params LIKE '%" . $param_key . "=" . $param_value . "%'";
    	$db->setQuery( $query );
    	$user_id = $db->loadResult();
    	    	    	
    	$user = JUser::getInstance($user_id);
    	
    	return $user;
    }
}