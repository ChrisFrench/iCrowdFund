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

Ambra::load( 'AmbraModelBase', 'models._base' );

class AmbraModelTools extends AmbraModelBase 
{	
    protected function _buildQueryWhere(&$query)
    {
    	parent::_buildQueryWhere($query);
    	
    	$database = JFactory::getDBO();
    	$where = array();
    	
       	$filter     = $this->getState('filter');

       	if ($filter) 
       	{
         	$where[] = " LOWER(tbl.id) LIKE '%" . $database->getEscaped( trim( strtolower( $filter ) ) ) . "%'";
       		$where[] = " LOWER(tbl.name) LIKE '%" . $database->getEscaped( trim( strtolower( $filter ) ) ) . "%'";
       		$where[] = " LOWER(tbl.element) LIKE '%" . $database->getEscaped( trim( strtolower( $filter ) ) ) . "%'";
       	}
		
		$query .= count( $where ) ? "\n HAVING " . implode( ' OR ', $where ) : "";
		$query .= " AND LOWER(tbl.folder) = 'ambra' ";
    }
   protected function prepareItem( &$item, $key=0, $refresh=false )
    {
      $item->link = 'index.php?option=com_ambra&controller=tools&view=tools&task=edit&id='.$item->id;
       parent::prepareItem($item, $key, $refresh);
    }

	
}
