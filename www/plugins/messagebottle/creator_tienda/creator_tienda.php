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


class plgMessagebottleCreator_tienda extends JPlugin {
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element = 'creator_tienda';
	

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$language = JFactory::getLanguage();
		$language -> load('plg_messagebottle_' . $this -> _element, JPATH_ADMINISTRATOR, 'en-GB', true);
		$language -> load('plg_messagebottle_' . $this -> _element, JPATH_ADMINISTRATOR, null, true);
		  		

	}

	function OnMessageBottleRunCron() {
		$this->onProcessCreateEmailsHourly();
		$this->onProcessCreateEmailsDaily();
		$this->onProcessCreateEmailsWeekly();
		$this->onProcessCreateEmailOnCampaignEnd();
	}

	function onProcessCreateEmailsHourly() {
		$this->doEndingInOneDayEmails();
	}

	function onProcessCreateEmailsDaily() {
		
	}
	function onProcessCreateEmailsWeekly() {
		$this->doEndingInWeekEmails();
	}

	function onProcessCreateEmailOnCampaignEnd() {
		$this->doFollowersEndingCampaignEnd();
	}




	function doEndingInOneDayEmails() {
		// Get a db connection.
		$db = JFactory::getDbo();
		//TODO move to JDAtabseQuery
		//this query tasks all the campaigns that are ending  withing 24 hours from 1 hour from now, so we can create emails that will be queued for sending  from 1 hour. 
		$query = "select * from `#__tienda_campaigns`  where `campaign_ready` = '1' AND `campaign_enabled` = '1' AND `ending_tasks_3` = '0'  AND `fundingend_date` <= DATE_ADD(NOW(), INTERVAL 25 HOUR) AND fundingend_date >= DATE_ADD(NOW(), INTERVAL 1 HOUR)";

		$db->setQuery($query);
		// Load the results as a list of stdClass objects.
		$results = $db->loadObjectList();

		foreach($results as $campaign) {
				$sql = "select * from`#__favorites_items` where `object_id` = '$campaign->campaign_id'";
		
				$db->setQuery($sql);
				$followers = $db->loadObjectList();

			foreach($followers as $fav) {
				$this->doEndingInOneDayEmail($campaign, $fav);
			}	
			$campaign->ending_tasks_3 = 1;
			$result = $db->updateObject('#__tienda_campaigns',$campaign, 'campaign_id', false);  
			
			
		}


		
	}


	function doEndingInOneDayEmail($campaign, $fav) {

		//TODO move to plugin
			if ( !class_exists('Messagebottle') ) 
    		JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
    		$lang = JFactory::getLanguage();
			$lang->load('com_tienda', JPATH_ADMINISTRATOR);
			$mail = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
			$mail->addRecipient( $fav->user_id );
			$mail->setSubject( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_ENDING_ONEDAY_TITLE'), $campaign->campaign_name ) );

			$link = '<a href="'.JURI::root().'/project/campaigns/view/'.$campaign->campaign_id .'">'.$campaign->campaign_name.'</a>';
			$mail->setBody( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_ENDING_ONEDAY_BODY'), $link ) );
			$mail->setScope('2');
			$initDate = new DateTime();
			$initDate->add(new DateInterval("PT1H"));
			$senddate =  $initDate->format('Y-m-d H:i:s');//result: 2010/08/24 08:15:00
			$mail->setSendDate($senddate);
			$mail->setView('campaign');
			$mail->setOption('com_tienda');
 			$mail->Send();

	}


	function doEndingInWeekEmails() {
		// Get a db connection.
		$db = JFactory::getDbo();
		//TODO move to JDAtabseQuery
		//this query tasks all the campaigns that are ending  withing 24 hours from 1 hour from now, so we can create emails that will be queued for sending  from 1 hour. 
		$query = "select * from `#__tienda_campaigns`  where `campaign_ready` = '1' AND `campaign_enabled` = '1' AND `ending_tasks_2` = '0'  AND `fundingend_date` <= DATE_ADD(NOW(), INTERVAL 1 WEEK) AND fundingend_date >= DATE_ADD(NOW(), INTERVAL 1 HOUR)";
		$db->setQuery($query);
		// Load the results as a list of stdClass objects.
		$results = $db->loadObjectList();
		foreach($results as $campaign) {
				$sql = "select * from`#__favorites_items` where `object_id` = '$campaign->campaign_id'";
		
				$db->setQuery($sql);
				$followers = $db->loadObjectList();

			foreach($followers as $fav) {
				$this->doEndingInOneDayEmail($campaign, $fav);
			}	

			$campaign->ending_tasks_2 = 1;
			$result = $db->updateObject('#__tienda_campaigns',$campaign, 'campaign_id', false);  	
		}
		
	}

	function doFollowersEndingCampaignEnd() {
		// Get a db connection.
		$db = JFactory::getDbo();
		//TODO move to JDAtabseQuery
		//this query tasks all the campaigns that are ending  withing 24 hours from 1 hour from now, so we can create emails that will be queued for sending  from 1 hour. 
		$query = "select * from `#__tienda_campaigns`  where `campaign_ready` = '1' AND `campaign_enabled` = '1' AND `completed_tasks` = '0'  AND `fundingend_date` <= NOW() ";
		$db->setQuery($query);
		// Load the results as a list of stdClass objects.
		$results = $db->loadObjectList();
		foreach($results as $campaign) {
				$sql = "select * from`#__favorites_items` where `object_id` = '$campaign->campaign_id'";
		
				$db->setQuery($sql);
				$followers = $db->loadObjectList();

			foreach($followers as $fav) {
				$this->doEndedEmail($campaign, $fav);
			}	

			$campaign->ended_email_followers = 1;
			$result = $db->updateObject('#__tienda_campaigns',$campaign, 'campaign_id', false);  	
		}
		
	}

	function doOwnerEndingCampaignEnd() {
		// Get a db connection.
		$db = JFactory::getDbo();
		//TODO move to JDAtabseQuery
		//this query tasks all the campaigns that are ending  withing 24 hours from 1 hour from now, so we can create emails that will be queued for sending  from 1 hour. 
		$query = "select * from `#__tienda_campaigns`  where `campaign_ready` = '1' AND `campaign_enabled` = '1' AND `fundingend_date` <= NOW() ";
		$db->setQuery($query);
		// Load the results as a list of stdClass objects.
		$results = $db->loadObjectList();
		foreach($results as $campaign) {
			$this->doEndedEmail($campaign);	
			$campaign->ended_email_owner = 1;
			$result = $db->updateObject('#__tienda_campaigns',$campaign, 'campaign_id', false);  	
		}
		
	}

	function doEndedEmail($campaign) {

		//TODO move to plugin
			if ( !class_exists('Messagebottle') ) 
    		JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
    		$lang = JFactory::getLanguage();
			$lang->load('com_tienda', JPATH_ADMINISTRATOR);
			$mail = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
			$mail->addRecipient( $campaign->user_id );
			$mail->setSubject( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_OWNER_CAMPAIGN_ENDED_TITLE'), $campaign->campaign_name ) );
			$link = '<a href="'.JURI::root().'/project/campaigns/view/'.$campaign->campaign_id .'">'.$campaign->campaign_name.'</a>';
			$mail->setBody( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_OWNER_CAMPAIGN_ENDED_BODY'), $link ) );
			$mail->setScope('2');
			$initDate = new DateTime();
			$initDate->add(new DateInterval("PT1H"));
			$senddate =  $initDate->format('Y-m-d H:i:s');//result: 2010/08/24 08:15:00
			$mail->setSendDate($senddate);
			$mail->setView('campaign');
			$mail->setOption('com_tienda');
 			$mail->Send();

	}




}
