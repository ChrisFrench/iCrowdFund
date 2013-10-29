<?php
/**
 * @version	1.5
 * @package	FeaturedItems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

FeaturedItems::load( 'FeaturedItemsModelBase', 'models.base' );

class FeaturedItemsModelItems extends FeaturedItemsModelBase
{
	protected function _buildQueryWhere( &$query )
	{
		$filter = $this->getState( 'filter' );
		$filter_id_from = $this->getState( 'filter_id_from' );
		$filter_id_to = $this->getState( 'filter_id_to' );
		$filter_name = $this->getState( 'filter_name' );
        $filter_date_from	= $this->getState('filter_date_from');
        $filter_date_to		= $this->getState('filter_date_to');
        $filter_datetype	= $this->getState('filter_datetype');
        $filter_type        = $this->getState('filter_type');
        $filter_ids        = $this->getState('filter_ids');
        $filter_fk        = $this->getState('filter_fk');
        $filter_category        = $this->getState('filter_category');
        $filter_categories        = $this->getState('filter_categories');
        $filter_label        = $this->getState('filter_label');
        $filter_labels        = $this->getState('filter_labels');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_category_id = $this->getState('filter_category_id');
        $filter_category_ids = $this->getState('filter_category_ids');
        
		if ( $filter )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter ) ) ) . '%' );
			
			$where = array( );
			$where[] = 'LOWER(tbl.item_id) LIKE ' . $key;
			$where[] = 'LOWER(tbl.item_label) LIKE ' . $key;
			$where[] = 'LOWER(tbl.item_short_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.item_long_title) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( strlen( $filter_id_from ) )
		{
			if ( strlen( $filter_id_to ) )
			{
				$query->where( 'tbl.item_id >= ' . ( int ) $filter_id_from );
			}
			else
			{
				$query->where( 'tbl.item_id = ' . ( int ) $filter_id_from );
			}
		}
		
		if ( strlen( $filter_id_to ) )
		{
			$query->where( 'tbl.item_id <= ' . ( int ) $filter_id_to );
		}
		
		if ( !empty( $filter_ids ) && is_array( $filter_ids ) )
		{
		    $id_csv = "'" . implode( "', '", $filter_ids ) . "'";
			$query->where( "tbl.item_id IN ( $id_csv )" );
		}
		
		if ( strlen( $filter_name ) )
		{
			$key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_name ) ) ) . '%' );
			$where[] = 'LOWER(tbl.item_label) LIKE ' . $key;
			$where[] = 'LOWER(tbl.item_short_title) LIKE ' . $key;
			$where[] = 'LOWER(tbl.item_long_title) LIKE ' . $key;
			
			$query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if (strlen($filter_date_from))
        {
        	switch ($filter_datetype)
        	{
        		case "published":
        			$query->where("tbl.publish_up <= '".$filter_date_from."'");		
        		  break;
        		case "date":
        		default:
        			$query->where("tbl.publish_up >= '".$filter_date_from."'");		
        		  break;
        	}
       	}
       	
		if (strlen($filter_date_to))
        {
			switch ($filter_datetype)
        	{
        		case "published":
        			$query->where("(tbl.publish_down >= '".$filter_date_to."' OR tbl.publish_down = '0000-00-00')");		
        		  break;
        		case "date":
        		default:
        			$query->where("(tbl.publish_down <= '".$filter_date_to."' OR tbl.publish_down = '0000-00-00')");		
        		  break;
        	}
       	}
       	
		if ( strlen( $filter_type ) )
		{
		    $query->where( "tbl.item_type = '$filter_type'" );
		}
		
		if ( strlen( $filter_fk ) )
		{
		    $query->where( "tbl.fk_id = '$filter_fk'" );
		}
		
		if ( strlen( $filter_category_id ) )
		{
			$query->where( "tbl.category_id = '$filter_category_id'" );
		}
		
		if ( !empty( $filter_category_ids ) && is_array( $filter_category_ids ) )
		{
			$categories_csv = "'" . implode( "', '", $filter_category_ids ) . "'";
			$query->where( "tbl.category_id IN ( $categories_csv )" );
		}
		
		if ( strlen( $filter_category ) )
		{
		    $key = $this->_db->Quote( '%' . $this->_db->getEscaped( trim( strtolower( $filter_category ) ) ) . '%' );
		    $where[] = 'LOWER(c.category_name) LIKE ' . $key;
		    	
		    $query->where( '(' . implode( ' OR ', $where ) . ')' );
		}
		
		if ( !empty( $filter_categories ) && is_array( $filter_categories ) )
		{
		    $categories_csv = "'" . implode( "', '", $filter_categories ) . "'";
		    $query->where( "c.category_id IN ( $categories_csv )" );
		}
		
		if (( !empty( $filter_labels ) && is_array( $filter_labels ) ) || (strlen($filter_label)))
		{
		    if (empty($filter_labels)) {
		        $filter_labels = array( $filter_label );
		    } else {
		        $filter_labels[] = $filter_label;
		    }
		    
		    $label_csv = "'" . implode( "', '", $filter_labels ) . "'";
		    $query->where( "tbl.item_label IN ( $label_csv )" );
		}

		if ( strlen( $filter_enabled ) )
		{
		    $query->where( 'tbl.item_enabled = ' . ( int ) $filter_enabled );
		}

	}
	
	protected function _buildQueryJoins( &$query )
	{
		$query->join( 'LEFT', '#__featureditems_categories AS c ON tbl.category_id = c.category_id' );
	}
	
	protected function _buildQueryFields( &$query )
	{
		$fields = array( );
		$fields[] = " c.category_name AS category_name ";
		
		$query->select( $this->getState( 'select', 'tbl.*' ) );
		$query->select( $fields );
	}
	
	public function getList($refresh=false)
	{
		$list = parent::getList($refresh);
		foreach ($list as $item)
	    {
	        $item->item_id = $item->item_id;
	        $item->label = $item->item_label;
	        $item->short_title = $item->item_short_title;
	        $item->long_title = $item->item_long_title;
	        $local_path = $item->item_image_local_filename ? JURI::root(true).'/' . $item->item_image_local_path . $item->item_image_local_filename : '';
	        $item->image_src = $item->item_image_url ? $item->item_image_url : $local_path;
	        $item->url = $item->item_url;
	        $item->url_target = $item->item_url_target;
	        $item->item_type = $item->item_type;
	        $item->content = '';
	        $item->link = 'index.php?option=com_featureditems&view=items&task=edit&id=' . $item->item_id;
	        $item->link_view = 'index.php?option=com_featureditems&view=items&task=display&id=' . $item->item_id;
	        $this->setItemProperties( $item );
	    }
		
		return $list;
	}

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setItemProperties( &$item )
	{
	    switch ($item->item_type)
	    {
	        case "content-category":
	            $this->setContentCategoryProperties( $item );
	            break;
	        case "content":
	            $this->setContentProperties( $item );
	            break;
	        case "mediamanager":
	            $this->setMediamanagerProperties( $item );
	            break;
	        case "calendar":
	            $this->setCalendarProperties( $item );
	            break;
	        default:
	            $item->content = $item->item_description ? $item->item_description : "<p>". $item->item_long_title . "</p>";
	            break;
	    }
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setContentProperties( &$item )
	{
	    $classname = strtolower( get_class($this) );
	    $cache = JFactory::getCache( $classname . '.content', '' );
	    $cache->setCaching(true);
	    $cache->setLifeTime('86400');
	    if (!$row = $cache->get($item->fk_id)) 
	    {
	        $db		= $this->getDbo();
	        $query	= $db->getQuery(true);
	        $query->select('*');
	        $query->from('#__content');
	        $query->where('id = ' . (int) $item->fk_id);
	        $db->setQuery((string) $query);
	        
	        if (!$db->query()) {
	            JError::raiseError(500, $db->getErrorMsg());
	        }
	        
	        $row = $db->loadObject();
	        
	        $cache->store($row, $item->fk_id);
	    }

	    if (empty($item->short_title))
	    {
	        $item->short_title = $row->title;
	    }
	
	    if (empty($item->long_title))
	    {
	        $item->long_title = '';
	    }
	    
	    if (empty($item->image_src)) 
	    {
	        if (!empty($row->images)) 
	        {
	            $images = json_decode($row->images);
	            if (!empty($images->image_fulltext))
	            {
	                $item->image_src = JURI::root(true) . '/' . $images->image_fulltext;
	            } 
	                elseif (!empty($images->image_intro))
	            {
	                $item->image_src = $images->image_intro;
	            }	            
	        }
	    }
	
	    if (empty($item->url))
	    {
	        require_once JPATH_SITE .'/components/com_content/helpers/route.php';
	        $item->url = ContentHelperRoute::getArticleRoute($row->id);
	    }
	
	    if (empty($item->content))
	    {
	        $html = $row->introtext;
	        $item->content = $html;
	    }
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setContentCategoryProperties( &$item )
	{
	    $classname = strtolower( get_class($this) );
	    $cache = JFactory::getCache( $classname . '.content-category', '' );
	    $cache->setCaching(true);
	    $cache->setLifeTime('86400');
	    if (!$row = $cache->get($item->fk_id))
	    {
	        $db		= $this->getDbo();
	        $query	= $db->getQuery(true);
	        $query->select('*');
	        $query->from('#__categories');
	        $query->where('id = ' . (int) $item->fk_id);
	        $db->setQuery((string) $query);
	         
	        if (!$db->query()) {
	            JError::raiseError(500, $db->getErrorMsg());
	        }
	         
	        $row = $db->loadObject();
	         
	        $cache->store($row, $item->fk_id);
	    }
	
	    if (empty($item->short_title))
	    {
	        $item->short_title = $row->title;
	    }
	
	    if (empty($item->long_title))
	    {
	        $item->long_title = '';
	    }
	     
	    if (empty($item->image_src))
	    {
	        if (!empty($row->params))
	        {
	            $params = json_decode($row->params);
	            if (!empty($params->image))
	            {
	                $item->image_src = JURI::root(true) . '/' . $params->image;
	            }
	        }
	    }
	
	    if (empty($item->url))
	    {
	        require_once JPATH_SITE .'/components/com_content/helpers/route.php';
	        $item->url = ContentHelperRoute::getCategoryRoute($row->id);
	    }
	
	    if (empty($item->content))
	    {
	        $html = $row->description;
	        $item->content = $html;
	    }
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setMediamanagerProperties( &$item )
	{
		if (empty($item->fk_id))
	    {
	        return;
	    }
	    
        if ( !class_exists('MediaManager') ) {
            JLoader::register( "MediaManager", JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" );
        }
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/models' );
        $model = JModel::getInstance( 'Media', 'MediaManagerModel' );
        $model->setId( $item->fk_id );
        $media = $model->getItem();
        $plugin_content = $this->getPlugin( $media );
        
        if (empty($item->short_title))
        {
            $item->short_title = $media->media_title;
        }
        
	    if (empty($item->long_title))
        {
            $item->long_title = $media->media_title;
        }
        
		if (empty($item->image_src))
        {
            $item->image_src = $media->media_image_remote;
        }
        
		if (empty($item->url))
        {
            $item->url = "index.php?option=com_com_mediamanager&view=media&task=view&id=" . $media->media_id;
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            $content = !empty($media->media_description) ? $media->media_description : $media->media_title;
            $item->content = "<p>". $content . "</p>";
        }
        
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setCalendarProperties( &$item )
	{
		if (empty($item->fk_id))
	    {
	        return;
	    }
	    
	    if ( !class_exists( 'Calendar' ) ) {
	        JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );
	    }
	    Calendar::load( 'CalendarConfig', 'defines' );
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );

        $row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$row->load( $item->fk_id );
		$row->bindObjects();
        
	    if (empty($item->short_title))
        {
            $item->short_title = $row->event_short_title;
        }
        
	    if (empty($item->long_title))
        {
            $item->long_title = $row->event_long_title;
        }
        
		if (empty($item->image_src))
        {
            $item->image_src = $row->image_src;
        }
        
		if (empty($item->url))
        {
            $item->url = $row->link_view;
            $item_id = CalendarConfig::getInstance()->get('item_id');
            $item->url .= "&Itemid=" . $item_id;
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            $html = '<p class="date cat ' . $row->primary_category_class . '">';
                $html .= date('M j', strtotime($row->eventinstance_date) ) . ' ' . JText::_( "at" ) . ' ';  
                $html .= (date('i', strtotime( $row->eventinstance_start_time ) ) == '00') ? date( 'g a', strtotime( $row->eventinstance_start_time ) ) : date( 'g:i a', strtotime( $row->eventinstance_start_time ) );
            $html .= '</p>
            <p>'. $row->event_short_title . '</p>
            ';
            $item->content = $html;
        }
	}
	
	/**
	 * Get the neccesary logic for the specific plugin ...
	 * @param unknown_type $item
	 * @return unknown_type
	 */
	function getPlugin( $media_item, $params = array( ) )
	{
		// if empty media_item->media_id or media_item->media_type, fail
		if ( empty( $media_item->media_id ) || empty( $media_item->media_type ) )
		{
			echo JText::_( 'NOT VALID MEDIA ITEM' );
			return;
		}
		//get the model 
		$hmodel = JModel::getInstance( 'handlers', 'MediaManagerModel' );
		
		$handler = $hmodel->getTable( );
		$handler->load( array( 'element' => $media_item->media_type ) );
		
		if ( empty( $handler->id ) )
		{
			echo JText::_( 'NOT VALID HANDLER' );
			return;
		}
		
		if ( empty( $handler->published ) && !empty( $handler->id ) )
		{
			$handler->published = 1;
			if ( $handler->store( ) )
			{
				// do we need to redirect?
				$uri = JURI::getInstance( );
				$redirect = $uri->toString( );
				$redirect = JRoute::_( $redirect, false );
				$this->setRedirect( $redirect );
				return;
			}
		}
		
		$import = JPluginHelper::importPlugin( 'mediamanager', $handler->element );
		
		$html = '';
		$dispatcher = JDispatcher::getInstance( );
		$results = $dispatcher->trigger( 'onDisplayMediaItem', array( $handler, $media_item ) );
		for ( $i = 0; $i < count( $results ); $i++ )
		{
			$html .= $results[$i];
		}
		
		$vars = new JObject( );
		$vars->row = $media_item;
		$vars->params = $params;
		$vars->handler_html = $html;
		
		return $vars;
	}
}
