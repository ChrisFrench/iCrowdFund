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

if (!class_exists('Billets')) {
    JLoader::register("Billets", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_billets" . DS . "defines.php");
}

Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

class plgUserBilletsManagerFromJoomlaAcl extends BilletsPluginBase 
{
	
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$element = 'com_billets' ;
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		$this->loadLanguage( $element, JPATH_BASE );
	}

	/**
	 * Confirms the component is installed and adds helper files
	 * @return unknown_type
	 */
	function _isInstalled()
	{
		$success = false;
		
		jimport('joomla.filesystem.file');
		if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'defines.php')) 
		{
			$success = true;
		}
				
		return $success;
	}
	
	/**
	 *
	 * @access	public
	 * @param 	array 	holds the user data
	 * @param 	array    extra options
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function onLoginUser( $user, $options ) 
	{
		$success = null;
		if (!$this->_isInstalled()) {
			return $success;
		}
		
		// if any action were to be performed onLogin, you'd add a reference to it below these lines
		// and uncomment the next line to assign the userid to user['id'] 
		// (onLogin doesn't populate this field in the array)
		jimport( "joomla.user.helper" ); // joomla/user/helper.php (line 26) 
		$user['id'] = intval(JUserHelper::getUserId($user['username']));
		
		$action = $this->_executeAdd( $user['id'] );
		
		return $success;
	}
	
	/**
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onAfterStoreUser($user, $isnew, $succes, $msg) 
	{
		$success = null;
		if (!$this->_isInstalled()) {
			return $success;
		}
		
		if ($isnew)
		{
			$action = $this->_executeAdd( $user['id'] );	
		}
		
		return $success;
	}
	
	/**
	 * Execute Add
	 *
	 * @access public
	 * @param array holds the user data
	 * @param array holds the item 
	 * @return boolean True on success
	 * @since 1.5
	 */
	function _executeAdd( $userid ) 
	{		
		if (!$this->params->get( 'acl2cats', '0' )) 
		{ 
			return; 
		}
		
		if ($isExcluded = $this->_isExcluded( $userid ) )
		{
			return;
		}
		
		// param has association of Joomla ACL Ids = CSV of Billets categories, e.g.
		// 18=1,3,4,5,6
		// 25=1,2,3,4,5,6,7,8,9,10
		// Get full Param list
		$acl2cats = $this->params->get( 'acl2cats' );
		$acl2cats_params = new DSCParameter( $acl2cats );
		
		// based on user's gid
		$user = JFactory::getUser( $userid );
		$user_gid = $user->get( 'gid' );
		$user_acl2cats = explode( ',', $acl2cats_params->get( $user_gid ) );

		if (empty($user_acl2cats))
		{
			return;
		}
		
		JLoader::import( 'com_billets.helpers.category', JPATH_ADMINISTRATOR.DS.'components' );		
		JLoader::import( 'com_billets.helpers.manager', JPATH_ADMINISTRATOR.DS.'components' );
		// if there is a CSV of categories
		// foreach CSV cat
		for ($i=0; $i<count($user_acl2cats); $i++) 
		{
			$catid = $user_acl2cats[$i];			
			$isUser = BilletsHelperManager::isCategory( $userid, $catid );
			// if user isn't a cat manager
			// assign them as manager
			if (!$isUser)
			{
				$act = BilletsHelperCategory::addManager( $catid, $userid );
			}
		}
		
		return;
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	function _isExcluded( $id )
	{
		$success = false;
		
		$excluded_csv = $this->params->get( 'excluded_users' );
		$excluded_array = explode( ',', $excluded_csv );
		if (!is_array($excluded_array) || empty( $excluded_array))
		{
			return $success;
		}
		
		if ( in_array( $id, $excluded_array ) )
		{
			$success = true;
		}
		
		return $success;
	}
}
