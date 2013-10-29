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

class AmbraModelUserRelations extends AmbraModelBase 
{	
	protected function _buildQueryWhere(&$query)
    {
    	$filter_id = $this->getState('filter_id');
        $filter_user = $this->getState('filter_user');
        $filter_relation = $this->getState('filter_relation');
        $filter_relations = $this->getState('filter_relations');
       	$filter_user_from = $this->getState('filter_user_from');       	
        $filter_user_to = $this->getState('filter_user_to');
        
    	if (strlen($filter_id))
        {
            $query->where('tbl.userrelation_id = '.(int) $filter_id);
       	}
       	
    	if (strlen($filter_user))
        {
            $query->where(
                '(tbl.user_id_from = '.(int) $filter_user .' OR tbl.user_id_to = '.(int) $filter_user .' )'
            );
        }
        
    	if (strlen($filter_user_from))
        {
            $query->where('tbl.user_id_from = '.(int)JFactory::getUser($filter_user_from)->id);
       	}
       	
    	if (strlen($filter_user_to))
        {
            $query->where('tbl.user_id_to = '.(int) $filter_user_to);
        }
        
    	if (strlen($filter_relation))
        {
            $query->where("tbl.relation_type = '$filter_relation'");
        }
        
        if (is_array($filter_relations))
        {
            $query->where("tbl.relation_type IN ('".implode("', '", $filter_relations)."')");
        }
    }
    
	protected function _buildQueryJoins(&$query)
    {
        $query->join('LEFT', '#__users AS u_from ON u_from.id = tbl.user_id_from');
        $query->join('LEFT', '#__users AS u_to ON u_to.id = tbl.user_id_to');   
    }
    
    protected function _buildQueryFields(&$query)
    {
        $fields = array();
        $fields[] = " u_from.name as user_name_from ";
        $fields[] = " u_from.username as user_username_from ";
        $fields[] = " u_from.email as user_email_from ";
        
        $fields[] = " u_to.name as user_name_to ";
        $fields[] = " u_to.username as user_username_to ";
        $fields[] = " u_to.email as user_email_to ";
        
        $query->select( $this->getState( 'select', 'tbl.*' ) );
        $query->select( $fields );
    }
    
	
}