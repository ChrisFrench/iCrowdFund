	<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$document->addStyleSheet( JURI::root(true).'/modules/mod_ambra_me/tmpl/style.css');

// Note. It is important to remove spaces between elements.
?>

<!-- Wrap the .navbar in .container to center it on the page and provide easy way to target it with .navbar-wrapper. -->
   
          	<ul class="nav me-menu<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
$dd = TRUE;
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent && $dd) {
		$dd = FALSE;
		$class .= ' parent dropdown';
		$item->type = 'dd';
	}
	if($item->type == 'separator' ){
		$class = ' divider ';
	}
	if (!empty($class)) {
		$class = ' class="me-menu '.trim($class) .'"';
		
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'dd':
			require JModuleHelper::getLayoutPath('mod_ambra_me', 'default_'.$item->type);
			break;
		case 'separator':
			break;
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_ambra_me', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_ambra_me', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul class="dropdown-menu">';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
		$dd = TRUE;
	}
endforeach;
?>
          



