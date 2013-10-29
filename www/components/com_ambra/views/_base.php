<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


class AmbraViewBase extends DSCViewSite
{
	/**
	 * The valid task set by the controller
	 * @var str
	 */
	protected $_doTask;
	
    /**
     * First displays the submenu, then displays the output
     * but only if a valid _doTask is set in the view object
     * 
     * @param $tpl
     * @return unknown_type
     */
    function display($tpl=null) 
    {
    	// display() will return null if 'doTask' is not set by the controller
    	// This prevents unauthorized access by bypassing the controllers
    	if (empty($this->_doTask))
    	{
    		return null;
    	}
    	
        $this->getLayoutVars($tpl);
        
        $dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger( 'onDisplayViewSiteComponentAmbra', array() );
        
        Ambra::load('AmbraMenu', 'library.menu');
       
        
        parent::display($tpl);
    }
	
	/**
	 * Displays a submenu if there is one and if hidemainmenu is not set
	 * 
	 * @param $selected
	 * @return unknown_type
	 */
	function displaySubmenu($selected='')
	{
		if (!JRequest::getInt('hidemainmenu') && empty($this->hidemenu)) 
		{
			jimport('joomla.html.toolbar');
			require_once( JPATH_ADMINISTRATOR.'/includes/toolbar.php' );
			$view = strtolower( JRequest::getVar('view') );

			if (!empty(JFactory::getUser()->id))
			{
			    $id = $this->getModel()->getId();
	            //JSubMenuHelper::addEntry(JText::_('Dashboard'), JRoute::_('index.php?option=com_ambra&view=dashboard'), $view == 'dashboard' ? true : false );
	            $suffix = "";
	            if ($id != JFactory::getUser()->id)
	            {
	                $suffix = "&id=".$id;
	            }
	            JSubMenuHelper::addEntry(JText::_('Profile'), JRoute::_('index.php?option=com_ambra&view=users'.$suffix), $view == 'users' ? true : false );
	            JSubMenuHelper::addEntry(JText::_('Points Earned'), JRoute::_('index.php?option=com_ambra&view=pointhistory'.$suffix), $view == 'users' ? true : false );
	            
	            if (empty($id) || $id == JFactory::getUser()->id)
	            {
                    JSubMenuHelper::addEntry(JText::_('Logout'), JRoute::_('index.php?option=com_ambra&view=logout'), $view == 'logout' ? true : false );
	            }
			}
		}
	}
	
  
	
}