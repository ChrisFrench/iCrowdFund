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

class AmbraModelPointCoupons extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_code        = $this->getState('filter_code');
        $filter_date_from   = $this->getState('filter_date_from');
        $filter_date_to     = $this->getState('filter_date_to');
        $filter_datetype    = $this->getState('filter_datetype');
        $filter_points_from = $this->getState('filter_points_from');
        $filter_points_to   = $this->getState('filter_points_to');
                
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.pointcoupon_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.pointcoupon_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.pointcoupon_enabled = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.pointcoupon_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.pointcoupon_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.pointcoupon_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.pointcoupon_name) LIKE '.$key);
        }

        if (strlen($filter_date_from) && !preg_match('/[^\d\: \-]/', $filter_date_from))
        
        {
            switch ($filter_datetype)
            {
                case "expires":
                    $query->where("tbl.expire_date >= '".$filter_date_from."'");
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
                $query->where('tbl.pointcoupon_value >= '.(int) $filter_points_from); 
            }
                else
            {
                $query->where('tbl.pointcoupon_value = '.(int) $filter_points_from);
            }
        }
        if (strlen($filter_points_to))
        {
            $query->where('tbl.pointcoupon_value <= '.(int) $filter_points_to);
        }
        
        if ($filter_code) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_code ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.pointcoupon_code) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
    }
    
      protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        $item->link = 'index.php?option=com_ambra&view=pointcoupons&task=edit&id='.$item->pointcoupon_id;
        
       parent::prepareItem($item, $key, $refresh);
    }

    
}
