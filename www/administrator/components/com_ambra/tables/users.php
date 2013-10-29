<?php
/**
 * @version 1.5
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
 
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Ambra::load( 'AmbraTable', 'tables._base' );

class AmbraTableUsers extends AmbraTable 
{
    function AmbraTableUsers( &$db ) 
    {
        $tbl_key    = 'id';
        $tbl_suffix = 'users';
        $this->set( '_suffix', $tbl_suffix );
        
        parent::__construct( "#__{$tbl_suffix}", $tbl_key, $db );   
    }
}