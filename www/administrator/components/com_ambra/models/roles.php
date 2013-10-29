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

if ( !class_exists('Ambra') ) 
         JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );

Ambra::load( 'AmbraModelBase', 'models._base' );

class AmbraModelRoles extends AmbraModelBase 
{	
    protected function _buildQueryWhere(&$query)
    {
        $filter         = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $enabled        = $this->getState('filter_enabled');
        $parentid       = $this->getState('filter_parentid');
        $level          = $this->getState('filter_level');

        if ($filter)
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.role_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.role_name) LIKE '.$key;
            $where[] = 'LOWER(tbl.role_description) LIKE '.$key;

            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.role_id >= '.(int) $filter_id_from);
            }
                else
            {
                $query->where('tbl.role_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.role_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.role_name) LIKE '.$key);
        }
        if (strlen($enabled))
        {
            $query->where('tbl.role_enabled = '.(int)$enabled);
        }
        if (strlen($parentid))
        {
            $parent = $this->getTable();
            $parent->load( $parentid );
            if (!empty($parent->role_id))
            {
                $query->where('tbl.lft BETWEEN '.$parent->lft.' AND '.$parent->rgt );
            }
        }

        if (strlen($level))
        {
            $query->where("tbl.parent_id = '$level'");
            if ($level > 1)
            {
                $query->where("parent.role_id = '$level'");             
            }
        }

        $query->where('tbl.isroot != 1');
        $query->where('tbl.lft BETWEEN parent.lft AND parent.rgt');
    }

    /**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(&$query)
    {
        $name = $this->getTable()->getTableName();
        $query->from($name.' AS tbl');
        $query->from($name.' AS parent');
    }

    protected function _buildQueryFields(&$query)
    {
        $level = $this->getState('filter_level');

        $field = array();
        $field[] = " COUNT(parent.role_id)-1 AS level ";
        $field[] = " CONCAT( REPEAT(' ', COUNT(parent.role_name) - 1), tbl.role_name) AS name ";        
 
        if ($level > 1)
        {
            $field[] = " parent.role_id AS parent_role_id ";
            $field[] = " parent.role_name AS parent_role_name ";
        }
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );
        $query->select( $field );
    }

    /**
     * Builds a GROUP BY clause for the query
     */
    protected function _buildQueryGroup(&$query)
    {
        $query->group('tbl.role_id');
    }
     protected function prepareItem( &$item, $key=0, $refresh=false )
    {
           $item->link = 'index.php?option=com_ambra&view=roles&task=edit&id='.$item->role_id; 
       parent::prepareItem($item, $key, $refresh);
    }

   
}
