<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


class BilletsViewBase extends DSCViewAdmin 
{
	
	/**
	 * Displays a layout file 
	 * 
	 * @param unknown_type $tpl
	 * @return unknown_type
	 */
	function display($tpl=null)
	{
	    JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
		JHTML::_('stylesheet', 'admin.css', 'media/com_billets/css/');
		JHTML::_('script', 'billets.js','media/com_billets/js/');
	    JHTML::_('script', 'core.js', 'media/system/js/');
	
    	$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/helpers';
		DSCLoader::discover('BilletsHelper', $parentPath, true);
		
		$parentPath = JPATH_ADMINISTRATOR . '/components/com_billets/library';
		DSCLoader::discover('Billets', $parentPath, true);
        $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );        
        parent::display($tpl);
		
	}
	
	  
	 
	 /**
	 * Finds any plugins meant to extend the form and adds them if so 
	 * @return void
	 */
	function _getFormPlugins()
	{
		$view = strtolower( JRequest::getVar('view') );
		
        // Get plugins
        $filtered_sliders = array();
    
		Billets::load('BilletsTools','library.tools');
        $items = BilletsTools::getPlugins();
		for ($i=0; $i < count($items); $i++) 
		{
			$item = &$items[$i];
			// Check if they have an event
			if (BilletsTools::hasEvent( $item, "getFormSliders{$view}" )) 
			{
				// add item to filtered array
				$filtered_sliders[] = $item;
			}
		}
		$items_sliders = $filtered_sliders;
        $this->assign( 'items_sliders', $items_sliders );
	}
}