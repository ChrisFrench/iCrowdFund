<?php
/**
 * @package     Joomla.CLI
 * @subpackage  com_tienda
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Make sure we're being called from the command line, not a web interface
if (array_key_exists('REQUEST_METHOD', $_SERVER)) die();

/**
 * Finder CLI Bootstrap
 *
 * Run the framework bootstrap with a couple of mods based on the script's needs
 */

// We are a valid entry point.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

// Load system defines
if (file_exists(dirname(dirname(dirname(__FILE__))) . '/defines.php'))
{
	require_once dirname(dirname(dirname(__FILE__))) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(dirname(dirname(__FILE__))));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Force library to be in JError legacy mode
JError::$legacy = true;

// Import necessary classes not handled by the autoloaders
jimport('joomla.application.application');
jimport('joomla.application.menu');
jimport('joomla.environment.uri');
jimport('joomla.environment.request');

jimport('joomla.event.dispatcher');
jimport('joomla.utilities.utility');
jimport('joomla.utilities.arrayhelper');

// Import the configuration.
require_once JPATH_CONFIGURATION . '/configuration.php';

// System configuration.
$config = new JConfig;


define('JDEBUG', $config->debug);


// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Library language
$lang = JFactory::getLanguage();

// Try the tienda_cli file in the current language (without allowing the loading of the file in the default language)
$lang->load('tienda_cli', JPATH_SITE, null, false, false)
// Fallback to the tienda_cli file in the default language
|| $lang->load('tienda_cli', JPATH_SITE, null, true);

// Since we are loading Tienda PLugins we need to define the library. 
if (!class_exists('DSC'))
        {
           
            require_once JPATH_SITE.'/libraries/dioscouri/dioscouri.php';
        }

        DSC::loadLibrary();

// Since we are loading Tienda PLugins we need to define the Tienda class. 
if ( !class_exists( 'Tienda' ) ) {
    JLoader::register( "Tienda", JPATH_ADMINISTRATOR . "/components/com_tienda/defines.php" );
}
/**
 * A command line cron job to run the Finder indexer.
 *
 * @package     Joomla.CLI
 * @subpackage  com_finder
 * @since       2.5
 */
class TiendaCli extends JApplicationCli
{
	/**
	 * Start time for the index process
	 *
	 * @var    string
	 * @since  2.5
	 */
	private $_time = null;

	/**
	 * Start time for each batch
	 *
	 * @var    string
	 * @since  2.5
	 */
	private $_qtime = null;

	/**
	 * Entry point for Finder CLI script
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function doExecute()
	{
		// Print a blank line.
		$this->out(JText::_('TIENDA_CLI'));
		$this->out('============================');
		$this->out();

		$this->_runplugins();

		// Print a blank line at the end.
		$this->out();
	}

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

		// import library dependencies we need this to run the DSC plugin
		JPluginHelper::importPlugin('system');
		JDispatcher::getInstance()->trigger('onAfterInitialise');


		jimport('joomla.application.component.helper');

		// fool the system into thinking we are running as JSite with Tienda as the active component
		JFactory::getApplication('site');
		$_SERVER['HTTP_HOST'] = 'domain.com';
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_tienda');

		// Disable caching.
		$config = JFactory::getConfig();
		$config->set('caching', 0);
		$config->set('cache_handler', 'file');

		// Reset the indexer state.

		JPluginHelper::importPlugin('tienda');

		$this->out(JText::_('TIENDA_CLI_STARTING_CRON'), true);

		JDispatcher::getInstance()->trigger('onStartCron', $this);

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

