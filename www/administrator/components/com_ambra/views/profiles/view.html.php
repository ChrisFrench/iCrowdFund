<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load( 'AmbraViewBase', "views._base");

class AmbraViewProfiles extends AmbraViewBase 
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
            case "selectcategories":
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
                $this->_default($tpl);
              break;
        }
    }
    
    function _default($tpl='')
    {
        parent::_default( $tpl );
        $model = $this->getModel();

        // list of items
        $items = $model->getList();
        foreach($items as $item)
        {
            $item->categories_list = '';
            $model = Ambra::getClass( "AmbraModelCategories", 'models.categories' );
            $model->setState('filter_profile', $item->profile_id);
            if ($categories = $model->getList())
            {
                $cats = array();
                foreach ($categories as $category)
                {
                    $cats[] = JText::_( $category->category_name );
                }
                $item->categories_list = implode(', ', $cats);
            }
        }
        
        $this->assign('items', $items);
    }
    
    /**
     * (non-PHPdoc)
     * @see ambra/admin/views/AmbraViewBase#_defaultToolbar()
     */
	function _defaultToolbar()
	{
		JToolBarHelper::publishList( 'profile_enabled.enable' );
		JToolBarHelper::unpublishList( 'profile_enabled.disable' );
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
}
