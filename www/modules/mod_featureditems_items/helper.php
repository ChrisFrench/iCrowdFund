<?php
/**
 * @package Featured Items
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted Access' );

jimport( 'joomla.application.component.model' );

class modFeaturedItemsItemsHelper extends JObject
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
	}
	
	/**
	 * Gets the various db information to sucessfully display item
	 * @return unknown_type
	 */
	function getItems()
	{
	    $items = array();
	    
	    $ids = array();
	    $param_ids = explode(',', $this->params->get('ids') );
	    foreach( $param_ids as $id )
	    {
	        $id = trim($id);
	        if (!empty($id))
	        {
	            $ids[] = $id;
	        }
	    }
	    
	    $categories = array();
	    $param_categories = explode(',', $this->params->get('categories') );

	    foreach( $param_categories as $category )
	    {
	        $category = trim($category);

	        if (!empty($category))
	        {
	            $categories[] = $category;
	        }
	    }
	    
	    $labels = array();
	    $param_labels = explode(',', $this->params->get('labels') );
	    foreach( $param_labels as $label )
	    {
	        $label = trim($label);
	        if (!empty($label))
	        {
	            $labels[] = $label;
	        }
	    }
	    
	    $date = JFactory::getDate();
	    
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models/' );
        $model = JModel::getInstance( 'Items', 'FeaturedItemsModel' );
        $model->setState( 'limit', $this->params->get('limit') );
        $model->setState( 'order', 'tbl.item_id' );
        $model->setState( 'direction', 'DESC' );
        $model->setState( 'filter_enabled', '1' );
        $model->setState( 'filter_datetype', 'published' );
        $model->setState( 'filter_date_from', $date->toFormat('%Y-%m-%d') );
        $model->setState( 'filter_date_to', $date->toFormat('%Y-%m-%d') );


        if (!empty($ids))
        {
            $model->setState( 'filter_ids', $ids );
        }
        if (!empty($categories))
        {
            $model->setState( 'filter_categories', $categories );
        }
        if (!empty($labels))
        {
            $model->setState( 'filter_labels', $labels );
        }
        
        $items = $model->getList();

	    if (!empty($ids))
        {
            $unsorted = $items;
            $items = array();
            foreach ($ids as $id)
            {
                foreach ($unsorted as $key=>$value)
                {
                    if ($value->item_id == $id)
                    {
                        $items[] = $value;
                    }
                }
            }
        }
        
		return $items;
	}

}
?>