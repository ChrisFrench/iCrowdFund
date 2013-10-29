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

Messagebottle::load('MessagebottleViewBase', 'views.base');

class MessagebottleViewScopes extends MessagebottleViewBase 
{
	
	function _defaultToolbar()
	{
	
        JToolBarHelper::publishList( 'enabled.enable' );
        JToolBarHelper::unpublishList( 'enabled.disable' );
		// add the core ACL options button only if access allows them to
			if (JFactory::getUser()->authorise('core.admin', 'com_messagebottle')) {
			    JToolBarHelper::preferences('com_messagebottle');
			}
		parent::_defaultToolbar();
	}
}
