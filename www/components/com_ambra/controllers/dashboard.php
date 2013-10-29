<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerDashboard extends AmbraController
{
	/**
	 * constructor
	 */
	function __construct() 
	{
        
        DSCAcl::validateUser(JText::_('COM_AMBRA_REDIRECT_LOGIN'), 'index.php?option=com_ambra&view=login');
		
		parent::__construct();
		$this->set('suffix', 'dashboard');
	}
}