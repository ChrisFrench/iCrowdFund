<?php
/**
* @package      Ambra
* @copyright    Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Ambra extends DSC {
    protected $_version        = '1.1.0';
    protected $_versiontype    = 'commercial';
    protected $_copyrightyear  = '2011';
    protected $_name           = 'ambra';
    protected $_min_php        = '5.3';
    protected $_build          = 'r343';

    // CONFIG
    public $show_linkback                      = '1';
    public $page_tooltip_dashboard_disabled    = '0';
    public $page_tooltip_config_disabled       = '0';
    public $page_tooltip_tools_disabled        = '0';
    public $page_tooltip_users_view_disabled   = '0';
    public $approve_new                        = '0';
    public $display_dashboard_statistics       = '1';
    public $login_redirect_url                 = 'index.php';
    public $login_redirect                     = '1';
    public $date_format                        = '%a, %d %b %Y, %I:%M%p';
    public $display_submenu                    = '1';
    public $defaults_title                     = 'Basic Information something';
    public $article_login                      = '0';
    public $article_logout                     = '0';
    public $article_points                     = '0';
    public $article_points_itemid              = '';
    public $days_before_points_can_be_earned   = '0';
    public $max_daily_points                   = '100';
    public $max_total_points                   = '-1';
    public $point_conversion_rate              = '10';
    public $amigos_registration                = '1';
    public $phplist_registration               = '1';
    public $phplist_newsletters_csv            = '';
    public $display_coupon_form                = '1';
    public $allchimp_registration              = '1';
    public $campaignmonitor_registration       = '0';
    public $campaignmonitor_api_key            = '';
    public $campaignmonitor_listid             = '';
    public $registration_redirect_url          = 'index.php';
    public $registration_redirect              = '1';
    public $login_point_notification           = '1';
    public $avatar_point_notification          = '1';
    public $affiliate_point_notification       = '1'; 
    public $productcomment_point_notification  = '1';  
    public $expiration_of_points               = '1';
    public $expirationperiod                   = '';
    public $points_in_lightbox                 = '0';
    public $last_expired_points                ='';
    public $purchase_point_notification        ='1';
    public $use_jquery                         = '0';
    public $profile_itemid                     = '0';
    public $vanity_itemid                      = '119';
    public $hybridauth_int                     = '0';
    //GRAVATER
    public $gravatar_support                     = '0';
    public $gravatar_default                     = 'default';
    public $gravatar_size                     = '0';
    //Advanced
    public $registration_profiles_templates   = '0';
    /**
     * Get the URL to the folder containing all media assets
     *
     * @param string    $type   The type of URL to return, default 'media'
     * @return  string  URL
     */
   public static function getURL( $type = 'media', $com='com_ambra' )
    {
        $url = '';

        switch($type)
        {
            case 'media' :
                $url = JURI::root(true).'/media/com_ambra/';
                break;
            case 'css' :
                $url = JURI::root(true).'/media/com_ambra/css/';
                break;
            case 'images' :
                $url = JURI::root(true).'/media/com_ambra/images/';
                break;
            case 'js' :
                $url = JURI::root(true).'/media/com_ambra/js/';
                break;
            case 'profilepics' :
            case 'avatars' :
                $url = JURI::root(true).'/images/com_ambra/profilepics/';
                break;
        }

        return $url;
    }

    /**
     * Get the path to the folder containing all media assets
     *
     * @param   string  $type   The type of path to return, default 'media'
     * @return  string  Path
     */
    public static function getPath( $type = 'media', $com='com_ambra' )
    {
        $path = '';

        switch($type)
        {
            case 'media' :
                $path = JPATH_SITE.'/media/com_ambra';
                break;
            case 'css' :
                $path = JPATH_SITE.'/media/com_ambra/css';
                break;
            case 'images' :
                $path = JPATH_SITE.'/media/com_ambra/images';
                break;
            case 'js' :
                $path = JPATH_SITE.'/media/com_ambra/js';
                break;
            case 'scopes' :
                $path = JPATH_SITE.'/media/com_ambra/scopes';
                break;
            case 'profilepics' :
            case 'avatars' :
                $path = JPATH_SITE.'/images/com_ambra/profilepics';
                break;
        }

        return $path;
    }

    
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    public function _buildQuery() {
        $query = "SELECT * FROM #__ambra_config";
        return $query;
    }

    /**
     * Retrieves the data, using a cached set if possible
     *
     * @return array Array of objects containing the data from the database
     */
    public function getData() {
        $cache = JFactory::getCache('com_ambra.defines');
        $cache -> setCaching(true);
        $cache -> setLifeTime('86400');
        $data = $cache -> call(array($this, 'loadData'));
        return $data;
    }

    /**
     * Loads the data from the database
     */
    public function loadData() {
        $data = array();

        $database = JFactory::getDBO();
        if ($query = $this -> _buildQuery()) {
            $database -> setQuery($query);
            $data = $database -> loadObjectList();
        }

        return $data;
    }

    /**
     * Get component config
     *
     * @acces   public
     * @return  object
     */
    public static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new Ambra();
        }

        return $instance;
    }

    /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = Ambra::getClass( 'AmbraHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Ambra::getClass( 'AmbraHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Ambra::getClass( 'AmbraSelect', 'select' )->category( $selected );
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public static function getClass($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_ambra' )) {
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
    public static function load($classname, $filepath = 'controller', $options = array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_ambra' )) {
        return parent::load($classname, $filepath, $options);
    }
   

}

/**
 * 
 * @author Rafael Diaz-Tushman
 *
 */
class AmbraConfig extends Ambra {}
?>