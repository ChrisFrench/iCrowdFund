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

class AmbraModelActions extends AmbraModelBase 
{
    protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
        $filter_id_from = $this->getState('filter_id_from');
        $filter_id_to   = $this->getState('filter_id_to');
        $filter_name    = $this->getState('filter_name');
        $enabled        = $this->getState('filter_enabled');

        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');

            $where = array();
            $where[] = 'LOWER(tbl.action_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.action_name) LIKE '.$key;
            
            $query->where('('.implode(' OR ', $where).')');
        }
        if (strlen($enabled))
        {
            $query->where('tbl.action_enabled = '.(int)$enabled);
        }
        if (strlen($filter_id_from))
        {
            if (strlen($filter_id_to))
            {
                $query->where('tbl.action_id >= '.(int) $filter_id_from); 
            }
                else
            {
                $query->where('tbl.action_id = '.(int) $filter_id_from);
            }
        }
        if (strlen($filter_id_to))
        {
            $query->where('tbl.action_id <= '.(int) $filter_id_to);
        }
        if (strlen($filter_name))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_name ) ) ).'%');
            $query->where('LOWER(tbl.action_name) LIKE '.$key);
        }
    }

    protected function prepareItem( &$item, $key=0, $refresh=false )
    {
        $item->link = 'index.php?option=com_ambra&view=actions&task=edit&id='.$item->action_id;
        
       parent::prepareItem($item, $key, $refresh);
    }
            
    
}
