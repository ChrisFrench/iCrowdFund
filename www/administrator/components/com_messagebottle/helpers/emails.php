<?php
/**
 * @package Messagebottle
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
 
 class MBHelper {


 	function __construct(){
		DSC::loadJQuery();
		JHTML::_('script', 'messagebottle.js', 'media/com_messagebottle/js/');
	}

	public function addEmailButton($object_id, $scope_id, $name, $url = null, $text = 'Add', $attribs = array() ) {
		if(empty($text)){
			$text = Messagebottle::getInstance()->get( 'messagebottle_add_text', JTEXT::_('COM_MESSAGEBOTTLE_EMAILADD') );
			
		}
		if(empty($attribs['addclass'])) {
			$attribs['addclass'] = Messagebottle::getInstance()->get( 'messagebottle_add_class', 'addFav messagebottle pull-right btn btn-primary' );
		}
		FB::log($object_id );
		$html = '';
		$html .= '';
		$html .= '<a id="email-' . $object_id . '-' . $scope_id . '" class="'.$attribs['addclass'].'"  data-loading-text="Loading..."';
		$html .= ' href="';
		$html .= $this -> makeurl($object_id, $scope_id, $name, $url);
		$html .= '">' . $text;
		$html .= '</a>';

		return $html;

	}

	public function removeEmailButton($fid = null, $object_id = null, $scope_id = null, $name = null, $po = null, $text = 'remove', $attribs = array()) {
		if(empty($attribs['removeclass'])) {
			$attribs['removeclass'] = Messagebottle::getInstance()->get( 'messagebottle_remove_class', 'removeFav messagebottle pull-right btn btn-danger' );
		}
		if(empty($text)){
			$text = Messagebottle::getInstance()->get( 'messagebottle_remove_text', JTEXT::_('COM_MESSAGEBOTTLE_EMAILREMOVE')  );
		}
		$html = ''; 
		$html .= '<a id="email-' . $fid. '" class="'.$attribs['removeclass'].'"  data-loading-text="Loading..."';
		$html .= ' href="';
		$html .= $this -> removeurl($fid, $object_id, $scope_id, $name, $po );
		$html .= '">' . $text;
		$html .= '</a>';

		return $html;
	}

	public function disabledButton( $text = 'Email Sent', $attribs = array()) {
		if(empty($attribs['removeclass'])) {
			$attribs['removeclass'] = Messagebottle::getInstance()->get( 'messagebottle_remove_class', 'removeFav messagebottle pull-right btn btn-danger' );
		}
		if(empty($text)){
			$text = Messagebottle::getInstance()->get( 'messagebottle_disabled_text', 'Sent' );
		}
		$html = ''; 
		$html .= '<button class="btn btn-info pull-right disabled" disabled="disabled">';
		$html .=  $text;
		$html .= '</button>';

		return $html;
	}


	public function emailButton($object_id, $scope_id, $name, $parent_object_id = null, $text = array(), $attribs = array()) {
		if(empty($text)){
			$text[0] = Messagebottle::getInstance()->get( 'messagebottle_add_text', JTEXT::_('COM_MESSAGEBOTTLE_EMAILADD') );
			$text[1] = Messagebottle::getInstance()->get( 'messagebottle_remove_text', JTEXT::_('COM_MESSAGEBOTTLE_EMAILREMOVE') );
		}
		if(empty($attribs)){
			$attribs['addclass'] = Messagebottle::getInstance()->get( 'messagebottle_add_class', 'addFav messagebottle pull-right btn btn-primary' );
			$attribs['removeclass'] = Messagebottle::getInstance()->get( 'messagebottle_remove_class', 'removeFav messagebottle pull-right btn btn-danger' );
		}
		$user = JFactory::getUser();

		if ($user -> id) {

		
			$emailObject = $this -> checkItem($object_id, $scope_id, $name, $parent_object_id, $user -> id);
			
			if ($emailObject) {

				if(@$emailObject->nofavorites) {
				return $this -> disabledButton('No followers yet.');	
				} 

				if($emailObject->processed) {
				return $this -> disabledButton();	
				} else {
				return $this -> removeEmailButton($emailObject->event_id, $object_id,$scope_id, $name, $parent_object_id,$text[1]);
				}

			} else {
				return $this -> addEmailButton($object_id, $scope_id, $name, $parent_object_id, $text[0] , $attribs);
			}
		}
	}

	function makeurl($object_id, $scope_id, $name, $po = null, $tid = null) {

		//$u = JFactory::getURI();
		$href = '';
		$href .= JURI::root();
		$href .= 'index.php?option=com_messagebottle&task=add&format=raw&view=emails';
		if (!empty($object_id)) {
			$href .= '&oid=' . $object_id;
		}
		if (!empty($scope_id)) {
			$href .= '&sid=' . $scope_id;
		}
		if (!empty($name)) {
			$href .= '&n=' . $name;
		}
		if (!empty($po)) {
			$href .= '&po=' . $po;
		}
		if (!empty($tid)) {
			$href .= '&tid=' . $tid;
		}
		

		return $href;
	}
	function removeurl($fid = null,$object_id = null, $scope_id= null, $name= null, $po = null) {

		//$u = JFactory::getURI();
		$href = '';
		$href .= JURI::root();
		$href .= 'index.php?option=com_messagebottle&task=remove&format=raw&view=emails';
		if (!empty($fid)) {
			$href .= '&fid=' . $fid;
		}
		if (!empty($object_id)) {
			$href .= '&oid=' . $object_id;
		}
		if (!empty($scope_id)) {
			$href .= '&sid=' . $scope_id;
		}
		if (!empty($name)) {
			$href .= '&n=' . $name;
		}
		if (!empty($po)) {
			$href .= '&po=' . $po;
		}

		return $href;
	}

	/*This is  just a wrapper for setting states and calling getItem, so you can  say for a list view  load this modal and  just $modal->checkItem($pk); and get a yes no to show the  add box.*/
    public function checkItem( $object_id = NULL, $scope_id = NULL , $name = NULL, $parent_object_id = NULL, $user_id = NULL, $pk = NULL  ) {
        
        if(!$user_id) { $user = JFactory::getUser(); $user_id =  $user->id;  }
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('count(id)');
        $query->from('#__favorites_items AS tbl');
        
         $query->where('tbl.object_id = '.  (int) $parent_object_id);
        
        
        $db->setQuery($query);
      
        $favorites = $db->loadResult();

        if(empty($favorites)) {
        	$obj = new stdClass();
        	$obj->nofavorites = '1';
        	return $obj;
        }

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__messagebottle_events AS tbl');
 
        if($object_id) {
         $query->where('tbl.object_id = '. $db->Quote( $object_id ));
        }
        if($parent_object_id) {
         $query->where('tbl.parent_object_id = '. $db->Quote( $parent_object_id ));
        }
        
        if($user_id) {
         $query->where('tbl.sender_id = '. (int) $user_id);
        }
        
        if($scope_id) {
         $query->where('tbl.scope_id = '. (int) $scope_id);
        }
       
        if($pk) {
         $query->where('tbl.event_id = '.  (int) $pk);
        }
        
        $db->setQuery($query);
        
        $item = $db->loadObject();
        if( $item) {
        	$item->count = $favorites;	
        }
     	

     
        return $item;
    }


}