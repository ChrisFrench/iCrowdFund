<?php
/**
 * @package	Messagebottle
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class MessagebottleControllerDashboard extends MessagebottleController
{
	function __construct() 
	{
		parent::__construct();
		$this->set('suffix', 'dashboard');
	}

	function hooks() {
		$this->display();
	}

	function process() {

		//TODO this is a basic version for cron system
		$postedKey = JRequest::getVar('key', 'testingkey');
		$remotekey = Messagebottle::getInstance()->get('key','testingkey');


		if($postedKey === $remotekey) {

			
			$dispatcher = JDispatcher::getInstance();
			$reports = $dispatcher->trigger( 'OnMessageBottleRunCron');

			foreach($reports as $report) {
				echo $report;
				
			}
			
		
		    $postedEvents = JRequest::getVar('events', '', 'post','ARRAY');
			if(count($postedEvents)) {
				foreach($postedEvents as $event) {
					$dispatcher->trigger($event);
				}
			}
		} else {
			$error = 'API Keys do not match';
			echo $error;
		}



	}


}