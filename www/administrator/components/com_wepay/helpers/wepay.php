<?php
/**
 * @version 1.5
 * @package Wepay
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if (!class_exists('Wepay')) {
	JLoader::register("Wepay", JPATH_ADMINISTRATOR . "/components/com_wepay/defines.php");
}

class WepayHelperWepay extends JObject {

	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu($vName = 'wepay') {
		JHtmlSidebar::addEntry(JText::_('Dashboard'), 'index.php?option=com_wepay&view=dashboard', $vName == 'favorites');
		JHtmlSidebar::addEntry(JText::_('Accounts'), 'index.php?option=com_wepay&view=accounts', $vName == 'favorites');
		JHtmlSidebar::addEntry(JText::_('Config'), 'index.php?option=com_wepay&view=config', $vName == 'favorites');

	}

	public static function getUser($uid = NULL) {
		if (empty($uid)) {
			$uid = JFactory::getUser() -> id;
		}
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_wepay/tables');
		JModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_wepay/models');
		$model = JModel::getInstance('Users', 'WepayModel');
		$model -> setState('filter_userid', $uid);
		$list = $model -> getList(true);
		if ($list)
			return $list[0];

		return array();
	}

	public static function canCreate($uid = NULL) {
		if (empty($uid)) {
			$uid = JFactory::getUser() -> id;
		}
		$user = WepayHelperWepay::getUser($uid);
		if ($user -> wepay_access_token) {
			return $user;
		} else {
			$msg = '';
			$link = JRoute::_('index.php?option=com_wepay&view=dashboard&Itemid=' . Wepay::getInstance() -> get('dashboard_itemid', ''), true, -1);
			$app = JFactory::getApplication();
			$app -> redirect($link, $msg);
		}
	}

	public static function createAccount($wePayUser, $name, $desc = '', $scope = 0) {

		$wepay = new WePayLib($wePayUser -> wepay_access_token);
		// create an account for this project
		$account = $wepay -> request('account/create/', array('name' => $name, 'description' => $desc));

		if ($account -> account_id) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_wepay/tables');
			$table = JTable::getInstance('Accounts', 'WepayTable');
			$table -> joomla_user_id = $wePayUser -> joomla_user_id;
			$table -> scope_id = $scope;
			$table -> wepay_account_id = $account -> account_id;
			$table -> wepay_account_uri = $account -> account_uri;
			$table -> wepay_name = $name;
			$table -> wepay_description = $desc;
			$table -> enabled = 1;
			$now = JFactory::getDate();
			$table -> datecreated = $now -> toMySQL();
			;
			if (!$table -> store()) {
				JError::raiseError(500, $table -> getError());
			} else {
				return $table -> id;
			}

		}
	}

	public static function getObjectFromAccountID($account_id) {

		$db = JFactory::getDBO();
        $query = NEW DSCQuery();
        $query->select('u.wepay_userid,
	u.wepay_access_token AS access_token,
	a.wepay_account_id AS account_id,
	a.wepay_account_uri AS account_uri');
        $query->from('#__wepay_users AS u');
        $query->leftJoin('#__wepay_accounts AS a ON u.joomla_user_id = a.joomla_user_id');
		
        $query->where('a.wepay_account_id = '. (int) $account_id);

        $db->setQuery($query);
        $object =  $db->loadObject();
     
        return $object;



	}




}
?>