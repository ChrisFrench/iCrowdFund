<?php
/**
 * @package Wepay
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class WepayHelperRoute extends DSCHelperRoute 
{
    static $itemids = null;
    
    public static function getItems( $option='com_wepay' )
    {
        parent::getItems($option);        
    }
}