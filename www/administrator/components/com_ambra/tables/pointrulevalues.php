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

class AmbraTablePointRuleValues extends AmbraTable 
{
	function AmbraTablePointRuleValues ( &$db ) 
	{
		$tbl_key 	= 'pointrulevalue_id';
		$tbl_suffix = 'pointrulevalues';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		if (empty($this->pointrule_id))
		{
			$this->setError( JText::_( "Rule Required" ) );
			return false;
		}
	    if (empty($this->pointrulevalue_key))
        {
            $this->setError( JText::_( "Key Required" ) );
            return false;
        }
	    if (empty($this->pointrulevalue_keyvalue))
        {
            $this->setError( JText::_( "Key Value Required" ) );
            return false;
        }
		return true;
	}
	
}
