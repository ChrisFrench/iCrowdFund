<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load( 'AmbraModelBase', 'models._base' );

class AmbraModelDashboard extends AmbraModelBase 
{
	public function getTable($name='Config', $prefix='AmbraTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }
}