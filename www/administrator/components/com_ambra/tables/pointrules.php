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

class AmbraTablePointRules extends AmbraTable 
{
	function AmbraTablePointRules ( &$db ) 
	{
		$tbl_key 	= 'pointrule_id';
		$tbl_suffix = 'pointrules';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		$this->filterHTML( 'pointrule_name' );
		if (empty($this->pointrule_name))
		{
			$this->setError( JText::_( "Name Required" ) );
			return false;
		}
		return true;
	}
	
}
