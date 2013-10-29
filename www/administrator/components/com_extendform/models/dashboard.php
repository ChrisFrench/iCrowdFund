<?php
/**
 * @version	1.5
 * @package	Extendform
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Extendform::load('ExtendformModelBase','models.base');

class ExtendformModelDashboard extends ExtendformModelBase 
{
	function getTable($name='Config', $prefix='ExtendformTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}