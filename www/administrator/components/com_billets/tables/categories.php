<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );


Billets::load( 'BilletsTableNested', 'tables._basenested' );

class BilletsTableCategories extends BilletsTableNested 
{
	function BilletsTableCategories ( &$db ) 
	{
		$tbl_key 	= 'id';
		$tbl_suffix = 'categories';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'billets';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	/**
	 * Checks the integrity of the object before a save 
	 * @return unknown_type
	 */
	function check()
	{		
		$db			= $this->getDBO();
		$nullDate	= $db->getNullDate();
		if (empty($this->created_datetime) || $this->created_datetime == $nullDate)
		{
			$date = JFactory::getDate();
			$this->created_datetime = $date->toMysql();
		}
		$this->filterHTML( 'title' );
		if (empty($this->title))
		{
			$this->setError( JText::_('COM_BILLETS_TITLE_REQUIRED') );
			return false;
		}
		
		return true;
	}
	
	/**
	 * Attempts base getRoot() and if it fails, creates root entry
	 * 
	 * @see billets/admin/tables/TiendaTableNested#getRoot()
	 */
	function getRoot()
	{
		if (!$result = parent::getRoot())
		{
			// add root
			$database = $this->_db;
			$query = "INSERT IGNORE INTO `#__billets_categories` SET `title` = 'ROOT', `description` = 'root', `parentid` = '0', `lft` = '1', `rgt` = '2', `enabled` = '1', `isroot` = '1'; ";
			$database->setQuery( $query );
			if ($database->query())
			{
				$insertid = $database->insertid();					
				JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
				$table = JTable::getInstance('Categories', 'BilletsTable');
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
				tbl.parentid = '{$parent}'
			WHERE 
				tbl.parentid = '0'
			AND 
				tbl.{$key} != '{$parent}'
		";
		$database->setQuery( $query );
		$database->query();		
	}

    /**
     * The method is overridden
     * @param $change direction of position change
     * @param $where is there for backword compatiblity
     * @return category itself or false
     */
    function move($change, $where='')
    {
        settype($change, 'int');
        if ($change !== 0)
        {
            $temporary_categories   = array();
            
            $database = JFactory::getDBO();
            
            // Get all siblings
            // This array will allow us to track the up/down of categories
            // keeping their parent relationship intact
            $query = "
                SELECT 
                    tbl.*
                FROM 
                    {$this->_tbl} AS tbl 
                WHERE 
                    tbl.parentid = '{$this->parentid}'
                ORDER BY
                    tbl.lft ASC
            ";
            $database->setQuery( $query );
            $children = $database->loadObjectList();
            
            $curr_index = 0;    // Current category's position in our array
            $counter    = 0;
            foreach($children as $child)
            {
                $temporary_categories[] = $child;
                if ($child->id==$this->id) {
                    $curr_index = $counter;
                }
                $counter++;
            }
            $new_position   = $curr_index + $change;
            if (isset($temporary_categories[$new_position]))
            {
                // We have got the category that we want to move up/down,
                // we need to replace their lft & rgt columns as well, because
                // all the sorting are based upon lft column
                $replacing_item_id  = $temporary_categories[$new_position]->id;
                $replacing_ordering = $temporary_categories[$new_position]->ordering;
                $replacing_lft      = $temporary_categories[$new_position]->lft;
                $replacing_rgt      = $temporary_categories[$new_position]->rgt;
                
                $query  = " UPDATE
                                {$this->_tbl}
                            SET
                                lft     = '{$this->lft}',
                                rgt     = '{$this->rgt}',
                                ordering= '{$this->ordering}'
                            WHERE
                                id      = '{$replacing_item_id}'";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $this->setError( $database->getErrorMsg() );
                    return false;
                }

                $query  = " UPDATE
                                {$this->_tbl}
                            SET
                                lft     = '{$replacing_lft}',
                                rgt     = '{$replacing_rgt}',
                                ordering= '{$replacing_ordering}'
                            WHERE
                                id      = '{$this->id}'";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $this->setError( $database->getErrorMsg() );
                    return false;
                }

                $this->rebuildTree(); // otherwise the tree will be disturbed
            }
        }
        return $this;
    }
}
