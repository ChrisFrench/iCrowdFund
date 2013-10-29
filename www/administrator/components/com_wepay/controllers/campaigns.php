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
defined( '_JEXEC' ) or die( 'Restricted access' );

class WepayControllerCampaigns extends WepayController
{
	/**
	 * constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->set('suffix', 'campaigns');
		$this->registerTask( 'campaign_enabled.enable', 'boolean' );
		$this->registerTask( 'campaign_enabled.disable', 'boolean' );
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'selected_disable', 'selected_switch' );
		$this->registerTask( 'saveprev', 'save' );
		$this->registerTask( 'savenext', 'save' );
	}

	/**
	 * Sets the model's state
	 *
	 * @return array()
	 */
	function _setModelState()
	{
		$state = parent::_setModelState();
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
		$ns = $this->getNamespace();
	//	$state['order']             = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.lft', 'cmd');
		$state['filter_id_from'] 	= $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
		$state['filter_id_to'] 		= $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
		$state['filter_name'] 		= $app->getUserStateFromRequest($ns.'name', 'filter_name', '', '');
		$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
		$state['filter_id_set'] 	= $app->getUserStateFromRequest($ns.'id_set', 'filter_id_set', '', '');
		
		foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );
		}
		return $state;
	}

   
	
	/**
	 * Saves an item and redirects based on task
	 * @return void
	 */
	function save()
	{
		$task = JRequest::getVar('task');
		$model 	= $this->getModel( $this->get('suffix') );
        $error=false;
		$row = $model->getTable();
		$row->load( $model->getId() );
		$row->bind( JRequest::get('POST') );
		$row->campaign_description = JRequest::getVar( 'campaign_description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$row->campaign_shortdescription = JRequest::getVar( 'campaign_shortdescription', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		$fieldname = 'campaign_full_image_new';
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if (!empty($userfile['size']))
		{
			if ($upload = $this->addfile( $fieldname, $row ))
			{
				$row->campaign_full_image = $upload->getPhysicalName();
			}
			else
			{
				$error = true;
			}
		}
		
		if ( $row->save() )
		{
			$model->setId( $row->id );
			$this->messagetype 	= 'message';
			$this->message  	= JText::_('COM_TIENDA_SAVED');
			if ($error)
			{
				$this->messagetype 	= 'notice';
				$this->message .= " :: ".$this->getError();
			}

			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
		}
		else
		{
			$this->messagetype 	= 'notice';
			$this->message 		= JText::_('COM_TIENDA_SAVE_FAILED')." - ".$row->getError();
		}

		$redirect = "index.php?option=com_wepay";

		switch ($task)
		{
			case "saveprev":
				$redirect .= '&view='.$this->get('suffix');
				// get prev in list
				Wepay::load( 'WepayHelperCampaign', 'helpers.campaign' );
				$surrounding = WepayHelperCampaign::getSurrounding( $model->getId() );
				if (!empty($surrounding['prev']))
				{
					$redirect .= '&task=edit&id='.$surrounding['prev'];
				}
				break;
			case "savenext":
				$redirect .= '&view='.$this->get('suffix');
				// get next in list
				Wepay::load( 'WepayHelperCampaign', 'helpers.campaign' );
				$surrounding = WepayHelperCampaign::getSurrounding( $model->getId() );
				if (!empty($surrounding['next']))
				{
					$redirect .= '&task=edit&id='.$surrounding['next'];
				}
				break;
			case "savenew":
				$redirect .= '&view='.$this->get('suffix').'&task=add';
				break;
			case "apply":
				$redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$model->getId();
				break;
			case "save":
			default:
				$redirect .= "&view=".$this->get('suffix');
				break;
		}

		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}



	/**
	 * Adds a thumbnail image to item
	 * @return unknown_type
	 */
	 /**
	 * Adds a image to item
	 * @return unknown_type
	 */
	function addfile( $fieldname = 'campaign_full_image_new', $row )
	{
	    $upload = new DSCImage();
	    
	    $upload->handleUpload( $fieldname );

	    $upload->setDirectory( Wepay::getPath('media'). '/campaigns/images/'.$row->campaign_id.'/' );

	    $upload->upload();
		
		// Thumb
		Wepay::load( 'WepayHelperImage', 'helpers.image' );
		$imgHelper = WepayHelperBase::getInstance('Image', 'WepayHelper');
		$options = array();
		$options['width'] = '';
		$options['height'] = '';
		$options['thumb_path'] = '';
		
		if (!$imgHelper->resizeImage( $upload, 'manufacturer'))
		{
		    JFactory::getApplication()->enqueueMessage( $imgHelper->getError(), 'notice' );
		}
	
	    return $upload;
	}
	 
	/**
	 * Loads view for assigning products to campaigns
	 *
	 * @return unknown_type
	 */
	function selectproducts()
	{
		$this->set('suffix', 'products');
		$state = parent::_setModelState();
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
		$ns = $this->getNamespace();

		foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );
		}

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'campaigns' );
		$row->load( $id );

		$view   = $this->getView( 'campaigns', 'html' );
		$view->set( '_controller', 'campaigns' );
		$view->set( '_view', 'campaigns' );
		$view->set( '_action', "index.php?option=com_tienda&controller=campaigns&task=selectproducts&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectproducts' );
		$view->setTask(true);
		$view->display();
	}

	/**
	 *
	 * @return unknown_type
	 */
	function selected_switch()
	{
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';

		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'request', 'array');
		$task = JRequest::getVar( 'task' );
		$vals = explode('_', $task);

		$field = $vals['0'];
		$action = $vals['1'];

		switch (strtolower($action))
		{
			case "switch":
				$switch = '1';
				break;
			case "disable":
				$enable = '0';
				$switch = '0';
				break;
			case "enable":
				$enable = '1';
				$switch = '0';
				break;
			default:
				$this->messagetype  = 'notice';
				$this->message      = JText::_('COM_TIENDA_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
				break;
		}

		$keynames = array();
		foreach (@$cids as $cid)
		{
			$table = JTable::getInstance('ProductCategories', 'WepayTable');
			$keynames["campaign_id"] = $id;
			$keynames["product_id"] = $cid;
			$table->load( $keynames );
			if ($switch)
			{
				if (isset($table->product_id))
				{
					if (!$table->delete())
					{
						$this->message .= $cid.': '.$table->getError().'<br/>';
						$this->messagetype = 'notice';
						$error = true;
					}
				}
				else
				{
					$table->product_id = $cid;
					$table->campaign_id = $id;
					if (!$table->save())
					{
						$this->message .= $cid.': '.$table->getError().'<br/>';
						$this->messagetype = 'notice';
						$error = true;
					}
				}
			}
			else
			{
				switch ($enable)
				{
					case "1":
						$table->product_id = $cid;
						$table->campaign_id = $id;
						if (!$table->save())
						{
							$this->message .= $cid.': '.$table->getError().'<br/>';
							$this->messagetype = 'notice';
							$error = true;
						}
						break;
					case "0":
					default:
						if (!$table->delete())
						{
							$this->message .= $cid.': '.$table->getError().'<br/>';
							$this->messagetype = 'notice';
							$error = true;
						}
						break;
				}
			}
		}

		$model->clearCache();

		if ($error)
		{
			$this->message = JText::_('COM_TIENDA_ERROR') . ": " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = JRequest::getVar( 'return' ) ?
		base64_decode( JRequest::getVar( 'return' ) ) : "index.php?option=com_tienda&controller=campaigns&task=selectproducts&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}

	/**
	 * Batch resize of thumbs
	 * @author Skullbock
	 */
	function recreateThumbs(){
			
		$per_step = 100;
		$from_id = JRequest::getInt('from_id', 0);
		$to =  $from_id + $per_step;
			
		Wepay::load( 'WepayHelperCampaign', 'helpers.campaign' );
		Wepay::load( 'WepayImage', 'library.image' );
		$width = Wepay::getInstance()->get('campaign_img_width', '0');
		$height = Wepay::getInstance()->get('campaign_img_height', '0');

		$model = $this->getModel('Categories', 'WepayModel');
		$model->setState('limistart', $from_id);
		$model->setState('limit', $to);
			
		$row = $model->getTable();
			
		$count = $model->getTotal();
			
		$campaigns = $model->getList();
			
		$i = 0;
		$last_id = $from_id;
		foreach($campaigns as $p){
			$i++;
			$image = $p->campaign_full_image;
			$path = Wepay::getPath('media'). '/campaigns/images/';

			if($image != ''){
					
				$img = new WepayImage($path.'/'.$image);
				$img->setDirectory( Wepay::getPath('media'). '/campaigns/images/');

				// Thumb
				Wepay::load( 'WepayHelperImage', 'helpers.image' );
				$imgHelper = WepayHelperBase::getInstance('Image', 'WepayHelper');
				$imgHelper->resizeImage( $img, 'campaign');
			}

			$last_id = $p->campaign_id;
		}
			
		if($i < $count)
		$redirect = "index.php?option=com_tienda&controller=campaigns&task=recreateThumbs&from_id=".($last_id+1);
		else
		$redirect = "index.php?option=com_tienda&view=config";
			
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, JText::_('COM_TIENDA_DONE'), 'notice' );
		return;
	}
}

?>