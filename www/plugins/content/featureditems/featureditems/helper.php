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

class plgContentFeaturedItemsHelper extends JObject
{
	/**
	 * Sets the modules params as a property of the object
	 * @param unknown_type $params
	 * @return unknown_type
	 */
	function __construct( $params )
	{
		$this->params = $params;
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models' );
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
	    
	    $date = JFactory::getDate();
	    
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models/' );
        $model = JModel::getInstance( 'Items', 'FeaturedItemsModel' );
        $model->setState( 'limit', $this->params->get('limit') );
        if ($this->params->get('start'))
        {
            $model->setState( 'limitstart', $this->params->get('start') );
        }
        $model->setState( 'order', 'tbl.ordering' );
        $model->setState( 'direction', 'ASC' );
        $model->setState( 'filter_datetype', 'published' );
        $model->setState( 'filter_date_from', $date->toFormat('%Y-%m-%d') );
        $model->setState( 'filter_date_to', $date->toFormat('%Y-%m-%d') );
        if (!empty($ids))
        {
            $model->setState( 'filter_ids', $ids );
        }
        
        if ($list = $model->getList(true))
        {
            foreach ($list as $list_item)
            {
                $item = new JObject();
                $item->label = $list_item->item_label;
                $item->short_title = $list_item->item_short_title;
                $item->long_title = $list_item->item_long_title;
                $item->image_src = $list_item->item_image_url;
                $item->url = $list_item->item_url;
                $item->url_target = $list_item->item_url_target;
                $item->publish_up = $list_item->publish_up;
                $item->publish_down = $list_item->publish_down;
                $item->item_type = $list_item->item_type;
                $item->content = '';
                $item->lightbox_x = '720';
                $item->lightbox_y = '450';
                $this->setItemProperties( $item, $list_item );
                
                $items[] = $item;
            }
        }

        $count = count($items);
        $layout = $this->params->get('layout');
        if ($count == 1 && empty($layout))
        {
            $this->params->set('layout', 'large');
        }
            elseif ($count > 1 && empty($layout))
        {
            $this->params->set('layout', 'small');
        }
        
		return $items;
	}
    
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setItemProperties( &$item, $list_item )
	{
        switch ($item->item_type)
        {
            case "content":
                $this->setContentProperties( $item, $list_item );
                break;
            case "mediamanager":
                $this->setMediamanagerProperties( $item, $list_item );
                break;
            case "calendar":
                $this->setCalendarProperties( $item, $list_item );
                break;
            default:
                $item->content = "<p>". $item->long_title . "</p>";
                break;
        }
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setMediamanagerProperties( &$item, $list_item )
	{
        if ( !class_exists('MediaManager') ) {
            JLoader::register( "MediaManager", JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" );
        }
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/models' );
        $model = JModel::getInstance( 'Media', 'MediaManagerModel' );
        $model->setId( $list_item->fk_id );
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
            $item->url = "index.php?option=com_mediamanager&view=media&task=view&id=" . $media->media_id;
            if ($item->url_target == '2') {
                $item->url .= "&tmpl=component";
            }
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            $content = $item->long_title;
            $item->content = "<p>". $content . "</p>";
        }
        
        $item->media_type = $media->media_type;
        if ($item->media_type == 'slideshow_bgl')
        {
            $item->lightbox_x = '600';
            $item->lightbox_y = '350';
        }
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setCalendarProperties( &$item, $list_item )
	{
	    if ( !class_exists( 'Calendar' ) ) {
	        JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" );
	    }
	    Calendar::load( 'CalendarConfig', 'defines' );
	    JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );

        $row = JTable::getInstance( 'EventInstances', 'CalendarTable' );
		$row->load( $list_item->fk_id );
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
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $item
	 * @return return_type
	 */
	function setContentProperties( &$item, $list_item )
	{
	    if (empty($list_item->fk_id))
	    {
	        return;
	    }
	    
        $row = JTable::getInstance( 'Content' );
		$row->load( $list_item->fk_id );
        
	    if (empty($item->short_title))
        {
            $item->short_title = $row->title;
        }
        
	    if (empty($item->long_title))
        {
            $item->long_title = $row->title;
        }
        
		if (empty($item->image_src))
        {
            // $item->image_src = $row->image_src;
        }
        
		if (empty($item->url))
        {
            $item->url = "index.php?option=com_content&view=article&id=" . $row->id;
            $item->url = JRoute::_( $item->url ); 
        }
        
		if (empty($item->content))
        {
            if ( !class_exists( 'Calendar' ) ) { JLoader::register( "Calendar", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_calendar" . DS . "defines.php" ); }
            if ( !class_exists( 'FeaturedItems' ) ) { JLoader::register( "FeaturedItems", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_featureditems" . DS . "defines.php" ); }
            FeaturedItems::load( 'FeaturedItemsConfig', 'defines' );
            Calendar::load( 'CalendarConfig', 'defines' );
            Calendar::load( 'DisqusAPI', 'library.disqus.disqusapi' );
            $featured_config = FeaturedItemsConfig::getInstance();
            $config = CalendarConfig::getInstance();
            $commenting_excluded = $featured_config->get('commenting_excluded');
            $exclusions = explode( ",", trim( $commenting_excluded ) );
            foreach ($exclusions as $key=>$value) { if (empty($value)) { unset($exclusions[$key]); } }
            $include_comments = true;
            if (in_array($row->id, (array) $exclusions))
            {
                $include_comments = false;
            }
            $uri = JURI::getInstance();
            if ($include_comments)
            {
                $disqus = new DisqusAPI( $config->get( 'disqus_api_key' ) );
                $disqus_thread_details = $disqus->threads->details( array( 'thread:ident'=>'article_' . $row->id, 'forum'=>$config->get( 'disqus_forum_id' ) ) );    
            }
            
            $html = "<p>";
            $html .= $item->long_title;
            if ($include_comments) 
            {
                $html .= ' <span onclick="window.location=\'' . $item->url . '#disqus\';" class="disqus_count">' . $disqus_thread_details->posts . '</span>';
            } 
            $html .= "</p>";

            $item->content = $html;
        }
	}
}
?>