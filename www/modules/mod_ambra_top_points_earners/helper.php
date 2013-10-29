<?php
/**
 * @package    Ambra
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class modAmbraTopPointsEarnersHelper extends JObject
{
    var $items = array();
    var $params = null;

    /**
     * Constructor to set the object's params
     * 
     * @param $params
     * @return unknown_type
     */
    function __construct( $params )
    {
        parent::__construct();
        $this->params = $params;
    }
    
    function isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php';
        if (JFile::exists($filePath))
        {
            JLoader::register('Ambra', JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php');
            Ambra::load( 'AmbraConfig', 'defines' );
            Ambra::load( 'AmbraQuery', 'library.query' );
            Ambra::load( 'AmbraHelperBase', 'helpers._base' );
            $success = true;
        }           
        return $success;
    }
    
	/**
	 * Gets the users with the most points
	 * 
	 * @return unknown_type
	 */
	function getItems()
	{
		if (empty($this->items))
		{
            $exclusions_list = @preg_replace( '/\s/', '', $this->params->get( 'exclusions_list', '' ) );
            $exclusions_array = explode( ',', $exclusions_list );

            $exclusions_list_profiles = @preg_replace( '/\s/', '', $this->params->get( 'exclusions_list_profiles', '' ) );
            $exclusions_array_profiles = explode( ',', $exclusions_list_profiles );
            
		    $query = new AmbraQuery();
		    $query->select( 'tbl.id' );
		    $query->select( 'tbl.username' );
		    $query->select( 'data.points_total' );
		    $query->from( '#__users AS tbl' );
		    $query->join('LEFT', '#__ambra_userdata AS data ON tbl.id = data.user_id');
		    $query->where( "tbl.id NOT IN ('". implode("', '", $exclusions_array ) ."')" );
		    if (!empty($exclusions_list_profiles))
		    {
		        $query->where( "data.profile_id NOT IN ('". implode("', '", $exclusions_array_profiles ) ."')" );
		    }
		    $query->order( 'data.points_total DESC' );
		    $query->order( 'tbl.lastvisitDate DESC' );
		    
		    jimport( 'joomla.application.component.model' );
		    JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
		    $users = JTable::getInstance('Users', 'AmbraTable');
		    JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
		    $model = JModel::getInstance( 'Users', 'AmbraModel');
		    $model->setQuery( $query );
            $model->setState( 'limit', $this->params->get('limit', '10') );		    

            $this->items = $model->getList();
            if (empty($this->items))
            {
                $this->items = array();
            }
		}
		return $this->items;
	}
}