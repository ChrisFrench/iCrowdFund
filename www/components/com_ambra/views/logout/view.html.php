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

Ambra::load( 'AmbraViewBase', "views._base", array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_ambra' ) );

class AmbraViewLogout extends AmbraViewBase  
{
    function display($tpl=null) 
    {  
        parent::display($tpl);
    }

    /**
     * 
     * @param $tpl
     * @return unknown_type
     */
    function getLayoutVars($tpl=null) 
    {
        $layout = $this->getLayout();
        switch(strtolower($layout))
        {
            case "autologout":
                $this->autologout($tpl);
              break;
            case "form":
               
                $this->_form($tpl);
              break;
            case "default":
            default:
                
                $this->_default($tpl);
              break;
        }
    }

function autologout() {
	// get param and menu URL
$params = JFactory::getApplication('site')->getParams();
$item = JFactory::getApplication()->getMenu()->getItem($params->get("quick_logout_redirect"));
$url = JRoute::_(JURI::base() . '&Itemid=' . $item->id);

$app = JFactory::getApplication();              
$user_id = JFactory::getUser()->get('id');          
$app->logout($user_id, array());
$app->redirect($url, JText::_('COM_AMBRA_SUCCESSFULLY_LOGGED_OUT'));
}
}
    