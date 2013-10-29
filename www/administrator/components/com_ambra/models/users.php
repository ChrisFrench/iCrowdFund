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

class AmbraModelUsers extends AmbraModelBase 
{	
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_enabled      = $this->getState('filter_enabled');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $filter_username    = $this->getState('filter_username');
        $filter_email    = $this->getState('filter_email');
        $filter_date_from   = $this->getState('filter_date_from');
        $filter_date_to     = $this->getState('filter_date_to');
        $filter_datetype    = $this->getState('filter_datetype');
        $filter_online    = $this->getState('filter_online');
        $filter_flex        = $this->getState('filter_flex');
        $filter_profile = $this->getState('filter_profile');
        $filter_points_from = $this->getState('filter_points_from');
        $filter_points_to   = $this->getState('filter_points_to');
        $filter_pointtype    = $this->getState('filter_pointtype');
        
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.id) LIKE '.$key;
            $where[] = 'LOWER(tbl.name) LIKE '.$key;
            $where[] = 'LOWER(tbl.username) LIKE '.$key;
            $where[] = 'LOWER(tbl.email) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($filter_enabled))
        {
            $query->where('tbl.block = '.(int)$filter_enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.id >= '.(int) $filter_id_from);
            }
                else
            {
                $query->where('tbl.id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.id <= '.(int) $filter_id_to);
        }
        if ($filter_name) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.name) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
        if ($filter_flex) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_flex ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.name) LIKE '.$key;
            $where[] = 'LOWER(tbl.username) LIKE '.$key;
            $where[] = 'LOWER(tbl.email) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
        if ($filter_username) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_username ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.username) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        if ($filter_email) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_email ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.email) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }

        if (strlen($filter_date_from) && !preg_match('/[^\d: \-]/', $filter_date_from))
        {
            switch ($filter_datetype)
            {
                case "visited":
                    $query->where("tbl.lastvisitDate >= '".$filter_date_from."'");
                  break;
                case "registered":
                default:
                    $query->where("tbl.registerDate >= '".$filter_date_from."'");
                  break;
            }
        }
        if (strlen($filter_date_to) && !preg_match('/[^\d: \-]/', $filter_date_to))
        
        {
            switch ($filter_datetype)
            {
                case "visited":
                    $query->where("tbl.lastvisitDate <= '".$filter_date_to."'");
                  break;
                case "registered":
                default:
                    $query->where("tbl.registerDate <= '".$filter_date_to."'");
                  break;
            }
        }
        
        if (strlen($filter_profile))
        {
            if (empty($filter_profile))
            {
                $query->where("( data.profile_id = '0' OR data.profile_id IS NULL )" );
            }
                else
            {
                $query->where('data.profile_id = '.(int) $filter_profile);    
            }
        }
        
        if (strlen($filter_online))
        {
            if ($filter_online == '1')
            {
                $query->where('session.session_id');    
            }
                else
            {
                $query->where('session.session_id IS NULL');
            }
        }
        
        if (strlen($filter_points_from))
        {
            switch ($filter_pointtype)
            {
                case "total":
                    $field = "data.points_total";
                  break;
                case "current":
                default:
                    $field = "data.points_current";
                  break;
            }
            
            if (strlen($filter_points_to))
            {
                $query->where( $field.' >= '.(int) $filter_points_from); 
            }
                else
            {
                $query->where( $field.' = '.(int) $filter_points_from);
            }
        }
        if (strlen($filter_points_to))
        {
            switch ($filter_pointtype)
            {
                case "total":
                    $field = "data.points_total";
                  break;
                case "current":
                default:
                    $field = "data.points_current";
                  break;
            }
            $query->where( $field.' <= '.(int) $filter_points_to);
        }
 
    }
    
    protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__ambra_userdata AS data ON tbl.id = data.user_id');
        $query->join('LEFT', '#__ambra_profiles AS p ON data.profile_id = p.profile_id');
        $filter_online    = $this->getState('filter_online');
        if (strlen($filter_online))
        {
            $query->join('LEFT', '#__session AS session ON tbl.id = session.userid');
        }
    }
    
    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " data.* ";
        $fields[] = " p.profile_name ";
        $fields[] = " p.profile_max_points_per_day ";

        $filter_online    = $this->getState('filter_online');
        if (strlen($filter_online))
        {
            $fields[] = " session.session_id ";
        }
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );     
        $query->select( $fields );
    }
    
      protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        $template = 'SELECT s.session_id FROM #__session AS s WHERE s.userid = %d LIMIT 1';
        $database = JFactory::getDBO();
        
        $item->link = 'index.php?option=com_ambra&view=users&task=view&id='.$item->id;
            
            // get login status
        $query = sprintf( $template, intval( $item->id ) );
        $database->setQuery( $query );
        $item->session_id = $database->loadResult();            

       parent::prepareItem($item, $key, $refresh);
    }

    
}
