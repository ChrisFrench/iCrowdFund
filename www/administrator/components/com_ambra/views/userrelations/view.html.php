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

class AmbraViewUserRelations extends AmbraViewBase 
{	
    /**
     * The default toolbar for a list
     * @return unknown_type
     */
    function _defaultToolbar()
    {    	
    	JToolBarHelper::deleteList();  
    	JToolBarHelper::addnew();   
    }
    
	function _formToolbar( $isNew=null )
    {
        JToolBarHelper::custom('savenew', "savenew", "savenew", JText::_( 'Save + New' ), false);
        JToolBarHelper::save('save');
        JToolBarHelper::cancel('cancel');
    }
}