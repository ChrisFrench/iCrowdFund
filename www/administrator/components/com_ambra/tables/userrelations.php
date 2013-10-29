<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Ambra::load( 'AmbraTable', 'tables._base' );

class AmbraTableUserRelations extends AmbraTable 
{
	function AmbraTableUserRelations( &$db ) 
    {
        $tbl_key    = 'userrelation_id';
        $tbl_suffix = 'userrelations';
        $this->set( '_suffix', $tbl_suffix );
        $name       = 'ambra';
        
        parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );   
    }
	
	function check()
	{
		if (empty($this->user_id_from))
		{
			$this->setError( JText::_( "User From Required" ) );
			return false;
		}

		if (empty($this->user_id_to))
        {
            $this->setError( JText::_( "User To Required" ) );
            return false;
        }
        
	    if (empty($this->relation_type))
        {
            $this->setError( JText::_( "Relation Type Required" ) );
            return false;
        }
		
		return true;
	}
}