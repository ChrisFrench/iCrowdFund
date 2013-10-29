<?php
/**
 * @version 1.5
 * @package Favorites
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgFavoritesProjects extends JPlugin 
{   
    var $params = null;

    function __construct(& $subject, $config)
    { 
        parent::__construct($subject, $config);
        $this->_isInstalled();
 
    }
    
    /**
     * Check if is installed
     * 
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR."/components/com_favorites/defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Favorites') )
            { 
                JLoader::register('Favorites', JPATH_ADMINISTRATOR.'/components/com_favorites/defines.php');
                $this->params = Favorites::getInstance();
            }
        }           
        return $success;
    }

    function onPrepareItems(&$item) {
        //Mapping Backers
        if($item->scope_id == 1) {
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
        Tienda::load( 'TiendaModelCampaigns', 'models.campaigns' );
        $model = JModel::getInstance( 'Campaigns', 'TiendaModel' );
        $item->project = $model->getItem($item->object_id);
     }
    }
        
    
}
