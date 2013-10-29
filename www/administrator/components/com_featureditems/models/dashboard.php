<?php
/**
 * @version	1.5
 * @package	Featureditems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Featureditems::load('FeatureditemsModelBase','models.base');

class FeatureditemsModelDashboard extends FeatureditemsModelBase 
{
	function getTable($name='Config', $prefix='FeatureditemsTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}