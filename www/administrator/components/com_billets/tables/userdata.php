<?php
/**
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Billets::load( 'BilletsTable', 'tables._base' );

class BilletsTableUserdata extends BilletsTable 
{
	function BilletsTableUserdata ( &$db ) 
	{
		$tbl_key 	= 'userdata_id';
		$tbl_suffix = 'userdata';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "billets";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
	    if (empty($this->user_id))
	    {
	        $this->setError( 'User ID Required' );
	        return false;
	    }
	    
		return true;
	}
	
	function save()
	{
	    if (!empty($this->_isNew))
	    {
	        $config = Billets::getInstance();
            $this->limit_tickets = $config->get('limit_tickets_globally');
            $this->ticket_max = $config->get('default_max_tickets');
            $this->limit_hours = $config->get('limit_hours_globally');
            $this->hour_max = $config->get('default_max_hours');
            
            JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models');
            $model = JModel::getInstance('Tickets', 'BilletsModel');
            $model->setState( 'select', 'COUNT(tbl.id)' );
            $model->setState( 'filter_userid', $this->user_id );
            $this->ticket_count = $model->getResult();

            $model = JModel::getInstance( 'Tickets', 'BilletsModel' );
            $model->setState( 'select', 'SUM(tbl.hours_spent)' );
            $model->setState( 'filter_userid', $this->user_id );
            $this->hour_count = $model->getResult();
	    }
	    
	    return parent::save();
	}
	
}
