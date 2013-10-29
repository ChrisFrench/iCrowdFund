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

Ambra::load( 'AmbraViewBase', "views._base");


class AmbraViewActions extends AmbraViewBase 
{
    /**
     * (non-PHPdoc)
     * @see ambra/admin/views/AmbraViewBase#_defaultToolbar()
     */
	function _defaultToolbar()
	{
		JToolBarHelper::publishList( 'is_public.enable' );
		JToolBarHelper::unpublishList( 'is_public.disable' );
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
}
