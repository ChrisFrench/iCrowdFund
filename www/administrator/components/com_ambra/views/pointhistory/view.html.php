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

class AmbraViewPointHistory extends AmbraViewBase 
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
            case "view":
                $this->_form($tpl);
              break;
            case "form":
                JRequest::setVar('hidemainmenu', '1');
                $this->_form($tpl);
              break;
             case "batchedit":
             	$this->_batchdedit($tpl);
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
		JToolBarHelper::publishList( 'pointhistory_enabled.enable', 'Enable' );
		JToolBarHelper::unpublishList( 'pointhistory_enabled.disable', 'Disable' );
		JToolBarHelper::divider();
		JToolBarHelper::custom('batchedit', "delete", "delete", JText::_( 'Batch Delete' ), false);
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
	
    /**
     * 
     * @return void
     **/
    function _form($tpl = null) 
    {
        parent::_form($tpl);
        
        $model = $this->getModel();
        $row = $model->getItem();
        $this->assign('row', $row );
        
        $user_id = @$row->user_id ? @$row->user_id : JRequest::getVar( 'user_id' );
        // Element
        $elementmodel   = JModel::getInstance( 'ElementUser', 'AmbraModel' );
        $elementUser    = $elementmodel->fetchElement( 'user_id', $user_id );
        $this->assign('elementUser', $elementUser);
        $resetUser      = $elementmodel->clearElement( 'user_id', '0' );
        $this->assign('resetUser', $resetUser);
    }

    /*
     * 
     */
    
    function _batchdedit($tpl=null)
	{	
		
		// Import necessary helpers + library files
		JLoader::import( 'com_ambra.library.select', JPATH_ADMINISTRATOR.DS.'components' );
		JLoader::import( 'com_ambra.library.grid', JPATH_ADMINISTRATOR.DS.'components' );
		JLoader::import( 'com_ambra.library.url', JPATH_ADMINISTRATOR.DS.'components' );
		$model = $this->getModel();
		
		// set the model state
			$this->assign( 'state', $model->getState() );
			
		// page-navigation
			$this->assign( 'pagination', $model->getPagination() );
		
		// list of items
			$items = $model->getList();	
			$this->assign('items', $items);
			
		// set toolbar
			$this->_batcheditToolbar();
			
		// form
			$validate = JUtility::getToken();
			$form = array();
			$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
			$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
			$action = $this->get( '_action', "index.php?option=com_ambra&controller={$controller}&view={$view}" );
			$form['action'] = $action;
			$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
			$this->assign( 'form', $form );
	}
    function _batcheditToolbar()
	{
		$this->set('title', "User Point Delete" );
		JToolBarHelper::custom('batchDelete', "delete", "delete", JText::_( 'Batch Delete' ), false);
		JToolBarHelper::cancel();
	}
  function _userPointsToolbar()
	{
		JToolBarHelper::publishList( 'pointhistory_enabled.enable', 'Enable' );
		JToolBarHelper::unpublishList( 'pointhistory_enabled.disable', 'Disable' );
		JToolBarHelper::divider();
		JToolBarHelper::custom('batchedit', "delete", "delete", JText::_( 'Batch Delete' ), false);
		JToolBarHelper::divider();
		JToolBarHelper::editList();
        JToolBarHelper::addnew();
	}
 /**
     * Basic commands for displaying a list
     *
     * @param $tpl
     * @return unknown_type
     */
    function _default($tpl='')
    {
        $model = $this->getModel();

        // set the model state
            $this->assign( 'state', $model->getState() );

        if (empty($this->hidemenu))
        {
            // add toolbar buttons
                $this->_userPointsToolbar();
        }

        // page-navigation
            $this->assign( 'pagination', $model->getPagination() );

        // list of items
            $this->assign('items', $model->getList());

        // form
            $validate = JUtility::getToken();
            $form = array();
            $controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
            $view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
            $action = $this->get( '_action', "index.php?option=com_ambra&controller={$controller}&view={$view}" );
            $form['action'] = $action;
            $form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
            $this->assign( 'form', $form );
    }
}
