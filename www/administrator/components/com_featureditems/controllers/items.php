<?php
/**
 * @version	1.5
 * @package	FeaturedItems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class FeaturedItemsControllerItems extends FeaturedItemsController
{
	/**
	 * constructor
	 */
	function __construct( )
	{
		parent::__construct( );
		
		$this->set( 'suffix', 'items' );
		
		$this->registerTask( 'item_enabled.enable', 'boolean' );
		$this->registerTask( 'item_enabled.disable', 'boolean' );
	}
	
	/**
	 * Sets the model's state
	 * 
	 * @return array()
	 */
	function _setModelState( )
	{
		$state = parent::_setModelState( );
		$app = JFactory::getApplication( );
		$model = $this->getModel( $this->get( 'suffix' ) );
		$ns = $this->getNamespace( );
		
		$state['filter_id_from'] = $app->getUserStateFromRequest( $ns . 'id_from', 'filter_id_from', '', '' );
		$state['filter_id_to'] = $app->getUserStateFromRequest( $ns . 'id_to', 'filter_id_to', '', '' );
		$state['filter_name'] = $app->getUserStateFromRequest( $ns . 'name', 'filter_name', '', '' );
		$state['filter_date_from'] = $app->getUserStateFromRequest( $ns . 'filter_date_from', 'filter_date_from', '', '' );
		$state['filter_date_to'] = $app->getUserStateFromRequest( $ns . 'filter_date_to', 'filter_date_to', '', '' );
		$state['filter_type'] = $app->getUserStateFromRequest( $ns . 'filter_type', 'filter_type', '', '' );
		$state['filter_label'] = $app->getUserStateFromRequest( $ns . 'filter_label', 'filter_label', '', '' );
		$state['filter_category'] = $app->getUserStateFromRequest( $ns . 'filter_category', 'filter_category', '', '' );
		$state['filter_enabled'] = $app->getUserStateFromRequest( $ns . 'filter_enabled', 'filter_enabled', '', '' );
		
		foreach ( @$state as $key => $value )
		{
			$model->setState( $key, $value );
		}
		return $state;
	}
	
	function save()
	{
	    if ($row = parent::save()) 
	    {
	        $row->item_description = JRequest::getVar( 'item_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
	        
	    	if (!empty($row->item_type))
	    	{
	    	    $key_id = $row->item_type . "_fk_id";
	    		$value = JRequest::getVar( $key_id, '', 'post' );
	    		$row->fk_id = $value;
	    	}
	    	
	    	$fieldname = 'item_image_local_new';
	    	$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
	    	if ( !empty( $userfile['size'] ) )
	    	{
	    	    if ( $upload = $this->addfile( $fieldname ) )
	    	    {
	    	        $row->item_image_local_filename = $upload->getPhysicalName( );
	    	        $row->item_image_local_path = 'images/com_featureditems/images/';
	    	    }
	    	}
	    	
	    	$row->store();
	    }
	}
	
	/**
	 * Adds a image to item
	 * @return unknown_type
	 */
	function addfile( $fieldname = 'item_image_local_new' )
	{
	    $upload = new DSCImage();
	    
	    $upload->handleUpload( $fieldname );

	    $upload->setDirectory( Featureditems::getPath( 'item_images' ) );

	    $upload->upload();
	
	    return $upload;
	}
}

?>