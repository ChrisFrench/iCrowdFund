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

class AmbraHelperAmigos extends AmbraHelperBase
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
        $filePath = JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS."defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Amigos') )
            { 
                JLoader::register( "Amigos", JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS."defines.php" );
                JLoader::register( "AmigosConfig", JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS."defines.php" );
                JLoader::register( "TableAccounts", JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS.'tables'.DS."accounts.php" );
                JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_amigos'.DS.'tables' );
            }
        }           
        return $success;
    }
    
    /**
     * Checks if the user is an affiliate
     */
    function isAffiliate( $user_id )
    {
        if (empty($user_id))
        {
            return false;
        }
        
        $db = JFactory::getDBO();
        $db->setQuery( "SELECT tbl.id FROM #__amigos_accounts AS tbl WHERE tbl.userid = '$user_id' " );
        if ($result = $db->loadResult())
        {
            return true;
        }
        return false;
    }
}