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

/** Import library dependencies */
jimport('joomla.plugin.plugin');

/** Import MSSQL db layer */
require_once (dirname(__FILE__).DS.'mssqlexport'.DS.'helper.php');


class plgBilletsMSSqlExport extends JPlugin {
		
	/**
	 * Constructor
	 */
	function plgBilletsMSSqlExport(& $subject, $config)  
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		
		// load plugin parameters
        $this->_plugin = JPluginHelper::getPlugin( 'billets', 'mssqlexport' );
        $this->_params = new DSCParameter( $this->_plugin->params );		
	}
	
	/**
	 * Method is called
	 * after a new message is saved
	 *
	 * @return
	 * @param $data Object
	 */
	function onAfterSaveTickets( $data )
	{		
		if ( !empty( $data->_isNew ) )
		{
			// create helper object
			$mssql = new plgBilletsMSSqlHelper( $this->_params );			
			
			// connect to the database
			$dbconn = $mssql->DBconnect( );
			
			// test if connection fails
			if( !$dbconn )	
			{		
				$app = JFactory::getApplication();
				$app->enqueueMessage( JText::_('SQL Server: Connecting to the database failed. SQL Error: '.odbc_errormsg( $dbconn ) ), 'notice');
				return;
			}
			else 
			{
				// insert new row in Notes
				$success = $mssql->insertNote( $dbconn, $data );
				// test if query fails
				if ( !$success ) {
					$app = JFactory::getApplication();
					$app->enqueueMessage( JText::_('SQL Server: Inserting row failed. SQL Error: '.odbc_errormsg( $dbconn ) ), 'notice' );
					return;		
				} 
				else {										
					$app = JFactory::getApplication();
					$app->enqueueMessage( JText::_( 'COM_BILLETS_SQL_SERVER_NEW_DATA_SAVED') );
					return;	
				}	
			}		
		}	
	}

	/**
	 * Method is called
	 * after a new comment is added
	 *
	 * @return
	 * @param $data Object
	 */
	function onAfterSaveComment( $data )
	{
		// create helper object
		$mssql = new plgBilletsMSSqlHelper( $this->_params );			
			
		// connect to the database
		$dbconn = $mssql->DBconnect( );
			
		// test if connection fails
		if( !$dbconn )	
		{		
			$success = odbc_errormsg( $dbconn );
			$app = JFactory::getApplication();
			$app->enqueueMessage( JText::_('SQL Server: Connecting to the database failed. SQL Error: '.$success ), 'notice');
			return;
		}
		else 
		{
			// update note row with comments
			$success = $mssql->updateNoteComments( $dbconn, $data );
			// test if query fails
			if ( $success == '' ) {
				$app = JFactory::getApplication();
				$app->enqueueMessage( JText::_('COM_BILLETS_SQL_SERVER_COMMENTS_UPDATED' ) );
				return;
			} 
			else {
				$app = JFactory::getApplication();
				$app->enqueueMessage( JText::_( 'SQL Server: Inserting row failed. SQL Error: '.$success ), 'notice');
				return;			
			}	
		}
	}
	
	/**
	 * Method is called
	 * after a ticket status is changed
	 *
	 * @return
	 * @param $data Object
	 */
	function onAfterChangeStatus( $data )
	{
		// create helper object
		$mssql = new plgBilletsMSSqlHelper( $this->_params );			
			
		// connect to the database
		$dbconn = $mssql->DBconnect( );
			
		// test if connection fails
		if( !$dbconn )	
		{		
			$success = odbc_errormsg( $dbconn );
			$app = JFactory::getApplication();
			$app->enqueueMessage( JText::_('SQL Server: Connecting to the database failed. SQL Error: '.$success ), 'notice');
			return;
		}
		else 
		{
			// update note row with Completed status (column Completed in Notes table)
			$success = $mssql->updateNoteCompleted( $dbconn, $data );
			// test if query fails
			if ( $success == '' ) {
				$app = JFactory::getApplication();
				$app->enqueueMessage( JText::_('COM_BILLETS_SQL_SERVER_TICKET_STATUS_UPDATED' ) );
				return;
			} 
			else {
				$app = JFactory::getApplication();
				$app->enqueueMessage( JText::_( 'SQL Server: Change status failed. SQL Error: '.$success ), 'notice');
				return;			
			}	
		}
	}
}
