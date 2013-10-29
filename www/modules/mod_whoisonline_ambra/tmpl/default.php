<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

	$html = '';

	if ($display_num) 
	{
	    if ($count['guest'] != 0 || $count['user'] != 0)
	    {
	    	$html .= '<div>';
	        $html .= JText::_('We have') . '&nbsp;';
	        
			if ($count['guest'] == 1)
			{
			    $html .= JText::sprintf('guest', '1');
			} elseif ($count['guest'] > 1) {
				$html .= JText::sprintf('guests', $count['guest']);
			}
	
			if ($count['guest'] != 0 && $count['user'] != 0)
			{
			    $html .= '&nbsp;' . JText::_('and') . '&nbsp;';
			}
	
			if ($count['user'] == 1)
			{
			    $html .= JText::sprintf('member', '1');
			} elseif ($count['user'] > 1) {
				$html .= JText::sprintf('members', $count['user']);
			}
			
			$html .= '&nbsp;' . JText::_('online');
			$html .= '</div>';
	    }
		
	}
		
	switch ($showmode) 
	{
		case "usernames":
		case "num_usernames":
			// display usernames
			for ($i=0; $i<count($users); $i++) 
			{
				$u = $users[$i];
				$url = "index.php?option=com_ambra&view=users&id={$u['user']->id}&Itemid={$itemid2affix}";
				$dispatcher =& JDispatcher::getInstance();
				$dispatcher->trigger( 'modWhoIsOnline_onSetUrl', array( $u['user'], $url ) );
				if ($display_urls && $url) { $do_url = true; } else { $do_url = false; }
				if ($do_url) { $html .= "<a href='{$url}'>"; } 
				$html .= "<div class='username{$moduleclass_sfx}'>".$u['user']->username."</div>";
				if ($do_url) { $html .= "</a>"; }
			}
		  break;
		case "avatars":
		case "num_avatars":
			// display avatars w/ usernames as title
			for ($i=0; $i<count($users); $i++) 
			{
				$u = $users[$i];
				$url = "index.php?option=com_ambra&view=users&id={$u['user']->id}&Itemid={$itemid2affix}";
				$dispatcher =& JDispatcher::getInstance();
				$dispatcher->trigger( 'modWhoIsOnline_onSetUrl', array( $u['user'], $url ) );
				if ($display_urls && $url) { $do_url = true; } else { $do_url = false; }
				if ($do_url) { $html .= "<a href='{$url}'>"; }
				$extra = " title='{$u['user']->username}' name='{$u['user']->username}' ";
				$html .= "<img class='avatar{$moduleclass_sfx}' src='{$u["pic"]}' size='{$pic_size}px' style='max-width: {$pic_size}px; padding: 2px;' {$extra}>";
				if ($do_url) { $html .= "</a>"; }
			}
		  break;
		case "num":
		default:
		  break;
	}

echo $html;