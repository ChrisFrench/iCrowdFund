<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Tienda::load( 'TiendaViewBase', 'views._base' );
Tienda::load('TiendaHelperCampaign', 'helpers.campaign');

class TiendaViewCampaigns extends TiendaViewBase 
{
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
            case "selectproducts":
                $this->_default($tpl);
              break;
            case "form":
                JRequest::setVar('hidemainmenu', '1');
                $this->_form($tpl);
              break;
            case "default":
            default:
                $this->set( 'leftMenu', 'leftmenu_campaign' );
                $this->_default($tpl);
              break;
        }
    }
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/campaigns.php';
	}
    
	function _form($tpl=null)
	{
		parent::_form($tpl);
		
		$model = $this->getModel();
		
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger( 'onGetCampaignView', array( $model->getItem()  ) );

		if( !isset($this->row) )
			$this->row = new stdClass();

		if( !isset( $this->row->display_name_subcampaign ) )
			$this->row->display_name_subcampaign = 1;
		if( !isset( $this->row->display_name_campaign ) )
			$this->row->display_name_campaign = 1;
   	
	}
	
    /**
     * (non-PHPdoc)
     * @see tienda/admin/views/TiendaViewBase#_defaultToolbar()
     */
	function _defaultToolbar()
	{
		JToolBarHelper::publishList( 'campaign_enabled.enable' );
		JToolBarHelper::unpublishList( 'campaign_enabled.disable' );
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
	
/**
	 * (non-PHPdoc)
	 * @see tienda/admin/views/TiendaViewBase#_formToolbar($isNew)
	 */
    function _formToolbar( $isNew=null )
    {
    	if (!$isNew)
    	{
        	JToolBarHelper::custom('save_as', 'refresh', 'refresh', 'COM_TIENDA_SAVE_AS' , false);
    	}
        parent::_formToolbar($isNew);
    }
	
}
