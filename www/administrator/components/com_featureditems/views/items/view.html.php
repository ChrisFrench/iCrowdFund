<?php
/**
 * @version 1.5
 * @package FeaturedItems
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

FeaturedItems::load( 'FeaturedItemsViewBase', 'views.base' );

class FeaturedItemsViewItems extends FeaturedItemsViewBase
{
    public function _defaultToolbar()
    {
		JToolBarHelper::publishList( "item_enabled.enable", "Enable" );
		JToolBarHelper::unpublishList( "item_enabled.disable", "Disable" );
        parent::_defaultToolbar();
    }
}
