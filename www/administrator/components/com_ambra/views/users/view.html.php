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


Ambra::load('AmbraViewBase','views._base');

class AmbraViewUsers extends AmbraViewBase 
{
	function _default($tpl=null)
	{
		parent::_default($tpl);
		
		Ambra::load('AmbraField','library.field');
		$model = DSCModel::getInstance( 'Fields', 'AmbraModel' );
		$model->setState( 'order', 'tbl.ordering' );
		$model->setState( 'direction', 'ASC' );
		$model->setState( 'filter_enabled', '1' );
		$model->setState( 'filter_listdisplayed', '1' );
		$fields = $model->getList();
		$this->assign( 'fields', $fields );
        //$this->assign( 'fields', array() );
	}
	
    /**
     * The default toolbar for a list
     * @return unknown_type
     */
    function _defaultToolbar()
    {
        JToolBarHelper::editList();
        JToolBarHelper::addnew();
        
    }
}
