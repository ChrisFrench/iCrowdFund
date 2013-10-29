<?php
/**
 * @version	1.5
 * @package	Featureditems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Featureditems::load( 'FeatureditemsViewBase', 'views.base' );

class FeatureditemsViewCategories extends FeatureditemsViewBase
{
	/**
	 * (non-PHPdoc)
	 * @see featureditems/admin/views/FeatureditemsViewBase#_defaultToolbar()
	 */
	function _defaultToolbar( )
	{
		JToolBarHelper::publishList( 'category_enabled.enable' );
		JToolBarHelper::unpublishList( 'category_enabled.disable' );
		JToolBarHelper::divider( );
		parent::_defaultToolbar( );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see featureditems/admin/views/FeatureditemsViewBase#_formToolbar($isNew)
	 */
	function _formToolbar( $isNew = null )
	{
		if ( !$isNew )
		{
			JToolBarHelper::custom( 'save_as', 'refresh', 'refresh', JText::_( 'Save As' ), false );
		}
		parent::_formToolbar( $isNew );
	}
	
}
