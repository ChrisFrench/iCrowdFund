<?php
/**
 * @version 1.5
 * @package Billets
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class BilletsPluginBase extends DSCPlugin
{
    protected function _getLayout($layout, $vars = false, $plugin = '', $group = 'billets' )
    {
        return parent::_getLayout($layout, $vars, $plugin, $group);
    }
}