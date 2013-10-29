<?php
/**
 * @version 1.5
 * @package Ambra
 * @user  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
if ( !class_exists('Ambra') ) 
    	 JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );

class AmbraModelElementUser extends DSCModelElement
{
    var $title_key = 'name';
    var $select_title_constant = 'COM_AMBRA_SELECT_USER';
    var $select_constant = 'COM_AMBRA_SELECT';
    var $clear_constant = 'COM_AMBRA_CLEAR_SELECTION';
    
    function getTable($name='', $prefix=null, $options = array())
    {
        $table = JTable::getInstance('User', 'DSCTable');
        return $table;
    }
    

    
}
?>
