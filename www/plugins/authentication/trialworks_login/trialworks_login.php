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
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

/** Import MSSQL db layer */
require_once (dirname(__FILE__).DS.'trialworks_login'.DS.'helper.php');

class plgAuthenticationTrialworks_Login extends JPlugin {
		
	/**
	 * Constructor
	 */
	function plgAuthenticationTrialworks_Login( &$subject, $config )  
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		
		// load plugin parameters
        $this->_plugin = JPluginHelper::getPlugin( 'authentication', 'trialworks_login' );
        $this->_params = new JParameter( $this->_plugin->params );	
				 
		$this->helper = new AmbraTWLoginHelper( $this->_params );			
	}
		
	/**
     * This method should handle any authentication and report back to the subject
     *
     * @access  public
     * @param   array   $credentials    Array holding the user credentials
     * @param   array   $options        Array of extra options
     * @param   object  $response       Authentication response object
     * @return  boolean
     * @since   1.5
     */
    function onAuthenticate( $credentials, $options, &$response )
    {	   	
        jimport('joomla.user.helper');
        		        
        if (empty($credentials['serialnumber']))
        {
            $response->status = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Empty serial number not allowed';
            return false;
        }
        
    	if (empty($credentials['registrationnumber']))
        {
            $response->status = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Empty registration number not allowed';
            return false;
        }
        
        // connect to the MS SQL Srv database
		$dbconn = $this->helper->DBconnect();
		
		// test if connection fails
		if( !$dbconn )	
		{		
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'SQL Server: Connecting to the database failed. SQL Error: '.odbc_errormsg( $dbconn );
			return false;
		}
		else 
		{
			$firminfo_id = $this->helper->getFirmInfoID( $credentials, $dbconn );
			
			if( empty($firminfo_id) )
			{
				$response->status = JAUTHENTICATE_STATUS_FAILURE;
	            $response->error_message = 'SQL Server: There is no matching Serial Number and Registration Number. FirmInfo.ID not returned.';
				return false;
			}
			else 
			{
				$user = $this->helper->getUserByParam( $firminfo_id, 'FirmInfoID' );
				
				if( empty($user) )
				{
					$response->status = JAUTHENTICATE_STATUS_FAILURE;
		            $response->error_message = "User doesn't exist.";
					return false;
				}
				else 
				{
					$response->username = $user->username;
	                $response->email = $user->email;
	                $response->fullname = $user->name;
	                $response->status = JAUTHENTICATE_STATUS_SUCCESS;
	                $response->error_message = '';
				}
			}
		}
    }
}