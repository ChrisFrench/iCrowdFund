<?php
/**
 * @version	0.1.0
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgSystemAmbraOverride extends JPlugin {
		
	/**
	 * Constructor
	 */
	function __construct( &$subject, $config )  
	{
		parent::__construct($subject, $config);

	}
		
	  /**
     * 
     * @return unknown_type
     */
    function onAfterInitialise() 
    {
        // if you want to be able to make menu items to adds you can add && !JRequest::getVar('itemId'), but it opens this up for access   
        if(JRequest::getCmd('option') == 'com_users'  && JFactory::getApplication()->isSite() ) {
            $array = array("option" => "com_ambra");
         JRequest::set( $array, 'GET', true );
        }
    }
}