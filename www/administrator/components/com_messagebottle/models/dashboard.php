<?php
/**
 * @version	1.5
 * @package	Messagebottle
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Messagebottle::load('MessagebottleModelBase','models.base');

class MessagebottleModelDashboard extends MessagebottleModelBase 
{
	function getTable($name='Config', $prefix='MessagebottleTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}