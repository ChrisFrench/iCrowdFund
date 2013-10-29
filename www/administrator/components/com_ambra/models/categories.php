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

class AmbraModelCategories extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_profile = $this->getState('filter_profile');
        $filter_field = $this->getState('filter_field');
        
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.category_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.category_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.category_enabled = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.category_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.category_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.category_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.category_name) LIKE '.$key);
        }

        if (strlen($filter_profile))
        {
            $query->where('c2p.profile_id = '.(int) $filter_profile);
        }

        if (strlen($filter_field))
        {
            $query->where('f2c.field_id = '.(int) $filter_field);
        }
        
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $filter_profile = $this->getState('filter_profile');
        if (strlen($filter_profile))
        {
            $query->join('LEFT', '#__ambra_categories2profiles AS c2p ON tbl.category_id = c2p.category_id');    
            $query->join('LEFT', '#__ambra_profiles AS p ON c2p.profile_id = p.profile_id');
        }

        $filter_field = $this->getState('filter_field');
        if (strlen($filter_field))
        {
            $query->join('LEFT', '#__ambra_fields2categories AS f2c ON tbl.category_id = f2c.category_id');
        }
    }

    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        
        $filter_profile = $this->getState('filter_profile');
        if (strlen($filter_profile))
        {
            $fields[] = " p.profile_name AS profile_name ";
        }
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }

    protected function prepareItem( &$item, $key=0, $refresh=false )
    {
       $item->link = 'index.php?option=com_ambra&view=categories&task=edit&id='.$item->category_id;
        
       parent::prepareItem($item, $key, $refresh);
    }
    
   
}
