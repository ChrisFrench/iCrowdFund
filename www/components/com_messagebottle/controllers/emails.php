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

class MessagebottleControllerEmails extends MessagebottleController
{
	function __construct() 
	{
		parent::__construct();
		$this->set('suffix', 'emails');
	}

	function remove() {
		
		//do a user ID check verse the ID they are trying to delete
		$fid = JRequest::getVar('fid');
		$response = array();
		if ($fid) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messagebottle/tables');
			$email = JTable::getInstance('Events','MessagebottleTable');
			$email -> load($fid);
			$item = $email;
			$email -> delete($fid);
			
			$html = 'Email removed';
			$success = 'true';
		
		$this->deleteEmailsFromEvent($fid);

		$helper = Messagebottle::getClass( 'MBHelper', 'helpers.emails' );
		  
		  $response['btn'] = $helper->emailButton($item->object_id, $item->scope_id, $item->name);
		} else {
			$html = 'Email not removed';
			$success = 'false';
		}
		$response['msg'] = $item->title;
		$response['success'] = $success;
		
		
		
		echo ( json_encode( $response ) );
	}

	function deleteEmailsFromEvent($pk) {
	     
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__messagebottle_emails AS tbl');
       // $query->leftJoin('#__favorites_scopes AS s ON tbl.scope_id = s.scope_id');
        if($pk) {
         $query->where('tbl.event_id = '.  (int) $pk);
        }
        
        $db->setQuery($query);
        
        $emails = $db->loadObjectList();
         DSCTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_messagebottle/tables');
        
        foreach($emails as $email) {
            $table = DSCTable::getInstance('Emails', 'MessagebottleTable');	
            $table->load($email->email_id);
            $table->delete();
        }
     
    
	}

	function getFollowers($oid) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('user_id as id');
		$query->from('#__favorites_items');
		$query->where("object_id = '{$oid}'");
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return 	$results;
	}

	function add() {
		
		$user = JFactory::getUser();

		Messagebottle::load( 'MBHelper', 'helpers.emails' );
		
		$helper = new MBHelper();
		

		if($user->id){
			//Check for posted/getVars
			$name = urldecode(JRequest::getVar('n'));
			$po = urldecode(JRequest::getVar('po'));	
			$object_id = JRequest::getVar('oid');
			$scope_id = JRequest::getVar('sid');
			$tid = JRequest::getVar('tid', 0);
			$date = new JDate();


			$articleBody = DSCArticle::display($po);
			//get the model
			$check = $helper->checkItem($object_id, $scope_id, $name, $po, $user->id  );

			$recepients = $this->getFollowers($po);
		
			if(!$check){

			$bottle = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
			foreach($recepients as $recepient) {
				$bottle->addRecipient($recepient->id);
			}
			$bottle->setParentObject(@$po);
		
			$bottle->setObject(@$object_id);
		
			$bottle->setScope(@$scope_id);
			$title = "Project Update:: ".$name ;
			$bottle->setSubject(@$title);
	
			$bottle->setSender($user -> id);

			$body = DSCArticle::display($object_id);

			$bottle->setBody($body);
		
			$bottle->setEvent('', $title );

			
				if($bottle->bottleit()) { 
					$html = "Email Added";
					$success = 'true';
				} else { 
					$html = "Not added, save failed";
					$success = 'flase';
				}
				
			} else {
			//not logged in
			$html = "Already Exsits";
			$success = 'flase';
			}
			
		} else {
			//not logged in
			$html = "Not authenicated";
			$success = 'flase';
		}
			
		
		$response = array();
        $response['msg'] = $html;
		$response['success'] = $success;
		$response['btn'] = $helper->emailButton($object_id, $scope_id, $name, $po );	
	
        echo ( json_encode( $response ) );
		
	}
}