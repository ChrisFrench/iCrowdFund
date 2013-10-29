<?php 
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class Tos extends DSC
{
    protected $_name = 'tos';
    protected $_version 		= '1.0';
    protected $_build          = 'r100';
    protected $_versiontype    = '';
    protected $_copyrightyear 	= '2012';
    protected $_min_php		= '5.3';

    // View Options
    public $show_linkback						= '1';
    public $include_site_css                   = '1';
    public $amigosid                           = '';
    public $page_tooltip_dashboard_disabled	= '0';
    public $page_tooltip_config_disabled		= '0';
    public $page_tooltip_tools_disabled		= '0';
    
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    public function _buildQuery()
    {
        $query = "SELECT * FROM #__tos_config";
        return $query;
    }
    
    /**
     * Retrieves the data, using a cached set if possible
     *
     * @return array Array of objects containing the data from the database
     */
    public function getData()
    {
        $cache = JFactory::getCache('com_tos.defines');
        $cache->setCaching(true);
        $cache->setLifeTime('86400');
        $data = $cache->call( array($this, 'loadData') );
        return $data;
    }
    
    /**
     * Loads the data from the database
     */
    public function loadData()
    {
        $data = array();
    
        $database = JFactory::getDBO();
        if ($query = $this->_buildQuery())
        {
            $database->setQuery( $query );
            $data = $database->loadObjectList();
        }
    
        return $data;
    }
    
    /**
     * Get component config
     *
     * @acces	public
     * @return	object
     */
    public static function getInstance()
    {
        static $instance;
    
        if (!is_object($instance)) {
            $instance = new Tos();
        }
    
        return $instance;
    }
    
    /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = Tos::getClass( 'TosHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Tos::getClass( 'TosHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Tos::getClass( 'TosSelect', 'select' )->category( $selected );
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public static function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_tos' )  )
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
    public static function load( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_tos' ) )
    {
        return parent::load( $classname, $filepath, $options  );
    }
}
?>
