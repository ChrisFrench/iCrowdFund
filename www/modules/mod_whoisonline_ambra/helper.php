<?php
/**
* @version		$Id: helper.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Ambra') ) 
    JLoader::register( "Ambra", JPATH_ADMINISTRATOR.DS."components".DS."com_ambra".DS."defines.php" );
                    
class modWhoisonlineAmbraHelper 
{	
	/**
	 * Get the online users count
	 * 
	 * @return number of users online
	 */
	function getOnlineCount() 
	{
	    $db		  =& JFactory::getDBO();
		$sessions = null;
		// calculate number of guests and members
		$result      = array();
		$user_array  = 0;
		$guest_array = 0;

		$query = 'SELECT guest, usertype, client_id' .
					' FROM #__session' .
					' WHERE client_id = 0';
		$db->setQuery($query);
		$sessions = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning( 500, $db->stderr() );
		}

		if (count($sessions)) {
		    foreach ($sessions as $session) {
			    // if guest increase guest count by 1
				if ($session->guest == 1 && !$session->usertype) {
				    $guest_array ++;
				}
				// if member increase member count by 1
				if ($session->guest == 0) {
					$user_array ++;
				}
			}
		}

		$result['user']  = $user_array;
		$result['guest'] = $guest_array;

		return $result;
	}

	
	/**
	 * show online member names
	 * 
	 * @return an array of usernames
	 */
	function getOnlineMembers() 
	{
	    $db		=& JFactory::getDBO();
		$result	= null;

		$query = ' SELECT DISTINCT a.username as username, a.userid as userid ' .
				 ' FROM #__session AS a' .
				 ' WHERE client_id = 0' .
				 ' AND a.guest = 0';
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		$return = array();
		
		if ($results) 
		{ 
			foreach ($results as $result) 
			{
                $user =& JFactory::getUser($result->userid);			
				$pic = Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $result->userid );
			
				$data = array();
				$data[0]= $user;
				$data[1] = $pic;
				$data['user']= $user;
				$data['pic'] = $pic;
				$return[] = $data;
			}
		}

		if ($db->getErrorNum()) {
			JError::raiseWarning( 500, $db->stderr() );
		}

		return $return;
	}

}
