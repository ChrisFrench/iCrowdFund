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

Tienda::load( 'TiendaViewBase', 'views._base', array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_tienda' ) );
Tienda::load( "TiendaHelperProduct", 'helpers.product' );
Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
if ( !class_exists('Wepay') ) 
    JLoader::register( "Wepay", JPATH_ADMINISTRATOR."/components/com_wepay/defines.php" );

class TiendaViewCampaigns extends TiendaViewBase
{
	
	
	/**
	 * Basic commands for displaying a campaign layout
	 *
	 * @param $tpl
	 * @return unknown_type
	 */
	function _campaign($tpl='')
	{
		Tienda::load( 'TiendaSelect', 'library.select' );
		Tienda::load( 'TiendaGrid', 'library.grid' );
		
		
		// form
		$validate = JUtility::getToken();
		$form = array();
		$view = strtolower( JRequest::getVar('view') );
		$form['action'] = "index.php?option=com_tienda&controller={$view}&view={$view}";
		$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
		$this->assign( 'form', $form );
	}
	
	function _mycampaigns($tpl='')
	{
		$userid = DSCAcl::validateUser();
		$model = $this->getModel();
		$model->emptyState();
		$model->setState('filter_user_id',$userid);
		$model->setState('filter_group_states','1');
		$rows = $model->getList();
		
		
		$this->assign( 'rows', $rows );
	}
	
	
	
	
	function _campaignsFunded($tpl='')
	{
		$userid = DSCAcl::validateUser();
		Tienda::load( 'TiendaModelCampaignBackers', 'models.campaignproducts' );
		$model = JModel::getInstance( 'CampaignBackers', 'TiendaModel' );
		$model->emptyState();
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
				DSCAcl::validateUser();
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
			 case "completed":
				 $this->_completed($tpl);	
				 break; 	 

			case "form":
			case "campaign_charityform":
			
				$type = JRequest::getVar('type', '1');
				$this->assign( 'type', $type );
				$this->validateUser();
				$this->_form($tpl);
			  break;
			case "rules":
				
				$msg = 'In order to create a project, you must create an account or login';
				$this->validateUser($msg);
				Wepay::load('WepayHelperWepay','helpers.wepay');

				$wePayUser = WepayHelperWepay::canCreate();

				
				if ( !class_exists('Tos') ) 
             	JLoader::register( "Tos", JPATH_ADMINISTRATOR."/components/com_tos/defines.php" );
				Tos::getClass('TosHelperTos','helpers.tos')->checkAccepted(2);
				$this->_campaign($tpl);
				break;
				case "campaign_checks":
				$this->_checks($tpl);
				break;
			case "order":
				$this->set("_task", "update_status");
				$this->_form($tpl);
				break;
				
			case "stats_orders":
			case "stats":
			case "default":
			
			default:
				$this->_default($tpl);
			  break;
		}
		parent::display($tpl);
	}
	
	function _completed($tpl='')
	{
		
		$model = $this->getModel();
		$model->emptyState();
		$model->setState('filter_completed','1');
		$rows = $model->getList();
		
		
		$this->assign( 'rows', $rows);
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
		$array[] = $this->_checkFundingGoal($this->row);
		$array[] = $this->_checkFundingLevels($this->row);
		JPluginHelper::importPlugin( 'tienda' );
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
		}
		$object->edit_link = JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);

		return $object;
		
	}

	function _checkFundingGoal($row, $json = NULL)
	{
		$object = new JObject();
		$object->title = 'Project Goal';
		$object->status = false;
		$object->msg = '';

		if($row->campaign_goal) {
 		$object->status = true;
 		$object->msg = 'Goal Set';
		}
		$object->edit_link = JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);

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
		}
		$object->edit_link = JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);

		return $object;
		
	}

	function _checkFundingLevels($row, $json = NULL)
	{
		$object = new JObject();
		$object->title = 'Funding Levels';
		$object->status = false;
		$object->msg = '';
		
		if($row->levels) {
 		$object->status = true;
 		$object->msg = 'You have added enough funding levels';
		}
		$object->edit_link = JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);

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
		Tienda::load( 'TiendaSelect', 'library.select' );
		Tienda::load( 'TiendaGrid', 'library.grid' );
		$model = $this->getModel();

		// set the model state
		$state = $model->getState();
		
		$model->setState('filter_group_states',1);
		//JFilterOutput::objectHTMLSafe( $state );
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
		$userid = DSCAcl::validateUser();
		$model = $this->getModel();
		if( isset( $this->row ) ) 
			$this->row;
		else
		{
	
			// get the data
			
			$row = $model->getItem();
	
			$this->assign('row', $row );
			$this->assign('type', $row->type); // otherwise, $this->type is always 1 
		}

		// form
		$form = array();
		$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
		$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
		$task = strtolower( $this->get( '_task', 'save' ) );
		$form['action'] = $this->get( '_action', "index.php?option=com_tienda&controller={$controller}&view={$view}&task={$task}&id=".$model->getId() );
		$form['validation'] = $this->get( '_validation', "index.php?option=com_tienda&controller={$controller}&view={$view}&task=validate&format=raw" );
		$form['validate'] = "<input type='hidden' name='".JUtility::getToken()."' value='1' />";
		$form['id'] = $model->getId();
		$this->assign( 'form', $form );

		// set the required image
		// TODO Fix this
		$required = new stdClass();
		$required->text = JText::_('COM_TIENDA_REQUIRED');
		$required->image = TiendaGrid::required( $required->text );
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
			$url = 'index.php?option=com_ambra&view=registration&Itemid=109';
			$url .= '&return=' . base64_encode($return);
			$url = JRoute::_($url,false);
			
			$app -> redirect($url, $msg);
			return false;
		}
		return $userId;
	}
	
	
}