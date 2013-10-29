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

class AmbraTableUserdata extends AmbraTable 
{
	function AmbraTableUserdata ( &$db ) 
	{
		
		$tbl_key 	= 'userdata_id';
		$tbl_suffix = 'userdata';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
	    if (empty($this->user_id))
        {
            $this->setError( JText::_( "User Required" ) );
            return false;
        }
		return true;
	}

	function save($isNew=false)
    {
        if ($isNew)
        {
            if (!isset($this->points_maximum))
            {
                $this->points_maximum = AmbraConfig::getInstance()->get('max_total_points', '-1');
            }    
        }
        return parent::save();
    }
}
