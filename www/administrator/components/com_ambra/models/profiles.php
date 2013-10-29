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

class AmbraModelProfiles extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_category = $this->getState('filter_category');

        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.profile_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.profile_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.profile_enabled = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.profile_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.profile_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.profile_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.profile_name) LIKE '.$key);
        }
        
        if (strlen($filter_category))
        {
            $query->where('c2p.category_id = '.(int) $filter_category);
        }
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $filter_category = $this->getState('filter_category');
        if (strlen($filter_category))
        {
            $query->join('LEFT', '#__ambra_categories2profiles AS c2p ON tbl.profile_id = c2p.profile_id');
        }
    }

    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        
        $fields[] = "
            (
            SELECT 
                COUNT(profile_id)
            FROM
                #__ambra_userdata AS userdata
            WHERE 
                userdata.profile_id = tbl.profile_id 
            ) 
        AS profile_users ";
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
     protected function prepareItem( &$item, $key=0, $refresh=false )
    {
       $item->link = 'index.php?option=com_ambra&view=profiles&task=edit&id='.$item->profile_id;
       parent::prepareItem($item, $key, $refresh);
    }

    
}
