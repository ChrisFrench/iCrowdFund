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

class AmbraHelperAllchimp extends AmbraHelperBase
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
        $filePath = JPATH_SITE.DS."components".DS."com_allchimp".DS."allchimp.php";
        if (JFile::exists($filePath))
        {
            $success = true;
        }           
        return $success;
    }

    /**
     * Gets the list of newsletters to include in the registration form
     */
    function getNewsletters()
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_allchimp'.DS.'tables' );
        $model = Ambra::getClass( "allChimpModelallChimp", 'models.allchimp', array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_allchimp' ));
        if ($newsletters = $model->get_all_publish_list())
        {
            return $newsletters;
        }
        return array();       
    }
}