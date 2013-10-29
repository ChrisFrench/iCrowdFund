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

$html = "";
		$itemid = modBilletsOpenHelper::getItemid();
		$num = intval( modBilletsOpenHelper::getOpenTickets() );

		$display_null = $params->get( 'display_null', '1' );
		$null_text = $params->get( 'null_text', 'No Tickets' );
		
		if ($num > 0) 
		{		
			if ($num == 1) { $text = JText::_('MOD_BILLETS_OPEN_TICKET' ); } else { $text = JText::_( 'MOD_BILLETS_OPEN_TICKETS'); }
						
		    $html .= '<div>';
				$link = "index.php?option=com_billets&Itemid=".$itemid;
				$link = JRoute::_( $link, false );
				$html .= "<a href='".$link."'>".$num." ".$text."</a>"; 
			$html .= '</div>';
		} 
			elseif ($display_null == '1') 
		{
			$text = JText::_( $null_text );
		    $html .= '<div>';
				$link = "index.php?option=com_billets&Itemid=".$itemid;
				$link = JRoute::_( $link, false );
				$html .= "<a href='".$link."'>".$text."</a>"; 
			$html .= '</div>';
		}
		
echo $html;