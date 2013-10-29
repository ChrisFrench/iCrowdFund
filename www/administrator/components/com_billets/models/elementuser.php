<?php
/**
 * @version		$Id: element.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Content Component User Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since		1.5
 */
class BilletsModelElementUser extends DSCModelElement
{
	var $title_key = 'name';
    var $select_title_constant = 'Select a User';
    var $select_constant = 'Select';
    var $clear_constant = 'Clear';

    public function getTable($name='Users', $prefix='BilletsTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_billets/tables' );
        return parent::getTable($name, $prefix, $options);
    }

     protected function _buildQueryWhere(&$query)
    {
       	$filter     = $this->getState('filter');
       	$block	 	= $this->getState('filter_block');

       	if ($filter) 
       	{
			$key	= $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(tbl.id) LIKE '.$key;
			$where[] = 'LOWER(tbl.name) LIKE '.$key;
			$where[] = 'LOWER(tbl.username) LIKE '.$key;
			$where[] = 'LOWER(tbl.email) LIKE '.$key;
			
			$query->where('('.implode(' OR ', $where).')');
       	}
        if (strlen($block))
        {
        	$query->where('tbl.block = '.$block);
       	}
    }

	
}
?>
