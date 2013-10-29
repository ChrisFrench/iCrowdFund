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

class plgSearchCampaigns extends JPlugin 
{   
   
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
            if ( !class_exists('Tienda') ) 
                JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );
        }
        return $success;
    }
    
    
    
	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas()
	{
		if (!$this->_isInstalled())
        {
       
           return null;
        }	
		static $areas = array(
			'campaigns' => 'Projects'
		);
		return $areas;
	}

	
	/**
	* Contacts Search method
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		
		
		if (!$this->_isInstalled())
        {
            return array();
        }
        if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
        
        
        $text = trim( $text );
        if (empty($text)) 
        {
            return array();
        }
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
        $model = JModel::getInstance( 'Campaigns', 'TiendaModel' );
        $model->setState( 'filter_enabled', 1 );
		$model->setState('filter_group_states',1);
       
        $phrase = strtolower($phrase);
        switch ($phrase)
        {
            case 'exact':
                $model->setState('filter', $phrase);
            case 'all':
            case 'any':
            default:
                $words = explode( ' ', $text );
                $wheres = array();
                foreach ($words as $word)
                {
                    $model->setState('filter', $word);
                }
                break;
        }
        
        // order the items according to the ordering selected in com_search
        switch ( $ordering ) 
        {
            case 'newest':
                $model->setState('order', 'tbl.fundingstart_date');
                $model->setState('direction', 'DESC');
                break;
            case 'oldest':
                $model->setState('order', 'tbl.fundingstart_date');
                $model->setState('direction', 'ASC');
                break;
            case 'alpha':
            case 'popular':
            default:
                $model->setState('order', 'tbl.product_name');
                break;
        }

        $items = $model->getList();
        if (empty($items)) { return array(); }
 
				if ( !class_exists('Tienda') ) 
				    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );
        
        // format the items array according to what com_search expects
        foreach ($items['active'] as $key => $item)
        {   
	        	
	        $item->href         = $item->view_link;
            $item->title        = $item->campaign_name;
         //   $item->created      = $item->fundingstart_date;
            $item->created      = $item->fundingstart_date;
            $item->section      = 'Project';
            $item->text         = substr( $item->campaign_shortdescription, 0, 250);
            $item->browsernav   = $this->params->get('link_behaviour', "1");                
        }

        return $items['active'];
    }
	
	
}
?>
