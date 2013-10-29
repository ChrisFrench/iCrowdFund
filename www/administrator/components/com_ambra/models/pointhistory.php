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

class AmbraModelPointHistory extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_enabled = $this->getState('filter_enabled');
        $filter_user        = $this->getState('filter_user');
        $filter_date_from   = $this->getState('filter_date_from');
        $filter_date_to     = $this->getState('filter_date_to');
        $filter_datetype    = $this->getState('filter_datetype');
        $filter_points_from = $this->getState('filter_points_from');
        $filter_points_to   = $this->getState('filter_points_to');
        $filter_rule        = $this->getState('filter_rule');
        $filter_coupon        = $this->getState('filter_coupon');
        
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.pointhistory_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.pointhistory_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.pointhistory_enabled = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.pointhistory_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.pointhistory_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.pointhistory_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(user.id) LIKE '.$key;
            $where[] = 'LOWER(user.name) LIKE '.$key;
            $where[] = 'LOWER(user.username) LIKE '.$key;
            $where[] = 'LOWER(user.email) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }

        if (strlen($filter_user))
        {
            $query->where('tbl.user_id = '.(int)$filter_user);
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
                $query->where('tbl.points >= '.(int) $filter_points_from); 
            }
                else
            {
                $query->where('tbl.points = '.(int) $filter_points_from);
            }
        }
        if (strlen($filter_points_to))
        {
            $query->where('tbl.points <= '.(int) $filter_points_to);
        }

        if (strlen($filter_rule))
        {
            $query->where('tbl.pointrule_id = '.(int)$filter_rule);
        }
        
        if (strlen($filter_coupon))
        {
            $query->where('tbl.pointcoupon_id = '.(int)$filter_coupon);
        }
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__users AS user ON tbl.user_id = user.id');
        $query->join('LEFT', '#__ambra_pointrules AS pr ON tbl.pointrule_id = pr.pointrule_id');
    }
    
    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " user.* ";
        $fields[] = " pr.pointrule_name ";
        $fields[] = " pr.pointrule_description ";
        $fields[] = " pr.pointrule_scope ";
        $fields[] = " pr.pointrule_event ";
                
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
    
    protected function prepareItem( &$item, $key=0, $refresh=false )
    {
       //$nullDate = JFactory::getDBO()->getNullDate();
       $item->link = 'index.php?option=com_ambra&view=pointhistory&task=edit&id='.$item->pointhistory_id;
        // convert working dates to localtime for display
       //$item->created_date = ($item->created_date != $nullDate) ? JHTML::_( "date", $item->created_date, 'Y-m-d h:i:A' ) : $item->created_date;
       //$item->expire_date = ($item->expire_date != $nullDate) ? JHTML::_( "date", $item->expire_date, 'Y-m-d h:i:A' ) : $item->expire_date;
            // 
        if (empty($item->pointhistory_name)) { $item->pointhistory_name = $item->pointrule_name; }
        if (empty($item->pointhistory_description)) { $item->pointhistory_description = $item->pointrule_description; } 

       parent::prepareItem($item, $key, $refresh);
    }

    
}
