<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

class plgBilletsFeedbackHistory extends BilletsPluginBase 
{
	
	function __construct(& $subject, $config) 
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}

	/**
	 * Method is called 
	 * before displaying a comment.
	 * Note: $comment->authorimage is available, and is already set to the default
	 * 
	 * @return 
	 * @param $row Object
	 * @param $body Object
	 * @param $user Object
	 * @param $args Object
	 */
	function onAfterDisplayComponentBillets() 
	{
		echo "<p><center>";
			echo $this->_displaySummary();
		echo "</center></p>";
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function showHistory()
	{
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		
		$user = JFactory::getUser();
		$html = "";	
		$html .= "<link href='".JURI::root()."media/com_billets/css/billets.css' rel='stylesheet' type='text/css' />";

		$stats = $this->getStats();
		$headerText = $this->params->get( 'headertext', JText::_('Our Feedback History') );

		$html .= "<center><h4>{$headerText}</h4></center>";
		$html .= "<table class='adminlist'>";
		$html .= "<thead>";
			$html .= "<tr>";
				$html .= "<th>".JText::_('Rating')."</th>";
				$html .= "<th>".JText::_('Tickets')."</th>";
			$html .= "</tr>";
		$html .= "</thead>";
		$html .= "<tr>";
			// # w/ 5 stars
			$html .= "<td style='text-align: center;'>".BilletsHelperTicket::getRatingImage( '5' )."</td>";
			$html .= "<td style='text-align: center;'>".$stats->five->count."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		// TODO # w/ 4 stars
			$html .= "<td style='text-align: center;'>".BilletsHelperTicket::getRatingImage( '4' )."</td>";
			$html .= "<td style='text-align: center;'>".$stats->four->count."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		// TODO # w/ 3 stars
			$html .= "<td style='text-align: center;'>".BilletsHelperTicket::getRatingImage( '3' )."</td>";
			$html .= "<td style='text-align: center;'>".$stats->three->count."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		// TODO # w/ 2 stars
			$html .= "<td style='text-align: center;'>".BilletsHelperTicket::getRatingImage( '2' )."</td>";
			$html .= "<td style='text-align: center;'>".$stats->two->count."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		// TODO # w/ 1 stars
			$html .= "<td style='text-align: center;'>".BilletsHelperTicket::getRatingImage( '1' )."</td>";
			$html .= "<td style='text-align: center;'>".$stats->one->count."</td>";
		$html .= "</tr>";
		$html .= "<tr>";
			$html .= "<th>".JText::_('Total')."</th>";
			$html .= "<th>".$stats->overall->count."</th>";
		$html .= "</tr>";
		$html .= "</table>";
      
		return $html;	
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function _displaySummary()
	{
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		Billets::load('BilletsUrl', 'library.url');
		
		
		$html = "";
		$stats = $this->getStats();

		JHTML::_('behavior.modal', 'a.modal');
		$url = "index.php?option=com_billets&view=tickets&format=raw&task=doTask&element=feedbackhistory&elementTask=showHistory";

		// TODO Param: Display overall header? 
		$text = JText::_('COM_BILLETS_OUR_OVERALL_FEEDBACK_RATING_IS');
		// $html .= BilletsUrl::popup( $url, $text, '500', '500' );
		$html .= $text;
		$html .= "<br/>";
				
		// TODO Param: Display image? 
		$num = 0;
		if ($stats->overall->count > '0') { $num = $stats->overall->sum / $stats->overall->count; }
		$html .= BilletsHelperTicket::getRatingImage ( $num );
		$html .= "<br/>";
		
		// TODO Param Display Infolink?		
		$text = JText::_('COM_BILLETS_FIND_OUT_WHAT_THIS_MEANS' );
		$html .= BilletsUrl::popup( $url, $text, array( 'width'=>'500', 'height'=>'325' ) );
		
		return $html;	
	}
	
	/**
	 * 
	 * @param $refresh
	 * @return unknown_type
	 */
	function getStats( $refresh='0' ) 
	{
		static $instance;
		
		if (!is_object($instance) || $refresh == '1' )
		{
			$instance = new JObject();
			$instance->overall 	= $this->getOverall();
			$instance->five		= $this->getByRating( '5' );
			$instance->four		= $this->getByRating( '4' );
			$instance->three	= $this->getByRating( '3' );
			$instance->two		= $this->getByRating( '2' );
			$instance->one		= $this->getByRating( '1' );
		}

		return $instance;
	}
	
	/**
	 * 
	 * @param $refresh
	 * @return unknown_type
	 */
	function getOverall( $refresh='0' ) 
	{
		static $instance;
		
		if (!is_object($instance) || $refresh == '1' )
		{
			$database = JFactory::getDBO();
			$state = Billets::getInstance()->get( 'state_closed' );
			
			$query = " 
				SELECT 
					SUM(t.feedback_rating) AS sum, COUNT(t.id) AS count
				FROM
					#__billets_tickets AS t
				WHERE 1
					AND ( t.stateid = '{$state}' ) 
					AND ( t.firstresponse_by > '0' )
					AND t.feedback_rating > '0'
			";
			$database->setQuery( $query );
			$instance = $database->loadObject();
		}

		return $instance;
	}
	
	/**
	 * 
	 * @param $refresh
	 * @return unknown_type
	 */
	function getByRating( $rating, $refresh='0' ) 
	{
		static $instance;
		$rating = intval($rating);
		
		if (!is_array($instance) || !isset($instance[$rating]) || $refresh == '1' )
		{
			if (!is_array($instance)) { $instance = array(); }
			$database = JFactory::getDBO();
			$state = Billets::getInstance()->get( 'state_closed' );
			
			$query = " 
				SELECT 
					SUM(t.feedback_rating) AS sum, COUNT(t.id) AS count
				FROM
					#__billets_tickets AS t
				WHERE 1
					AND ( t.stateid = '{$state}' ) AND ( t.firstresponse_by > '0' )
					AND t.feedback_rating = '{$rating}'
			";
			$database->setQuery( $query );
			$instance[$rating] = $database->loadObject();
		}

		return $instance[$rating];
	}

}
