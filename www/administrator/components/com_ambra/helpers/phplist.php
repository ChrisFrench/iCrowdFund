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

Ambra::load( 'AmbraHelperBase', 'helpers._base' );

class AmbraHelperPhplist extends AmbraHelperBase
{
    /**
     * Check if extension is installed
     * 
     * @return unknown_type
     */
    function isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.DS."components".DS."com_phplist".DS."defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Phplist') )
            { 
                JLoader::register( "Phplist", JPATH_ADMINISTRATOR.DS."components".DS."com_phplist".DS."defines.php" );
                JLoader::register( "PhplistConfig", JPATH_ADMINISTRATOR.DS."components".DS."com_phplist".DS."defines.php" );
            }
        }           
        return $success;
    }

    /**
     * Gets the list of newsletters to include in the registration form
     */
    function getNewsletters()
    {
        $return = array();
        $csv = AmbraConfig::getInstance()->get( 'phplist_newsletters_csv' );
        if (empty($csv))
        {
            return $return;
        }
        $csv_list = @preg_replace( '/\s/', '', $csv );
        $csv_array = explode( ',', $csv_list );
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phplist'.DS.'tables' );
        $model = Ambra::getClass( "PhplistModelNewsletters", 'models.newsletters', array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_phplist' ));
        $query = $model->getQuery();
        $query->where( "tbl.id IN ('".implode( "', '", $csv_array )."')" );
        $model->setQuery( $query );
        if ($newsletters = $model->getList())
        {
            return $newsletters;
        }
        return array();       
    }
}