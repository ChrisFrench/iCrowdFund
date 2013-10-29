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
Billets::load('BilletsPluginBase', 'library.plugin.base');

/**
 * Billets Plugin
 *
 */
class plgBilletsAvatarJomSocial extends BilletsPluginBase {

	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this -> loadLanguage('', JPATH_ADMINISTRATOR);
	}

	/**
	 * Check if is installed
	 *
	 * @return unknown_type
	 */
	function _isInstalled() {
		$db = JFactory::getDBO();

		if (version_compare(JVERSION, '1.6.0', 'ge')) {
			// Joomla! 1.6+ code here
			$app = JFactory::getApplication();
			$prefix = $app -> getCfg('dbprefix');
		} else {
			// Joomla! 1.5 code here
			$prefix = $db -> _table_prefix;
		}
		// Manually replace the Joomla Tables prefix. Automatically it fails
		// because the table name is between single-quotes
		$db -> setQuery(str_replace('#__', $prefix, "SHOW TABLES LIKE '#__community_users'"));
		$result = $db -> loadObject();

		if ($result === null)
			return false;
		else
			return true;
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
	function onBeforeDisplayCommentAuthorImage($comment, $data, $user) {
		$success = true;

		if (!$this -> _isInstalled()) {
			return $success;
		}

		if ($avatar = plgBilletsAvatarJomSocial::getUserAvatar($comment -> userid_from)) {
			$url = @getimagesize(JURI::root() . $avatar);
			$imgLink = is_array($url) ? JURI::root() . $avatar : JURI::root() . "/components/com_community/assets/default_thumb.jpg";
		} else {
			$imgLink = JURI::root() . "/components/com_community/assets/default_thumb.jpg";
		}

		$comment -> authorimage = "<img src='{$imgLink}' style='max-width:48px;'>";

		return $success;
	}

	/**
	 * Returns a user's Avatar
	 * from JomSocial
	 *
	 * @return
	 * @param $data Object
	 */
	function & getUserAvatar($id) {
		$database = JFactory::getDBO();
		$success = false;

		$query = " 
			SELECT 
				*
			FROM
				#__community_users
			WHERE
				`userid` = '" . $id . "'
			";
		$database -> setQuery($query);
		$result = $database -> loadObject();

		if (isset($result -> thumb)) {
			$success = $result -> thumb;
		}

		return $success;
	}

}
