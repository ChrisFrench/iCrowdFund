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



class WepayTableCampaignProducts extends DSCTable

{
	/**
	 * Constructs the object
	 * @param $db
	 * @return unknown_type
	 */
	function WepayTableCampaignProducts ( &$db ) 
	{
		$tbl_key 	= 'id';
		$tbl_suffix = 'campaignproducts';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'wepay';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	/**
	 * Checks the integrity of the object before a save 
	 * @return unknown_type
	 */
	function check()
	{		
	/*	$db			= $this->getDBO();
		$nullDate	= $db->getNullDate();
		if (empty($this->created_date) || $this->created_date == $nullDate)
		{
			$date = JFactory::getDate();
			$this->created_date = $date->toMysql();
		}
		if (empty($this->modified_date) || $this->modified_date == $nullDate)
		{
			$date = JFactory::getDate();
			$this->modified_date = $date->toMysql();
		}
		$this->filterHTML( 'campaign_name' );
		if (empty($this->campaign_name))
		{
			$this->setError( JText::_('COM_TIENDA_NAME_REQUIRED') );
			return false;
		}
        jimport( 'joomla.filter.output' );
        if (empty($this->campaign_alias)) 
        {
            $this->campaign_alias = $this->campaign_name;
        }
        $this->campaign_alias = JFilterOutput::stringURLSafe($this->campaign_alias);
		*/
		return true;
	}
	
	/**
	 * Stores the object
	 * @param object
	 * @return boolean
	 */
	function store($updateNulls=false) 
	{
		//$date = JFactory::getDate();
		//$this->modified_date = $date->toMysql();
		$store = parent::store($updateNulls);		
		return $store;		
	}
	
	
}
