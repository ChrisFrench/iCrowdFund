<?php
/**
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );


class WepayTableScopes extends DSCTable 
{
	function WepayTableScopes( &$db ) 
	{
		$tbl_key 	= 'scope_id';
		$tbl_suffix = 'scopes';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'wepay';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if (empty($this->scope_name))
		{
			$this->setError( JText::_( "Scope Name Required" ) );
			return false;
		}
		
	    if (empty($this->scope_identifier))
        {
            $this->setError( JText::_( "Scope Identifier Required" ) );
            return false;
        }
		return true;
	}
	
	function delete( $oid = null )
	{
	    $scope_id = !empty($oid) ? $oid : $this->scope_id;
	     
	    if ($return = parent::delete( $oid ))
	    {
	        $query = "DELETE FROM #__wepay_relationships WHERE `scope_id` = '$scope_id';";
	        $db = $this->getDBO();
	        $db->setQuery($query);
	        $db->query();
	    }
	
	    return $return;
	}
}
