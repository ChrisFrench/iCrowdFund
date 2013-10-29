<?php
/**
 * @package	Extendform
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Extendform::load('ExtendformViewBase','views.base');

class ExtendformViewForms extends ExtendformViewBase 
{
	
	function _defaultToolbar()
	{
	
        JToolBarHelper::publishList( 'enabled.enable' );
        JToolBarHelper::unpublishList( 'enabled.disable' );
		
		parent::_defaultToolbar();
	}
	
	
	function getLayoutVars($tpl=null) 
	{
		$layout = $this->getLayout();
		switch(strtolower($layout))
		{
			case "view":
				$this->_form($tpl);
			  break;
			case "form":
				JRequest::setVar('hidemainmenu', '1');
				$this->_form($tpl);
			  break;
			case "default":
			default:
				$this->_default($tpl);
			  break;
		}
	}
	
	
    
}
