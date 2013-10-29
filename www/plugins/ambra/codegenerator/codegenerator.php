<?php
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

/**
 * @version	1.5
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
class plgAmbraCodeGenerator extends JPlugin {

	var $_element   = 'codegenerator'; 
	
	/**
	 * Constructor 
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}

	/**
	 * Auto-generating the code
	 * 
	 * @param void
	 * @return string generated code
	 */
	function generate_code()
	{	
		$code = '';
		$code_length = $this->params->get('code_length');
			
		$chars = array('0','1','2','3','4','5','6','7','8','9',
		               'A','B','C','D','E','F','G','H','I','J',
		               'K','L','M','N','O','P','Q','R','S','T',
					   'U','V','W','X','Y','Z'); 
		
		for( $i=0;$i<$code_length;$i++)
		{
			$random = mt_rand(0,35);
			$code .= $chars[$random];
		}
		
		return $code;
	}
}
	