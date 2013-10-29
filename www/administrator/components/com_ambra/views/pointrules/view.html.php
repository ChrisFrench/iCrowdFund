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

Ambra::load( 'AmbraViewBase', "views._base");

class AmbraViewPointRules extends AmbraViewBase 
{
    /**
     * Gets layout vars for the view
     * 
     * @return unknown_type
     */
    function getLayoutVars($tpl=null)
    {
        $layout = $this->getLayout();
        switch(strtolower($layout))
        {
            case "setoverrides":
                parent::_default( $tpl );
              break;
            case "view":
                $this->_form($tpl);
              break;
            case "form":
                JRequest::setVar('hidemainmenu', '1');
                $this->_form($tpl);
              break;
            case "default":
            default:
                $this->set( 'leftMenu', true );
                $this->_default($tpl);
              break;
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see ambra/admin/views/AmbraViewBase#_defaultToolbar()
     */
	function _defaultToolbar()
	{
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton( 'Link', 'forward', 'Check Scopes', 'index.php?option=com_ambra&view=pointrules&task=checkScopes' );
	    
	    JToolBarHelper::divider();
		JToolBarHelper::publishList( 'pointrule_enabled.enable' );
		JToolBarHelper::unpublishList( 'pointrule_enabled.disable' );
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
}
