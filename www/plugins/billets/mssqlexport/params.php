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

class plgBilletsMSSqlParams {
	var $params = '';
	
	/**
	 * plgBilletsMSSqlParams Constructor
     * Adds the params object as a member variable
     * so that it can be used throughout this class
     * and may also be used in plgBilletsMSSqlHelper.
     *
     * @param mixed $params
     * @return plgBilletsMSSqlParams
	 */
	function __construct(&$params)
    {
        $this->params =& $params;
    } 
    
	/**
    * Gets the ODBC host name set by the user
	* in plugin params
	*  
    * @return string
    */
    function getODBCDriver()
    {
    	return $this->params->get('odbcdriver');
    }    
    
    /**
    * Gets the MSSQLSrv host name set by the user
	* in plugin params
	*  
    * @return string
    */
    function getServer()
    {
    	return $this->params->get('server');
    }
    
	/**
    * Gets the database name set by the user
	* in plugin params
    *
    * @return string
    */
    function getDBName()
    {
    	return $this->params->get('database');
    }
    
    /**
    * Gets the Username set by the user
	* in plugin params
    *
    * @return string
    */
    function getUser()
    {
    	return $this->params->get('user'); 
    }
    
    /**
    * Gets the Username set by the user
	* in plugin params
    *
    * @return string
    */
    function getPass()
    {
    	return $this->params->get('password');
    }  
}