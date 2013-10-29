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

class AmbraViewRoles extends AmbraViewBase 
{
    /**
     * (non-PHPdoc)
     * @see ambra/admin/views/AmbraViewBase#_defaultToolbar()
     */
	function _defaultToolbar()
	{
		JToolBarHelper::custom('rebuild', 'refresh', 'refresh', JText::_( 'Rebuild Tree' ), false);
		JToolBarHelper::publishList( 'role_enabled.enable' );
		JToolBarHelper::unpublishList( 'role_enabled.disable' );
		JToolBarHelper::divider();
		parent::_defaultToolbar();
	}
}
