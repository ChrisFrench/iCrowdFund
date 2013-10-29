<?php
/**
* Author: Dioscouri Design - www.dioscouri.com
* @package BILLETS
* @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// Import library dependencies
Billets::load( 'BilletsPluginBase', 'library.plugin.base' );
jimport( 'joomla.filesystem.file' );

class plgBilletsAmbrasubs extends BilletsPluginBase {

	
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		$element = strtolower( 'com_Billets' );
		$this->loadLanguage( $element, JPATH_BASE );
		$this->loadLanguage( $element, JPATH_ADMINISTRATOR );
	}
	
	/**
	 * 
	 * @param $row
	 * @param $user
	 * @return unknown_type
	 */
	function onDisplayFromColumn( $row, $user )
	{
		$text = '';

		if ( !JFile::exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambrasubs'.DS.'helpers'.DS.'ambrasubs.php' ) ) {
			return true;
		}

		// Require Helpers
		jimport( 'joomla.filesystem.folder' );
		$helpersPath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambrasubs'.DS.'helpers';
		$helperFiles = JFolder::files($helpersPath, '\.php$', false, true);
		if (count($helperFiles) > 0) {
			// iterate through the helper files
			foreach ($helperFiles as $helperFile) {
				require_once($helperFile);
			}
		}
		
		echo "<p>";
		
		// get subs
			$subscriptions_list = "";
			$subscriptions = AmbrasubsHelperSubscription::getUserSubs( intval( $user->id ), '1' );
			$img = "<img src='".JURI::root()."media/com_billets/images/required_16.png'>";
			for ($r=0; $r<count($subscriptions); $r++) {
				$sub = $subscriptions[$r];
				$title = $sub->title ? $sub->title : JText::_('COM_BILLETS_EDIT');
				$link = JRoute::_( "index.php?option=com_ambrasubs&controller=subscriptions&task=edit&id={$sub->u2tid}" );
				$subscriptions_list .= "{$img} <a href='{$link}'>".$title.'</a><br>';
			}
			echo $subscriptions_list;
					
		// get expired subs
			$subscriptions_expired = "";
			$expired = AmbrasubsHelperSubscription::getUserSubs( intval( $user->id ), '0' );
			if ($num = count($expired)) {
				$link = JRoute::_( "index.php?option=com_ambrasubs&controller=users&task=manage&id={$user->id}" );
				$subscriptions_expired = "[<a href='{$link}'>{$num} ".JText::_('Expired Subscription(s)')."</a>]<br>";
			}
			echo $subscriptions_expired;
			
		echo "</p>";
		
		return true;
	}
	
	/**
	 * 
	 * @param $ticket
	 * @param $user
	 * @return unknown_type
	 */
	function onAfterDisplayTicketInfo( $ticket, $admin )
	{
		global $mainframe;
		$text = '';

		if ( !JFile::exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambrasubs'.DS.'helpers'.DS.'ambrasubs.php' ) ) {
			return true;
		}

		// Require Helpers
		jimport( 'joomla.filesystem.folder' );
		$helpersPath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambrasubs'.DS.'helpers';
		$helperFiles = JFolder::files($helpersPath, '\.php$', false, true);
		if (count($helperFiles) > 0) {
			// iterate through the helper files
			foreach ($helperFiles as $helperFile) {
				require_once($helperFile);
			}
		}

		$user = JFactory::getUser( $ticket->sender_userid );
		
		echo "<table class='adminlist'>
			<thead>
				<th>".JText::_('Subscriptions')."</th>
			</thead>
			<tbody>
			<tr>
			<td>
			";
				
		// get subs
			$subscriptions_list = "";
			$subscriptions = AmbrasubsHelperSubscription::getUserSubs( intval( $user->id ), '1' );
			$img = "<img src='".JURI::root()."media/com_billets/images/required_16.png'>";
			for ($r=0; $r<count($subscriptions); $r++) {
				$sub = $subscriptions[$r];
				$title = $sub->title ? $sub->title : JText::_('COM_BILLETS_EDIT');
				if ($mainframe->isAdmin()) {
					$link = JRoute::_( "index.php?option=com_ambrasubs&controller=users&task=manage&id={$user->id}" );	
				} else {
					$link = JRoute::_( "index.php?option=com_ambrasubs&view=subscriptions&layout=mine" );
				}
				$subscriptions_list .= "{$img} <a href='{$link}'>".$title.'</a><br>';
			}
			echo $subscriptions_list;
					
		// get expired subs
			$subscriptions_expired = "";
			$expired = AmbrasubsHelperSubscription::getUserSubs( intval( $user->id ), '0' );
			if ($num = count($expired)) {
				if ($mainframe->isAdmin()) {
					$link = JRoute::_( "index.php?option=com_ambrasubs&controller=users&task=manage&id={$user->id}" );	
				} else {
					$link = JRoute::_( "index.php?option=com_ambrasubs&view=subscriptions&layout=mine" );
				}				
				$subscriptions_expired = "[<a href='{$link}'>{$num} ".JText::_('Expired Subscription(s)')."</a>]<br>";
			}
			echo $subscriptions_expired;
			
		echo "
			</td>
			</tr>
		<tbody>
		</table>";
		
		return true;
	}

}
