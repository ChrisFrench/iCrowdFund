<?php
/**
 * @version 1.5
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::import( 'com_ambra.tables._basenested', JPATH_ADMINISTRATOR.DS.'components' );

class AmbraTableRoles extends AmbraTableNested 
{
    /**
     * Constructs the object
     * @param $db
     * @return unknown_type
     */
    function AmbraTableRoles ( &$db ) 
    {
        $tbl_key    = 'role_id';
        $tbl_suffix = 'roles';
        $this->set( '_suffix', $tbl_suffix );
        $name       = 'ambra';
        
        parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );   
    }
    
    /**
     * Checks the integrity of the object before a save 
     * @return unknown_type
     */
    function check()
    {       
        $this->filterHTML( 'role_name' );
        if (empty($this->role_name))
        {
            $this->setError( JText::_( "Name Required" ) );
            return false;
        }
        
        return true;
    }
    
    /**
     * Attempts base getRoot() and if it fails, creates root entry
     * 
     * @see ambra/admin/tables/AmbraTableNested#getRoot()
     */
    function getRoot()
    {
        if (!$result = parent::getRoot())
        {
            // add root
            $database = $this->_db;
            $query = "INSERT IGNORE INTO `#__ambra_roles` SET `role_name` = 'All Roles', `role_description` = '', `parent_id` = '0', `lft` = '1', `rgt` = '2', `role_enabled` = '1', `isroot` = '1'; ";
            $database->setQuery( $query );
            if ($database->query())
            {
                $insertid = $database->insertid();                  
                JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
                $table = JTable::getInstance('Roles', 'AmbraTable');
                $table->load( $insertid );
                $table->rebuildTree();
                $result = $table;
            }
                else
            {
                $this->setError( $database->getErrorMsg() );
                return false;
            }
        }
        
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see ambra/admin/tables/AmbraTableNested#getTree($parent, $enabled, $indent)
     */
    function getTree( $parent=null, $enabled='1', $indent=' ' )
    {
        if (intval($enabled) > 0)
        {
            $enabled_query = "
            AND node.role_enabled = '1'
            AND NOT EXISTS
            (
                SELECT 
                    * 
                FROM 
                    {$this->_tbl} AS tbl 
                WHERE 
                    tbl.lft < node.lft AND tbl.rgt > node.rgt
                    AND tbl.role_enabled = '0' 
                ORDER BY 
                    tbl.lft ASC
            )
            ";           
        }
        
        $key = $this->getKeyName();     
        $query = "
            SELECT node.*, COUNT(parent.{$key}) AS level, CONCAT( REPEAT('{$indent}', COUNT(parent.role_name) - 1), node.role_name) AS name
            FROM {$this->_tbl} AS node,
            {$this->_tbl} AS parent
            WHERE node.lft BETWEEN parent.lft AND parent.rgt
            $enabled_query
            GROUP BY node.{$key}
            ORDER BY node.lft;
        ";
        $this->_db->setQuery( $query );
        $return = $this->_db->loadObjectList();
        return $return;     
    }
    
    /**
     * 
     * @param $parent
     * @return unknown_type
     */
    function updateParents( $parent=null )
    {
        $key = $this->getKeyName();
        if ($parent === null)
        {
            $root = $this->getRoot();
            if ($root === false) 
            {
                return false;
            }
            $parent = $root->$key;
        }
        
        $database = $this->_db;
        $tbl = $this->getTableName();
        
        $query = "
            UPDATE 
                {$tbl} AS tbl
            SET
                tbl.parent_id = '{$parent}'
            WHERE 
                tbl.parent_id = '0'
            AND 
                tbl.{$key} != '{$parent}'
        ";
        $database->setQuery( $query );
        $database->query();     
    }
}
