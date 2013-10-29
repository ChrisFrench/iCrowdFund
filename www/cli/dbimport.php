<?php 
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

// Load system defines
if (file_exists(dirname(dirname(__FILE__)) . '/defines.php'))
{
	require_once dirname(dirname(__FILE__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(dirname(__FILE__)));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Force library to be in JError legacy mode
JError::$legacy = true;

// Import necessary classes not handled by the autoloaders
/*jimport('joomla.application.menu');
jimport('joomla.environment.uri');
jimport('joomla.event.dispatcher');
jimport('joomla.utilities.utility');
jimport('joomla.utilities.arrayhelper');
*/
// Import the configuration.
require_once JPATH_CONFIGURATION . '/configuration.php';





class DBImport extends JApplicationCli
{
	public function doExecute()
	{
		// Print a blank line.
		$this->out(JText::_('IMPORTING SQL'));
		$this->out('============================');
		$this->out();
		
		$this->_import();

		// Print a blank line at the end.
		$this->out();
	}
	
	function _import() {
			
		$config = new JConfig;
	
		$path = JPATH_BASE;
		$file = $this->input->get('file');
		$command = "mysql -u {$config->user} -p{$config->password} {$config->db} < {$file}";
		
		$this->out($command);
		exec($command);
		
	}
	
	
	
}

JApplicationCli::getInstance('DBImport')->execute();
	



?>