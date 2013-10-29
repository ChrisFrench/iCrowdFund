<?php
/**
 * @package	Terms of Service
 * @author 	Ammonite Networks
 * @link 	http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosModelElementArticle extends DSCModelElement
{
    var $title_key = 'title';
    var $select_title_constant = 'COM_TOS_SELECT_ARTICLE';
    var $select_constant = 'COM_TOS_SELECT';
    var $clear_constant = 'COM_TOS_CLEAR_SELECTION';
    
    function getTable($name='', $prefix=null, $options = array())
    {
        $table = JTable::getInstance('Content', 'DSCTable');
        return $table;
    }
}
?>
