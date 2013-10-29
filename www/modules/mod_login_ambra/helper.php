<?php
/**
* @version		$Id: helper.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modLoginAmbraHelper extends JObject
{
	/**
	 * 
	 * @return 
	 * @param object $params
	 * @param object $type
	 */
	static public function getReturnURL($params, $type)
	{	


		//todo maybe this should be param
		 $return = JRequest::getVar('return', '', 'method', 'base64');
		if($return ) {
			return $return;
		}

		if($itemid =  $params->get($type))

		{	

			$app = JFactory::getApplication();
			$menu =  $app->getMenu();
			$item = $menu->getItem($itemid);
			
			$url = $item->link.'&Itemid='. $item->id;
			
			$url = JRoute::_($url);
			

		}
		else
		{
			// Redirect to login
			$uri = JFactory::getURI();
			$url = $uri->toString();
		}

		return base64_encode($url);
	}

}
