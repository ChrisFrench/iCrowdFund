<?php
/**
 * @version		0.1.0
 * @package		Billets
 * @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link 		http://www.dioscouri.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class Billets extends DSC 
{
	protected $_name = 'billets';
	protected $_version = '5.0.3-alpha';
	protected $_revision = '';
	protected $_versiontype    = '';
	protected $_copyrightyear = '2012';
	protected $_min_php = '5.3';
	
	public $show_linkback = '1';	
	public $amigosid = '';
	public $page_tooltip_dashboard_disabled = '0';
	public $page_tooltip_config_disabled = '0';
	public $page_tooltip_tools_disabled = '0';
	public $page_tooltip_users_disabled = '0';
	public $page_tooltip_categories_disabled = '0';
	public $page_tooltip_fields_disabled = '0';
	public $page_tooltip_tickets_disabled = '0';
	public $page_tooltip_ticketstates_disabled = '0';
	public $page_tooltip_frequents_disabled = '0';
	public $page_tooltip_users_view_disabled = '0';
	public $page_tooltip_tickets_merge_disabled = '0';
	public $files_enable = '1';
	public $files_maxsize = '3000';
	public $files_storagemethod = '1';
	public $display_name = '1';
	public $emails_defaultname = null;
	public $emails_defaultemail = null;
	public $emails_includedescription = '1';
	public $emails_descriptionmaxlength = '-1';
	public $emails_includecomments = '1';
	public $emails_commentmaxlength = '-1';
	public $emails_sendselfnotification = '0';
	public $require_login = '0';
	public $state_new = '1';
	public $state_closed = '2';
	public $state_feedback = '3';
	public $state_open = '4';
	public $kb_category = null;
	public $kb_publish = '0';
	public $kb_username = '0';
	public $kb_readmore = '0';
	public $display_ie8_notice = '1';
	public $enable_frontend_editing = '0';
	public $maxCheckOutTime = '7200';
	public $redirect_menu_id = '';
	public $locking_enabled = '1';
	public $limit_tickets_globally = '0';
	public $default_max_tickets = '10';
	public $enable_tienda = '1';
	public $restricted_file_extensions = 'shtm,shtml,htm,html,php,asp,aspx,jsp,py';
	public $auto_convert_file_extension = '1';
	public $limit_hours_globally = '0';
	public $default_max_hours = '10';
	public $emails_encoding_debug = '0';
	public $emails_save_attachments = '1';
	public $use_email_as_login = '1';
	public $number_of_tickets_links = '5';
	public $use_jquery             = '0';
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$query = "SELECT * FROM #__billets_config";
		return $query;
	}

	/**
	 * Get component config
	 *
	 * @acces	public
	 * @return	object
	 */
	public static function getInstance() {
		static $instance;

		if (!is_object($instance)) {
			$instance = new Billets();
		}

		return $instance;

	}

	/**
	 * Set Variables we need this because the billets_config is title and not config_name
	 *
	 * @acces	public
	 * @return	object
	 */
	function setVariables() {
		$success = false;

		if ($data = $this -> getData()) {
			for ($i = 0; $i < count($data); $i++) {
				$title = $data[$i] -> title;
				$value = $data[$i] -> value;
				if (isset($title)) {
					$this -> $title = $value;
				}
			}

			$success = true;
		}

		return $success;
	}

	/**
	 * Get the path to the folder containing all media assets
	 *
	 * @param 	string	$type	The type of path to return, default 'media'
	 * @return 	string	Path
	 */
	public static function getPath($type = 'media', $com = 'billets') {
		$path = '';

		switch($type) {
			case 'media' :
				$path = JPATH_SITE . DS . 'media' . DS . 'com_billets';
				break;
			case 'css' :
				$path = JPATH_SITE . DS . 'media' . DS . 'com_billets' . DS . 'css';
				break;
			case 'images' :
				$path = JPATH_SITE . DS . 'media' . DS . 'com_billets' . DS . 'images';
				break;
			case 'flags' :
				$path = JPATH_SITE . DS . 'media' . DS . 'com_billets' . DS . 'images' . DS . 'flags';
				break;
			case 'js' :
				$path = JPATH_SITE . DS . 'media' . DS . 'com_billets' . DS . 'js';
				break;
		}

		return $path;
	}

	/**
	 * Intelligently loads instances of classes in framework
	 *
	 * Usage: $object = BIllets::getClass( 'BIlletsHelperCarts', 'helpers.carts' );
	 * Usage: $suffix = BIllets::getClass( 'BIlletsHelperCarts', 'helpers.carts' )->getSuffix();
	 * Usage: $categories = BIllets::getClass( 'BIlletsSelect', 'select' )->category( $selected );
	 *
	 * @param string $classname   The class name
	 * @param string $filepath    The filepath ( dot notation )
	 * @param array  $options
	 * @return object of requested class (if possible), else a new JObject
	 */
	public static function getClass($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_billets' )) {
		return parent::getClass($classname, $filepath, $options);
	}

	/**
	 * Method to intelligently load class files in the framework
	 *
	 * @param string $classname   The class name
	 * @param string $filepath    The filepath ( dot notation )
	 * @param array  $options
	 * @return boolean
	 */
	public static function load($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_billets' )) {
		return parent::load($classname, $filepath, $options);
	}

	public static function wraplongword($str, $width = 75, $break = "\n") {
		return preg_replace('#(\S{' . $width . ',})#e', "chunk_split('$1', " . $width . ", '" . $break . "')", $str);
	}

}

// keeping for compatibility
class BilletsConfig extends Billets {
}
?>