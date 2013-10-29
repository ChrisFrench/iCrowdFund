<?php
/**
 * @version	1.5
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Wepay::load( 'WepayViewBase', 'views.base', array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_wepay' ) );
Wepay::load( "TiendaHelperProduct", 'com.wepay.helpers.product' );
Wepay::load('WepayHelperCampaign', 'helpers.campaign');
if ( !class_exists('Wepay') ) 
    JLoader::register( "Wepay", JPATH_ADMINISTRATOR."/components/com_wepay/defines.php" );

class WepayViewCampaigns extends WepayViewBase
{
	
	/**
	 * Basic commands for displaying a campaign layout
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function _campaign($tpl='')
	{
		Wepay::load( 'WepaySelect', 'library.select' );
	
		
		
		// form
		$validate = JUtility::getToken();
		$form = array();
		$view = strtolower( JRequest::getVar('view') );
		$form['action'] = "index.php?option=com_wepay&controller={$view}&view={$view}";
		$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
		$this->assign( 'form', $form );
	}
	function _mycampaigns($tpl='')
	{
		$userid = $this->validateUser();
		$model = $this->getModel();
		$model->setState('filter_user_id',$userid);
		$model->setState('filter_group_states','1');
		$rows = $model->getList();
		
		
		$this->assign( 'rows', $rows );
	}
	
	
	
	
	function _campaignsFunded($tpl='')
	{
		$userid = $this->validateUser();
		Wepay::load( 'WepayModelCampaignBackers', 'models.campaignproducts' );
		$model = JModel::getInstance( 'CampaignBackers', 'WepayModel' );
		
		$model->setState('filter_userid',$userid); 
		
		$rows = $model->getCampaignsList();
		$this->assign( 'rows', $rows );
		//$this->assign( 'form', $form );
	}
	
	
	/**
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	 function display($tpl=null, $perform = true) 
	{
		$layout = $this->getLayout();
	
		switch(strtolower($layout))
		{
			case "view":
				$this->validateUser();
				$this->_form($tpl);
			  break;
			  case "campaign_fund":
			  case "campaign":
				$this->_campaign($tpl);
			  break;
			 case "mycampaigns":
				 $this->_mycampaigns($tpl);	
				 break; 
			 case "campaignsfunded":
				 $this->_campaignsFunded($tpl);	
				 break; 			 
			case "form":
			case "campaign_charityform":
				$this->validateUser();
				$this->_form($tpl);
			  break;
			case "rules":
				Wepay::load('WepayHelperWepay','helpers.wepay');

				$wePayUser = WepayHelperWepay::canCreate();

				$msg = 'In order to create a project, you must create an account or login';
				$this -> validateUser($msg);
				
				$this->_default($tpl);
				break;
				case "campaign_checks":
				$this->_checks($tpl);
				break;

			case "stats":
			case "default":
			
			default:
				$this->_default($tpl);
			  break;
		}
		parent::display($tpl);
	}
	
/**
	 * Basic commands for displaying a list
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function _checks($tpl='', $onlyPagination = false )
	{
		$array = array();

		$array[] = $this->_checkImage($this->row);
		$array[] = $this->_checkDescription($this->row);
		JPluginHelper::importPlugin( 'wepay' );
		$dispatcher = JDispatcher::getInstance( );
		$result = $dispatcher->trigger( 'onPrepareCampaignChecks', array( $this->row ));
		$array[] = $result[0];

		$this->assign('checks', @$array);
		
	}

	function _checkImage($row, $json = NULL)
	{
		$object = new JObject();
		$object->title = 'Project Image';
		$object->status = false;
		$object->msg = '';

		if($row->campaign_full_image) {
 		$object->status = true;
 		$object->msg = 'Image Uploaded';
 		$object->edit_link = JRoute::_( 'index.php?option=com_wepay&view=campaigns&task=edit&id='.$row->campaign_id);;
		}

		return $object;
		
	}

	function _checkDescription($row, $json = NULL)
	{
		$object = new JObject();
		$object->title = 'Project Description';
		$object->status = false;
		$object->msg = '';

		if($row->campaign_description) {
 		$object->status = true;
 		$object->msg = 'Description is valid';
 		$object->edit_link = JRoute::_( 'index.php?option=com_wepay&view=campaigns&task=edit&id='.$row->campaign_id);;
		}

		return $object;
		
	}



	/**
	 * Basic commands for displaying a list
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function _default($tpl='', $onlyPagination = false )
	{
		Wepay::load( 'WepaySelect', 'library.select' );
		Wepay::load( 'WepayGrid', 'library.grid' );
		$model = $this->getModel();

		// set the model state
		$state = $model->getState();
		
		$model->setState('filter_group_states',1);
		JFilterOutput::objectHTMLSafe( $state );
		$this->assign( 'state', $state );

		// page-navigation
		$this->assign( 'pagination', $model->getPagination() );

		$items = $model->getList();
	
		  $this->assign('items', @$items);
		
		

		
		
	}
	 
	
	/**
	 * Basic methods for a form
	 * @param $tpl
	 * @return unknown_type
	 */
	function _form($tpl='')
	{
		$userid = $this->validateUser();
		$model = $this->getModel();
		if( isset( $this->row ) ) 
			JFilterOutput::objectHTMLSafe( $this->row );
		else
		{
	
			// get the data
			
			$row = $model->getItem();
			JFilterOutput::objectHTMLSafe( $row );
			$this->assign('row', $row );
		}

		// form
		$form = array();
		$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
		$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
		$task = strtolower( $this->get( '_task', 'save' ) );
		$form['action'] = $this->get( '_action', "index.php?option=com_wepay&controller={$controller}&view={$view}&task={$task}&id=".$model->getId() );
		$form['validation'] = $this->get( '_validation', "index.php?option=com_wepay&controller={$controller}&view={$view}&task=validate&format=raw" );
		$form['validate'] = "<input type='hidden' name='".JUtility::getToken()."' value='1' />";
		$form['id'] = $model->getId();
		$this->assign( 'form', $form );

		// set the required image
		// TODO Fix this
		$required = new stdClass();
		$required->text = JText::_('COM_TIENDA_REQUIRED');
		$required->image = WepayGrid::required( $required->text );
		$this->assign('required', $required );
		$this->assign('user_id', $userid );
	}

	function validateUser($msg = null) {
		if(empty($msg)) {
			$msg = 'You must login first';
		}
		$user = JFactory::getUser();
		$userId = $user -> get('id');
		if (!$userId) {
			$app = JFactory::getApplication();
			$return = JFactory::getURI() -> toString();
			$url = 'index.php?option=com_users&view=login';
			$url .= '&return=' . base64_encode($return);
			$app -> redirect($url, $msg);
			return false;
		}
		return $userId;
	}
	
	
}