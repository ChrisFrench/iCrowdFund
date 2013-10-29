<?php
/**
* @version		0.1.0
* @package		Featureditems
* @copyright	Copyright (C) 2011 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Featureditems extends DSC
{
    protected $_name = 'featureditems';
    protected $_version 		= '1.0';
    protected $_build          = 'r100';
    protected $_versiontype    = 'community';
    protected $_copyrightyear 	= '2011';
    protected $_min_php		= '5.3';

    // View Options
    var $show_linkback						= '1';
    var $include_site_css                   = '1';
    var $amigosid                           = '';
    var $page_tooltip_dashboard_disabled	= '0';
    var $page_tooltip_config_disabled		= '0';
    var $page_tooltip_tools_disabled		= '0';
    
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    public function _buildQuery()
    {
        $query = "SELECT * FROM #__featureditems_config";
        return $query;
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
            $instance = new Featureditems();
        }
    
        return $instance;
    }
    
    /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = Featureditems::getClass( 'FeatureditemsHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Featureditems::getClass( 'FeatureditemsHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Featureditems::getClass( 'FeatureditemsSelect', 'select' )->category( $selected );
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public static function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_featureditems' )  )
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
    public static function load( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_featureditems' ) )
    {
        return parent::load( $classname, $filepath, $options  );
    }
    
    /**
     * Get the URL to the folder containing all media assets
     *
     * @param string	$type	The type of URL to return, default 'media'
     * @return 	string	URL
     */
    public static function getURL($type = 'media', $com='com_featureditems')
    {
        $url = parent::getUrl($type, $com);
    
        switch($type)
        {
            case 'item_images':
                $url = JURI::root(true).'/images/com_featureditems/images/';
                break;
        }
    
        return $url;
    }
    
    /**
     * Get the path to the folder containing all media assets
     *
     * @param 	string	$type	The type of path to return, default 'media'
     * @return 	string	Path
     */
    public static function getPath($type = 'media', $com='com_featureditems')
    {
        $path = parent::getPath($type, $com);
    
        switch($type)
        {
            case 'item_images':
                $path = JPATH_SITE.DS.'images'.DS.'com_featureditems'.DS.'images';
                break;
        }
    
        return $path;
    }
}
?>
