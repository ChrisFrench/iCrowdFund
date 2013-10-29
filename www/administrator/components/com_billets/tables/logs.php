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

Billets::load( 'BilletsTable', 'tables._base' );

class BilletsTableLogs extends BilletsTable 
{
	function BilletsTableLogs( &$db ) 
	{
		
		$tbl_key 	= 'log_id';
		$tbl_suffix = 'logs';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'billets';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	/**
	 * Checks the object's integrity before storing to the DB
	 * 
	 * @return unknown_type
	 */
	function check()
	{
	    $db         = $this->getDBO();
        $nullDate   = $db->getNullDate();
        if ( empty( $this->datetime ) || $this->datetime == $nullDate)
        {
            $date = JFactory::getDate();
            $this->datetime = $date->toMysql();
        }
        
		if ( empty( $this->user_id ) ) 
		{
			$this->setError( JText::_('COM_BILLETS_USER_ID_REQUIRED') );
			return false;
		}
		
        if ( empty( $this->object_id ) )
        {
            $this->setError( JText::_('COM_BILLETS_OBJECT_ID_REQUIRED') );
            return false;
        }
        	
	    if ( empty( $this->object_type ) )
        {
            $this->setError( JText::_('COM_BILLETS_OBJECT_TYPE_REQUIRED') );
            return false;
        }
        
		if ( empty( $this->property_name ) )
        {
            $this->setError( JText::_('COM_BILLETS_PROPERTY_NAME_REQUIRED') );
            return false;
        }
        
		if ( empty( $this->value_from ) )
        {
            $this->value_from = 'null';
        }
        
		if ( empty( $this->value_to ) )
        {
            $this->value_to = 'null';
        }
        
		if ( !strlen( $this->log_description ) )
        {
            $this->setError( JText::_('COM_BILLETS_LOG_DESCRIPTION_REQUIRED') );
            return false;
        }
        
		return true;
	}

}
