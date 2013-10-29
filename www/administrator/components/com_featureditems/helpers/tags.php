<?php
/**
 * @package Featureditems
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class FeatureditemsHelperTags extends JObject 
{
    /**
     * Checks if extension is installed
     *
     * @return boolean
     */
    public function isInstalled()
    {
        $success = false;
    
        jimport('joomla.filesystem.file');
        if (JFile::exists( JPATH_ADMINISTRATOR . '/components/com_tags/defines.php' ))
        {
            JLoader::register( "Tags", JPATH_ADMINISTRATOR . "/components/com_tags/defines.php" );
            
            $parentPath = JPATH_ADMINISTRATOR . '/components/com_tags/helpers';
            DSCLoader::discover('TagsHelper', $parentPath, true);
            
            $parentPath = JPATH_ADMINISTRATOR . '/components/com_tags/library';
            DSCLoader::discover('Tags', $parentPath, true);
            
            if ($this->getScope()) 
            {
                $success = true;
            }
        }
        return $success;
    }
    
    /**
     * 
     * @param unknown_type $scope
     */
    public function getScope( $scope='com_featureditems.item' )
    {
        // TODO cache this
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_tags/tables' );
        
        $table = JTable::getInstance( 'Scopes', 'TagsTable' );
        $table->load( array( 'scope_identifier'=>$scope ) );
        
        if (empty($table->scope_id)) 
        {
            switch ($scope) 
            {
                case "com_featureditems.item":
                    $table->scope_name			   = 'Featured Item';
                    $table->scope_url              = 'index.php?option=com_featureditems&view=items&task=edit&id=';
                    $table->scope_table            = '#__featureditems_items';
                    $table->scope_table_field      = 'item_id';
                    $table->scope_table_name_field = 'item_short_title';
                    break;
            }
            
            $table->scope_identifier       = $scope;
            $table->save();
        }
        
        return $table;
    }
    
    /**
     * Gets the standard html form for adding tags to an item
     *  
     * @param unknown_type $identifier
     * @param unknown_type $scope
     * @return string
     */
    public function getForm( $identifier, $scope='com_featureditems.item' )
    {
        $html = '';
        
        if (!$this->isInstalled())
        {
            return $html;
        }
        
        $helper = new TagsHelperTags();
        $html = $helper->getForm( $identifier, $scope );
        
        return $html;
    }
}