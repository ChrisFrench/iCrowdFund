<?php
/**
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class WepayControllerDashboard extends WepayController
{
	function __construct() 
	{
		DSCAcl::validateUser($msg = 'You must be Logged in to create a wepay account');	
		parent::__construct();
		$this->set('suffix', 'dashboard');
	}
	
	
	
	
	
	
}