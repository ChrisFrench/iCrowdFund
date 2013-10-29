<?php
/**
 * @package	FeaturedItems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class FeaturedItemsTableItems extends DSCTable
{
	public function FeaturedItemsTableItems( &$db )
	{
		$tbl_key = 'item_id';
		$tbl_suffix = 'items';
		$this->set( '_suffix', $tbl_suffix );
		$name = "featureditems";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );
	}
	
	public function check()
	{
		if ( empty( $this->publish_up ) || $this->publish_up == '0000-00-00' )
		{
		    $date = JFactory::getDate();
			$this->publish_up = $date->toFormat( '%Y-%m-%d' );
		}
		
		if (empty($this->ordering))
		{
		    $this->ordering = $this->getNextOrder();
		}
		
		if (empty($this->item_type))
		{
		    $this->item_type = 'default';
		}
		
		return true;
	}
	
	public function isPublished( $id=null )
	{
	    if (!empty($id))
	    {
	        $this->load( $id );
	    }
	    
	    $date = JFactory::getDate();
	    $today = $date->toFormat( '%Y-%m-%d' );
	    
	    if ($today < $this->publish_up)
	    {
	        return false;
	    }
	    
	    if ($today > $this->publish_down && $this->publish_down != '0000-00-00')
	    {
	        return false;
	    }
	    
	    return true;
	    
	}
}
