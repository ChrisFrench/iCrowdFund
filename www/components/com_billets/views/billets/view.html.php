<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

jimport( 'joomla.application.component.view' );

class BilletsViewBillets extends JView 
{
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl = null) 
	{
		$app = JFactory::getApplication();			
		$link = 'index.php?option=com_billets&view=tickets';
		$link = JRoute::_( $link, false );
		$app->redirect( $link );
		return;
		
    }
}
