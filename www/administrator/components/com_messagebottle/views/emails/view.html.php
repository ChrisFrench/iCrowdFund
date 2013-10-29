<?php
/**
* @package		Messagebottle
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Messagebottle::load('MessagebottleViewBase','views.base');

class MessagebottleViewEmails extends MessagebottleViewBase 
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
	