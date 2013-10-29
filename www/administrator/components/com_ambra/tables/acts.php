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

class AmbraTableActs extends AmbraTable 
{
	function AmbraTableActs ( &$db ) 
	{
		
		$tbl_key 	= 'act_id';
		$tbl_suffix = 'acts';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
		$this->filterHTML( 'act_name' );
		if (empty($this->act_name))
		{
			$this->setError( JText::_( "Name Required" ) );
			return false;
		}
		return true;
	}
	
}
