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

class AmbraViewCategories extends AmbraViewBase 
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
            case "selectfields":
            case "selectprofiles":
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

        foreach ($items as $item)
        {
            $item->fields_list = '';
            $model = Ambra::getClass( "AmbraModelFields", 'models.fields' );
            $model->setState('filter_category', $item->category_id);
            if ($fields = $model->getList())
            {
                $field_names = array();
                foreach ($fields as $field)
                {
                    $field_names[] = JText::_( $field->field_name );
                }
                $item->fields_list = implode(', ', $field_names);
            }
            
            $item->profiles_list = '';
            $model = Ambra::getClass( "AmbraModelProfiles", 'models.profiles' );
            $model->setState('filter_category', $item->category_id);
            if ($profiles = $model->getList())
            {
                $profile_names = array();
                foreach ($profiles as $profile)
                {
                    $profile_names[] = JText::_( $profile->profile_name );
                }
                $item->profiles_list = implode(', ', $profile_names);
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
        JToolBarHelper::publishList( 'category_enabled.enable' );
        JToolBarHelper::unpublishList( 'category_enabled.disable' );
        JToolBarHelper::divider();
        parent::_defaultToolbar();
    }
}
