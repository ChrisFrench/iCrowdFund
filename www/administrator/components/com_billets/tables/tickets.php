<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Billets::load( 'BilletsTable', 'tables._base' );	

class BilletsTableTickets extends BilletsTable 
{
	function BilletsTableTickets ( &$db ) 
	{
		
		$tbl_key 	= 'id';
		$tbl_suffix = 'tickets';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		$this->setLogged();
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{		
		$db			= JFactory::getDBO();
		$nullDate	= $db->getNullDate();
		if (empty($this->created_datetime) || $this->created_datetime == $nullDate)
		{
			$date = JFactory::getDate();
			$this->created_datetime = $date->toMysql();
		}
		if (empty($this->last_modified_datetime) || $this->last_modified_datetime == $nullDate)
		{
			$date = JFactory::getDate();
			$this->last_modified_datetime = $date->toMysql();
		}
		if (empty($this->stateid))
		{
			$config = Billets::getInstance();
			$this->stateid = $config->get( 'state_new', '1' );
		}
		if (empty($this->sender_userid))
		{
			$this->setError( JText::_('COM_BILLETS_USER_REQUIRED') );
			return false;
		}
		
		if (empty($this->title))
		{
			$this->setError( JText::_('COM_BILLETS_SUBJECT_REQUIRED') );
			return false;
		}
    		else
		{
		    $this->title = strip_tags( $this->title );
		}
		
		if (empty($this->description))
		{
			$this->setError( JText::_('COM_BILLETS_DESCRIPTION_REQUIRED') );
			return false;
		}
		
		if (empty($this->categoryid))
		{
			$this->setError( JText::_('COM_BILLETS_CATEGORY_REQUIRED') );
			return false;
		}
		
		// TODO Perform the ticketdata check() method here too
		
		return true;
	}
	
	/**
	 * Stores the ticket and saves the ticketdata
	 *  
	 * @see billets/admin/tables/BilletsTable#store($updateNulls)
	 */
	function store( $updateNulls=false )
	{
		// after storing the ticket's core data, store the extra data
		$result = parent::store($updateNulls);

		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$ticketdata = JTable::getInstance( 'Ticketdata', 'BilletsTable' );
		$ticketdata->load( array('ticketid'=>$this->id) );
		$values = JRequest::getVar( 'ticketdata', array(0), 'post', 'array' );
		$ticketdata->bind( $values );
		$ticketdata->ticketid = $this->id;
		$ticketdata->_categoryid = $this->categoryid;
		if (!$result = $ticketdata->store()) // only a store, not a full save; this method assumes that check() has already been done
		{
			$this->setError( $ticketdata->getError() );			
		}

		return $result;
	}
	
	function delete( $oid=null )
	{
		
		//get the file for our file manager class
	
		Billets::load( 'BilletsFile', 'library.file' );
		
		//Remove any attachments before removing the ticket
		$file = new BilletsFile();
		
		if (!$file->removeUploads($oid))
		{
        	$this->setError( JText::_('COM_BILLETS_ERROR_DELETING_ATTACHMENTS'));
        	return false;			
		}						
		
		//call the overridden parent delete function
		$return = parent::delete($oid);

		return $return;
		
	}
}
