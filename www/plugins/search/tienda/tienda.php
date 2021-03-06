<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgSearchTienda extends JPlugin 
{
    public $cache_enabled = true; // TODO Make this a param and/or make it depend on global setting
    public $cache_lifetime = '900';  // TODO Make this a param and/or make it depend on global setting
        
    public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
    /**
     * Checks the extension is installed
     * 
     * @return boolean
     */
    function _isInstalled()
    {
        $success = false;
        
        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ADMINISTRATOR.'/components/com_tienda/defines.php')) 
        {
            $success = true;
            if ( !class_exists('Tienda') ) { 
                JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );
            }
            
            $this->defines = Tienda::getInstance();
            Tienda::load( 'TiendaHelperProduct', 'helpers.product' );
        }
        return $success;
    }
    
    private function doSearchAreas() 
    {
        if (!$this->_isInstalled())
        {
            return array();
        }
        
        $areas = array(
            'tienda' => $this->params->get('title', "Tienda")
        );
        
        return $areas;
    }
    
    /**
     * Tells the seach component what extentions are being searched
     * 
     * @return unknown_type
     */
    function onSearchAreas()
    {
        return $this->doSearchAreas();
    }
    
	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas()
	{
		return $this->doSearchAreas();
	}
	
	private function doSearch( $keyword='', $match='', $ordering='', $areas=null )
	{
	    if (!$this->_isInstalled())
	    {
	        return array();
	    }
	
	    if ( is_array( $areas ) )
	    {
	        if ( !array_intersect( $areas, array_keys( $this->doSearchAreas() ) ) )
	        {
	            return array();
	        }
	    }
	
	    $keyword = trim( $keyword );
	    if (empty($keyword))
	    {
	        return array();
	    }

	    $cache_key = base64_encode(serialize($keyword) . serialize($match) . serialize($ordering) . serialize($areas)) . '.search-results' ;
	    $classname = strtolower( get_class($this) );
	    $cache = JFactory::getCache( $classname . '.search-results', '' );
	    $cache->setCaching($this->cache_enabled);
	    $cache->setLifeTime($this->cache_lifetime);
	    $list = $cache->get($cache_key);	    
	    
	    if (empty($list)) 
	    {
	        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
	        JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
	        $model = JModel::getInstance( 'Products', 'TiendaModel' );
	        $model->setState( 'filter_published', 1 );
	        $model->setState( 'filter_enabled', 1 );
	        $model->setState( 'filter_published_date', JFactory::getDate()->toMySQL() );
	        $match = strtolower($match);

	        switch ($match)
	        {
	            case 'any':
	                $model->setState('filter_any', $keyword);
	                break;
	            case 'all':
	                $model->setState('filter_all', $keyword);
	                break;
	            case 'exact':
	            default:
	                $model->setState('filter', $keyword);
	                break;
	        }
	         
	        // order the items according to the ordering selected in com_search
	        switch ( $ordering )
	        {
	            case 'newest':
	                $model->setState('order', 'tbl.created_date');
	                $model->setState('direction', 'DESC');
	                break;
	            case 'oldest':
	                $model->setState('order', 'tbl.created_date');
	                $model->setState('direction', 'ASC');
	                break;
	            case 'alpha':
	            case 'popular':
	            default:
	                $model->setState('order', 'tbl.product_name');
	                break;
	        }
	         
	        // filter according to shopper group
	        Tienda::load( 'TiendaHelperUser', 'helpers.user' );
	        $user_id = JFactory::getUser( )->id;
	        $model->setState('filter_group', TiendaHelperUser::getUserGroup( $user_id ) );
	         
	        //display_out_of_stock
	        if (!$this->defines->get('display_out_of_stock'))
	        {
	            $model->setState( 'filter_quantity_from', '1' );
	        }
	         
	        $items = $model->getListRaw();
	        
	        if (empty($items)) {
	            return array();
	        }
            
	        // format the items array according to what com_search expects
	        foreach ($items as $key => $item)
	        {
	            $item->itemid_string = null;
	            $item->itemid = (int) Tienda::getClass( "TiendaHelperRoute", 'helpers.route' )->product( $item->product_id, null, true );
	            if (!empty($item->itemid)) {
	                $item->itemid_string = "&Itemid=".$item->itemid;
	            }
	            $item->link = 'index.php?option=com_tienda&view=products&task=view&id=' . $item->product_id;
	            $item->href = $item->link . $item->itemid_string;
                
	            $item->title        = $item->product_name;
	            $item->created      = $item->created_date;
	            $item->section      = $this->params->get('title', "Tienda");
	            $item->text         = $item->product_description;
	            $item->browsernav   = $this->params->get('link_behaviour', "0");
	            
	            $item->source = $classname;
	        }
	        
	        $list = $items;
	        
	        $cache->store($list, $cache_key);
	    }
	    
	    return $list;
	}

    /**
     * Content Search method
     * The sql must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav
     * @param string Target search string
     * @param string mathcing option, exact|any|all
     * @param string ordering option, newest|oldest|popular|alpha|category
     * @param mixed An array if the search it to be restricted to areas, null if search all
     */
    public function onContentSearch($keyword, $match='', $ordering='', $areas=null)
    {
        return $this->doSearch( $keyword, $match, $ordering, $areas );
    }
	
	
    /**
     * Performs the search
     *
     * @param string $keyword
     * @param string $match
     * @param unknown_type $ordering
     * @param unknown_type $areas
     * @return unknown_type
     */
    public function onSearch( $keyword='', $match='', $ordering='', $areas=null )
    {
        return $this->doSearch( $keyword, $match, $ordering, $areas );
    }
}
?>