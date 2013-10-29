<?php
/**
 * @version	1.5
 * @package	Messagebottle
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
 
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class MessagebottleTableEmails extends DSCTable 
{
	public function MessagebottleTableEmails( &$db ) 
	{
		$tbl_key 	= 'email_id';
		$tbl_suffix = 'emails';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "messagebottle";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}

	function check( )
	{
		$db = $this->getDBO( );
		$date = JFactory::getDate( );
		$nullDate = $db->getNullDate( );
		//you need to have a id or an email to store the email
		if(empty($this->receiver_id) && empty($this->receiver_email)) {
			return false;
		}


		if ( empty( $this->datecreated ) || $this->datecreated == $nullDate )
		{
			
			$this->datecreated = $date->toMysql( );
		}
		
			$this->datemodified = $date->toMysql( );





		return true;
	} 

}