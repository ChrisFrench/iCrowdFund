<?php
/**
* @version      0.1.0
* @package      Messagebottle
* @copyright    Copyright (C) 2011 DT Design Inc. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Messagebottle extends DSC
{
    protected $_name = 'messagebottle';
    protected $_version         = '1.0';
    protected $_build          = 'r100';
    protected $_versiontype    = '';
    protected $_copyrightyear   = '2012';
    protected $_min_php     = '5.3';
    public $key = '';   
    // View Options
    public $show_linkback                       = '1';
    public $include_site_css                   = '1';
    public $amigosid                           = '';
    public $page_tooltip_dashboard_disabled = '0';
    public $page_tooltip_config_disabled        = '0';
    public $page_tooltip_tools_disabled     = '0';
     // Defaults
    public $default_name = 'Website name';
    public $default_email = 'user@address.com';

    //ManDrill
    public $md_host = 'smtp.mandrillapp.com';
    public $md_port = '587';
    public $md_smtp_username = '';
    public $md_smtp_api_key = '';

    
    
    /**
     * Retrieves the data, using a cached set if possible
     *
     * @return array Array of objects containing the data from the database
     */
    public function getData()
    {   

      $data = $this->loadData();
        return $data;
    }
    
    /**
     * Set Variables
     *
     * @acces   public
     * @return  object
     */
    public function setVariables() 
    {
        $success = false;


        $data = $this->getData();

        if ( !empty($data) )
        {

            foreach($data as $title => $value) {
                if (!empty($title)) {
                    $this->$title = $value;
                }
            }

            $success = true;
        }

        return $success;
    }

    
    /**
     * Loads the data from the database
     */
    public function loadData()
    {  
        $option = 'com_messagebottle';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('extension_id AS id, element AS "option", params, enabled');
        $query->from('#__extensions');
        $query->where($query->qn('type') . ' = ' . $db->quote('component'));
        $query->where($query->qn('element') . ' = ' . $db->quote($option));
        $db->setQuery($query);

        $cache = JFactory::getCache('_system', 'callback');

        $result =  $cache->get(array($db, 'loadObject'), null, $option, false);

        $data = json_decode($result->params);
        $data->id = $result->id;
        $data->option = $result->option;
        $data->enabled = $result->enabled;
        return $data;

       
    }
    
    /**
     * Get component config
     *
     * @acces   public
     * @return  object
     */
    public static function getInstance()
    {
        static $instance;
    
        if (!is_object($instance)) {
            $instance = new Messagebottle();
        }
    
        return $instance;
    }
    
    /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = Messagebottle::getClass( 'MessagebottleHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Messagebottle::getClass( 'MessagebottleHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Messagebottle::getClass( 'MessagebottleSelect', 'select' )->category( $selected );
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public static function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_messagebottle' )  )
    {
        return parent::getClass( $classname, $filepath, $options  );
    }
    
    /**
     * Method to intelligently load class files in the framework
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return boolean
     */
    public static function load( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_messagebottle' ) )
    {
        return parent::load( $classname, $filepath, $options  );
    }
}
?>
