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

class AmbraModelPointRules extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_scope        = $this->getState('filter_scope');
        $filter_event        = $this->getState('filter_event');
        $filter_date_from   = $this->getState('filter_date_from');
        $filter_date_to     = $this->getState('filter_date_to');
        $filter_datetype    = $this->getState('filter_datetype');
        $filter_points_from = $this->getState('filter_points_from');
        $filter_points_to   = $this->getState('filter_points_to');
        $filter_profile     = $this->getState('filter_profile');
        $filter_pointprofile = $this->getState('filter_pointprofile');
                
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.pointrule_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.pointrule_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.pointrule_enabled = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.pointrule_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.pointrule_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.pointrule_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.pointrule_name) LIKE '.$key);
        }

         if (strlen($filter_date_from) && !preg_match('/[^\d\: \-]/', $filter_date_from))
        
        {
            switch ($filter_datetype)
            {
                case "expires":
                    $nullDate = $this->_db->getNullDate();
                    $query->where("(tbl.expire_date >= '".$filter_date_from."' OR tbl.expire_date = '".$nullDate."' )");
                  break;
                case "created":
                default:
                    $query->where("tbl.created_date >= '".$filter_date_from."'");
                  break;
            }
        }
         if (strlen($filter_date_to) && !preg_match('/[^\d\: \-]/', $filter_date_to))
        
        {
            switch ($filter_datetype)
            {
                case "expires":
                    $query->where("tbl.expire_date <= '".$filter_date_to."'");
                  break;
                case "created":
                default:
                    $query->where("tbl.created_date <= '".$filter_date_to."'");
                  break;
            }
        }
        
        if (strlen($filter_points_from))
        {
            if (strlen($filter_points_to))
            {
                $query->where('tbl.pointrule_value >= '.(int) $filter_points_from); 
            }
                else
            {
                $query->where('tbl.pointrule_value = '.(int) $filter_points_from);
            }
        }
        if (strlen($filter_points_to))
        {
            $query->where('tbl.pointrule_value <= '.(int) $filter_points_to);
        }
        
        if ($filter_scope) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_scope ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.pointrule_scope) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
        if ($filter_event) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_event ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.pointrule_event) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
        if (strlen($filter_profile))
        {
            if (empty($filter_profile))
            {
                $query->where("( tbl.profile_id = '0' OR tbl.profile_id IS NULL )" );
            }
                else
            {
                $query->where('tbl.profile_id = '.(int) $filter_profile);    
            }
        }
        
        if (strlen($filter_pointprofile))
        {
            $query->where("( tbl.profile_id = '0' OR tbl.profile_id IS NULL OR tbl.profile_id = '".(int) $filter_pointprofile ."' )" );
        }
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__ambra_profiles AS p ON tbl.profile_id = p.profile_id');
    }

    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " p.profile_name ";

        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
    
     protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        $item->link = 'index.php?option=com_ambra&view=pointrules&task=edit&id='.$item->pointrule_id;
       parent::prepareItem($item, $key, $refresh);
    }

    
}
