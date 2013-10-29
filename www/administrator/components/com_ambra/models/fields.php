<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
if ( !class_exists('Ambra') ) 
         JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );

Ambra::load( 'AmbraModelBase', 'models._base' );

class AmbraModelFields extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_typeid = $this->getState('filter_typeid');
        $filter_category = $this->getState('filter_category');
        $filter_required = $this->getState('filter_required');
        $filter_listdisplayed = $this->getState('filter_listdisplayed');
        $filter_profiledisplayed = $this->getState('filter_profiledisplayed');
        
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.field_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.field_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.field_enabled = '.(int) $filter_enabled);
        }
        if (strlen($filter_typeid))
        {
            $query->where('tbl.type_id = '.(int)$filter_typeid);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.field_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.field_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.field_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.field_name) LIKE '.$key);
        }

        if (strlen($filter_category))
        {
            $query->where('f2c.category_id = '.(int) $filter_category);
            // filter_required only applied if filter_category is applied
            if (strlen($filter_required))
            {
                $query->where('f2c.required = '.(int) $filter_required);
            }
        }
        
        if (strlen($filter_listdisplayed))
        {
            $query->where('tbl.list_displayed = '.(int)$filter_listdisplayed);
        }
        
        if (strlen($filter_profiledisplayed))
        {
            $query->where('tbl.profile_displayed = '.(int)$filter_profiledisplayed);
        }
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $filter_category = $this->getState('filter_category');
        if (strlen($filter_category))
        {
            $query->join('LEFT', '#__ambra_fields2categories AS f2c ON tbl.field_id = f2c.field_id');    
            $query->join('LEFT', '#__ambra_categories AS c ON f2c.category_id = c.category_id');
        }
    }

    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        
        $filter_category = $this->getState('filter_category');
        if (strlen($filter_category))
        {
            $fields[] = " c.category_id AS category_id ";
            $fields[] = " c.category_name AS category_name ";
            $fields[] = " f2c.required AS required ";
        }
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
    
    protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        $item->link = 'index.php?option=com_ambra&view=fields&task=edit&id='.$item->field_id;
        
       parent::prepareItem($item, $key, $refresh);
    }

}