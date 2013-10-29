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
Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

/**
 * Billets Plugin
 *
 */
class plgBilletsAvatarfb extends BilletsPluginBase {

	
	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}

	/**
	 * Method is called 
	 * before displaying a comment.
	 * Note: $comment->authorimage is available, and is already set to the default
	 * 
	 * @return 
	 * @param $row Object
	 * @param $body Object
	 * @param $user Object
	 * @param $args Object
	 */
	function onBeforeDisplayCommentAuthorImage( $comment, $data, $user ) 
	{
		$success = true;
		
		if(!$this->_isInstalled()) return $success;
				
	    if (empty($comment->userid_from))
        {
            return $success;
        }
        
		if ($avatar = plgBilletsAvatarfb::getUserAvatar( $comment->userid_from )) {
			$file = JPATH_SITE.DS.'images'.DS.'fbfiles'.DS.'avatars'.DS.$avatar;
			if (file_exists( $file )) {
				$comment->authorimage = "<img src='".JURI::root()."/images/fbfiles/avatars/".$avatar."' style='max-width:48px;'>";
			}
		}
		
		return $success;
	}
	
	/**
	 * Check if FireBoard is installed
	 * 
	 * @return unknown_type
	 */
	function _isInstalled()
	{
		$db = JFactory::getDBO();
		
		// Manually replace the Joomla Tables prefix. Automatically it fails
		// because the table name is between single-quotes
		$db->setQuery(str_replace('#__', $db->getPrefix(), "SHOW TABLES LIKE '#__fb_users'"));
		$result = $db->loadObject();
		
		if($result === null) return false;
		else return true;
	}
	
	/**
	 * Returns a user's Avatar
	 * from CB
	 * 
	 * @return 
	 * @param $data Object
	 */
	function &getUserAvatar( $id ) {
		$database = JFactory::getDBO();
		$success = false;
		
		$query = " 
			SELECT 
				*
			FROM
				#__fb_users
			WHERE
				`userid` = '".$id."'
			";
		$database->setQuery( $query );
		$result = $database->loadObject();
		
		if (isset($result->avatar)) {
			$success = $result->avatar;
		}

		return $success;
	}

}
