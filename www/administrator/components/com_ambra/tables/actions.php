<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Ambra::load( 'AmbraTable', 'tables._base' );

class AmbraTableActions extends AmbraTable 
{
	function AmbraTableActions ( &$db ) 
	{
		
		$tbl_key 	= 'action_id';
		$tbl_suffix = 'actions';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		$this->filterHTML( 'action_name' );
		if (empty($this->action_name))
		{
			$this->setError( JText::_( "Name Required" ) );
			return false;
		}
		return true;
	}
	
}
