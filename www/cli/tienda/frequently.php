<?php
/**
 * @package     Joomla.CLI
 * @subpackage  com_tienda
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once('base.php');


class FrequentTiendaCli extends TiendaCli {

	/**
	 * Run the indexer
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	 function _runplugins()
	{
		
		// initialize the time value
		$this->_time = microtime(true);

		// import library dependencies
		jimport('joomla.application.component.helper');

		// fool the system into thinking we are running as JSite with Tienda as the active component
		JFactory::getApplication('site');
		$_SERVER['HTTP_HOST'] = 'domain.com';
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_tienda');

		// Disable caching.
		$config = JFactory::getConfig();
		$config->set('caching', 0);
		$config->set('cache_handler', 'file');
		
		// Import the finder plugins.
		JPluginHelper::importPlugin('tienda');

		// Starting Indexer.
		$this->out(JText::_('TIENDA_CLI_STARTING_CRON'), true);

	
		JDispatcher::getInstance()->trigger('onStartCron');

		// Remove the script time limit.
		@set_time_limit(0);

		// Get the indexer state.

		// Setting up plugins.
		$this->out(JText::_('TIENDA_CLI_SETTING_UP_PLUGINS'), true);

		JDispatcher::getInstance()->trigger('onProcessCron');

		JDispatcher::getInstance()->trigger('onAfterCron');

		// Total reporting.
		$this->out(JText::sprintf('TIENDA_CLI_PROCESS_COMPLETE', round(microtime(true) - $this->_time, 3)), true);

	}
}

JApplicationCli::getInstance('FrequentTiendaCli')->execute();