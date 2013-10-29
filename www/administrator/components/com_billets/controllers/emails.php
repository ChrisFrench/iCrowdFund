<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2011 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class BilletsControllerEmails extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'emails');
	}

	/**
	 * Overriding the method because we use a slightly diff getItem in the emails model
	 * (non-PHPdoc)
	 * @see tienda/admin/TiendaController::edit()
	 */
	function edit()
	{
        $id = JRequest::getVar('id', 'en-GB');
        
        $view   = $this->getView( $this->get('suffix'), 'html' );
        $model  = $this->getModel( $this->get('suffix') );
        
        $model->setId( $id );
        $row = $model->getItem( $id );
        
        JRequest::setVar( 'hidemainmenu', '1' );
        $view->setLayout( 'form' );
        $view->setModel( $model, true );
        $view->assign( 'row', $row );
		$view->setTask( true );
        $model->emptyState();
        $this->_setModelState();
        //$surrounding = $model->getSurrounding( $model->getId() );
        //$view->assign( 'surrounding', $surrounding );

        $view->display();
        $this->footer();
        return;
	}
	
	/**
	 * Save method is diff here because we're writing to a file
	 * (non-PHPdoc)
	 * @see tienda/admin/TiendaController::save()
	 */
	function save()
	{
		$id = JRequest::getVar('id', 'en-GB');
		$temp_values = JRequest::get('post', '4');
		
		$model = $this->getModel('Emails', 'BilletsModel');
		
		// Filter values
		$prefix = $model->email_prefix;
		$values = array();
		foreach($temp_values as $k =>$v){
			if(stripos($k, $prefix) === 0)
				$values[$k] = $v;
		}
		
		
		$lang = $model->getItem( $id );
		$path = $lang->path;
		
		$msg = JText::_('COM_BILLETS_SAVED');
		
		jimport('joomla.filesystem.file');

		if (JFile::exists($path))
		{
			$original = new JRegistry();
			$original->loadFile($path);
			
			$registry = new JRegistry();
			$registry->loadArray($values);
			
			$original->merge($registry);
			
			$txt = $original->toString('INI');
			
			$success = JFile::write($path, $txt);

			if(!$success)
				$msg = JText::_('COM_BILLETS_ERROR_SAVING_THE_NEW_LANGUAGE_FILE');
				
		}

		$task = JRequest::getVar('task');
        $redirect = "index.php?option=com_billets";
            
        switch ($task)
        {
            case "apply":
                $redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$id;
                break;
            case "save":
            default:
                $redirect .= "&view=".$this->get('suffix');
                break;
        }

        $redirect = JRoute::_( $redirect, false );
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
}
?>