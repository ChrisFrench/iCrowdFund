<?php
/**
* @package		FeaturedItems
* @copyright	Copyright (C) 2011 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class FeaturedItemsSelect extends DSCSelect
{
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $selected
	 * @param unknown_type $name
	 * @param unknown_type $attribs
	 * @param unknown_type $idtag
	 * @param unknown_type $allowAny
	 * @param unknown_type $title
	 * @return return_type
	 */
	public static function url_target( $selected, $name = 'filter_url_target', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select URL Target' )
	{
		$lists = array();
	
		if ($allowAny)
		{
			$lists[] = JHTML::_('select.option', '', $title);
		}
	
		$lists[] = JHTML::_('select.option', '0', 'Same Window');
		$lists[] = JHTML::_('select.option', '1', 'New Window');
		$lists[] = JHTML::_('select.option', '2', 'Lightbox');
	
		return self::genericlist($lists, $name, $attribs, 'value', 'text', $selected );
	}
	
	/**
	 * 
	 * @param unknown_type $selected
	 * @param unknown_type $name
	 * @param unknown_type $attribs
	 * @param unknown_type $idtag
	 * @param unknown_type $allowAny
	 * @param unknown_type $title
	 */
    public static function item_type( $selected, $name = 'filter_type', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Item Type' )
    {
        $lists = array();

        if ($allowAny)
        {
            $lists[] = JHTML::_('select.option', '', $title);
        }
        
        $lists[] = JHTML::_('select.option', 'default', 'Default Featured Item');
		 if (JFile::exists( JPATH_ADMINISTRATOR.DS."components".DS."com_mediamanager".DS."defines.php" ))
        $lists[] = JHTML::_('select.option', 'mediamanager', 'MediaManager Item');
		 if (JFile::exists( JPATH_ADMINISTRATOR . "/components/com_calendar/defines.php" ))
        $lists[] = JHTML::_('select.option', 'calendar', 'Calendar Event Instance');
        $lists[] = JHTML::_('select.option', 'content', 'Content Article');
        $lists[] = JHTML::_('select.option', 'content-category', 'Content Category');
		if (JFile::exists( JPATH_ADMINISTRATOR . "/components/com_tienda/defines.php" ))
		$lists[] = JHTML::_('select.option', 'tienda-product', 'Tienda Product');
		$lists[] = JHTML::_('select.option', 'tienda-campaign', 'Tienda Campaign');
        
        return self::genericlist($lists, $name, $attribs, 'value', 'text', $selected );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function category( $selected, $name = 'filter_category', $attribs = array( 'class' => 'inputbox', 'size' => '1' ), $idtag = null, $allowAny = true, $allowNone = false, $title = 'Select Category', $title_none = 'No Parent', $enabled = null )
    {
    	$list = array( );
    
    	if ( $allowAny )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title ) . " -" );
    	}
    
    	if ( $allowNone )
    	{
    		$list[] = self::option( '', "- " . JText::_( $title_none ) . " -" );
    	}
    
    	JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/tables' );
    	JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_calendar/models' );
    	$model = JModel::getInstance( 'Categories', 'FeatureditemsModel' );
    	$model->setState( 'order', 'tbl.ordering' );
    	if ( intval( $enabled ) == '1' )
    	{
    		$model->setState( 'filter_enabled', '1' );
    	}
    	else
    	{
    		$items = $model->getList( );
    	}
    
    	foreach ( @$items as $item )
    	{
    		$list[] = self::option( $item->category_id, JText::_( $item->category_name ) );
    	}
    	return self::genericlist( $list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
}
