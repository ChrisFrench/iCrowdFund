<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
jimport('joomla.filter.output');
// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="me-menu '.$item->anchor_css.'" ' : '';
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else { $linktype = $item->title;
}
?>
<a width="100px;" class="me-menu dropdown-toggle" data-toggle="dropdown" href="#" >
<div class="pull-left me-menu-image"><img class="media-object" src="<?php echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar(  $user->id ); ?>"/></div>

	<div class="pull-right me-menu-title"><?php echo $item->title; ?><b class="caret"></b></div>
	<div class="clearfix"></div>
</a>