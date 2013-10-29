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
	$maxtitledisplaylength = "10";
	$num_columns = $params->get('num_columns', '5');
	switch ($showmode) 
	{
		case "num":
		case "usernames":
		case "num_usernames":
		case "avatars":
		case "num_avatars":
		default:
						
			if (!empty($users))
			{
			// display avatars w/ usernames as title
			$items = $users;			
			$html .= "
			<table>
			<tbody>
	            <tr>
	            	<td>
		            <table>";
				
					$title = JText::_( 'Front End' );
					$html .= "<strong>{$title}</strong>";
				
				$colnum = 1;
				$m = 0;
				for ($i=0; $i<count($items); $i++) {
					if (fmod($m, $num_columns) == 0) {
						if ($m>1) {
							// first close the row
							$html .= "</tr>";
						}
						// new row
						$html .= "<tr>";
					}
					$html .= "<td>";
	
						// ITEM
						$u = $items[$i];
						$url = "index.php?option=com_ambra&controller=user&task=edit&id={$u['user']->id}";
						$dispatcher =& JDispatcher::getInstance();
						$dispatcher->trigger( 'modWhoIsOnline_onSetUrl', array( $u['user'], $url ) );
						if ($display_urls && $url) { $do_url = true; } else { $do_url = false; }
						if ($do_url) { $html .= "<a href='{$url}'>"; }
						$extra = " title='{$u['user']->username}' name='{$u['user']->username}' ";
						$html .= "<img class='avatar{$moduleclass_sfx}' src='{$u["pic"]}' size='{$pic_size}px' style='max-width: {$pic_size}px; padding: 2px;' {$extra}>";
						if (strlen($u['user']->username) > $maxtitledisplaylength) { 
							$username = substr($u['user']->username, 0, $maxtitledisplaylength)."..."; 
						} else { 
							$username = $u['user']->username; 
						}
						$html .= "<div class='username{$moduleclass_sfx}'>".$username."</div>";
						if ($do_url) { $html .= "</a>"; }
	
					$html .= "<td>";
					$m++;
				}
				$html .= "</tr>";
				
				$html .= "
			            </table>
						</div>
						</td>
		            </tr>
		        </tbody>
				</table>
				";
			}

			if (!empty($usersAdmin))
			{
			// REPEAT FOR ADMIN-SIDE
			// display avatars w/ usernames as title
			$items = $usersAdmin;
			$html .= "
			<table>
			<tbody>
	            <tr>
	            	<td>
		            <table>";
			
				$title = JText::_( 'Admin Side' );
				$html .= "<strong>{$title}</strong>";
			
				$colnum = 1;
				$m = 0;
				for ($i=0; $i<count($items); $i++) {
					if (fmod($m, $num_columns) == 0) {
						if ($m>1) {
							// first close the row
							$html .= "</tr>";
						}
						// new row
						$html .= "<tr>";
					}
					$html .= "<td>";
	
						// ITEM
						$u = $items[$i];
						$url = "index.php?option=com_ambra&controller=user&task=edit&id={$u['user']->id}";
						$dispatcher =& JDispatcher::getInstance();
						$dispatcher->trigger( 'modWhoIsOnline_onSetUrl', array( $u['user'], $url ) );
						if ($display_urls && $url) { $do_url = true; } else { $do_url = false; }
						if ($do_url) { $html .= "<a href='{$url}'>"; }
						$extra = " title='{$u['user']->username}' name='{$u['user']->username}' ";
						$html .= "<img class='avatar{$moduleclass_sfx}' src='{$u["pic"]}' size='{$pic_size}px' style='max-width: {$pic_size}px; padding: 2px;' {$extra}>";
						if (strlen($u['user']->username) > $maxtitledisplaylength) { 
							$username = substr($u['user']->username, 0, $maxtitledisplaylength)."..."; 
						} else { 
							$username = $u['user']->username; 
						}
						$html .= "<div class='username{$moduleclass_sfx}'>".$username."</div>";
						if ($do_url) { $html .= "</a>"; }
	
					$html .= "<td>";
					$m++;
				}
				$html .= "</tr>";
				
				$html .= "
			            </table>
						</div>
						</td>
		            </tr>
		        </tbody>
				</table>
				";
			}
			
		  break;
	}

echo $html;