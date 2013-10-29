<?php
/**
 * @version	0.1
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
class BilletsController extends DSCControllerAdmin {

	public $default_view = 'dashboard';

	var $message = null;
	var $messagetype = null;

	/*
	 * enable record(s)
	 */
	function enable() {
		$task = JRequest::getVar('task');
		switch (strtolower($task)) {
			case "switch_publish" :
				$field = 'enabled';
				$action = 'switch';
				break;
			case "switch" :
			case "switch_enable" :
				$field = 'enabled';
				$action = 'switch';
				break;
			case "unpublish" :
				$field = 'published';
				$action = 'disable';
				break;
			case "disable" :
				$field = 'enabled';
				$action = 'disable';
				break;
			case "publish" :
				$field = 'published';
				$action = 'enable';
				break;
			case "enable" :
			default :
				$field = 'enabled';
				$action = 'enable';
				break;
		}
		JRequest::setVar('task', $field . '.' . $action);
		$this -> boolean();
	}

}
?>