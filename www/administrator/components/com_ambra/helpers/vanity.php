<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
 * @version     $Id: helper.php 14583 2010-02-04 07:16:48Z eddieajau $
 * @package     Joomla.Framework
 * @subpackage  Mail
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.filesystem.file');

class AmbraHelperVanity extends DSCHelper {





 function displayField($value) {

$itemId = Ambra::getInstance()->get('vanity_itemid');
$item = JFactory::getApplication()->getMenu()->getItem( $itemId );
$path = JRoute::_($item->link . '&Itemid=' . $item->id, true, -1);
$path = explode('?', $path);
$path = $path[0];

$html = '';
$html .= '<div class="form_item">';
$html .= '<div class="form_key">';
$html .= $path . '/';
$html .= '</div> ';
$html .= '<div class="form_input">';
$html .= '<input name="userdata[slug]" id="profile_slug" value="'.$value.'" type="text" class="inputbox" size="50" />';
$html .= '</div>';
$html .= '</div>';


 return $html; 

}

function checkValidVanityUrl ( ) {

}


function addVanityUrl() {
	

    //something lick this.
        $menutype = $this->params->get(`menutype`, ``);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(`max(lft) AS max_lft, max(rgt) AS max_rgt`);
        $query->from(`#__menu`);
        $query->where(`menutype = ` . $db->quote($menutype));

        $db->setQuery($query);
        $result = $db->loadObject();

        $query = $db->getQuery(true);
        $query->select(`id, lft, rgt, level`);
        $query->from(`#__menu`);
        $query->where(`lft = ` . $db->quote($result->max_lft));
        $query->where(`rgt = ` . $db->quote($result->max_rgt));

        $db->setQuery($query);
        $result = $db->loadObject();

        // Create space in the tree at the new location for the new node in left ids
        $query = $db->getQuery(true);
        $query->update(`#__menu`);
        $query->set(`lft = lft + 2`);
        $query->where(`lft > ` . $result->lft);
        $success = $db->query();

        // Create space in the tree at the new location
        // for the new node in right ids.
        $query = $db->getQuery(true);
        $query->update(`#__menu`);
        $query->set(`rgt = rgt + 2`);
        $query->where(`rgt > ` . $result->rgt);
        $success = $db->query();

        $query->select(`extension_id`);
        $query->from(`#__extensions`);
        $query->where(`type = ` . $db->quote('component'));
        $query->where(`element = ` . $db->quote('com_example'));

        $query = $db->getQuery(true);
        $db->setQuery($query);
        $extension = $db->loadObject();

        $params = JComponentHelper::getParams(`com_example`);

        // Insert new menu item
        $new_menu_item = new stdClass();

        $new_menu_item->id = 0;
        $new_menu_item->parent_id = 1;
        $new_menu_item->level = 1;
        $new_menu_item->menutype = $menutype;
        $new_menu_item->title = $args->title;
        $new_menu_item->alias = $args->alias;
        $new_menu_item->note = '';
        $new_menu_item->path = $args->alias;
        $new_menu_item->link = `index.php?com_example&view=demo&id=`. $args->id;
        $new_menu_item->type = `component`;
        $new_menu_item->published = $args->published;
        $new_menu_item->level = 1;
        $new_menu_item->component_id = $extension->extension_id;
        $new_menu_item->ordering = 0;
        $new_menu_item->checked_out = 0;
        $new_menu_item->checked_out_time = `0000-00-00 00:00:00`;
        $new_menu_item->browserNav = 0;
        $new_menu_item->access = 1;
        $new_menu_item->img = ``;
        $new_menu_item->template_style_id = 0;
        $new_menu_item->params = $params;
        $new_menu_item->lft = $result->lft + 2;
        $new_menu_item->rgt = $result->rgt + 2;
        $new_menu_item->home = 0;
        $new_menu_item->language = `*`;
        $new_menu_item->client_id = 0;

        $success = $db->insertObject(`#__menu`, $new_menu_item, `id`));

        return $new_menu_item->id;
    
}





}