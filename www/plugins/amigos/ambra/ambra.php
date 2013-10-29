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

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgAmigosAmbra extends JPlugin 
{
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
    /**
     * Check if is installed
     * 
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR."/components/com_ambra/defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Ambra') )
            { 
                JLoader::register('Ambra', JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php');
            }
        }           
        return $success;
    }
		
    /**
     * Method is after an affiliate account is saved 
     * 
     * @param object $row  an AmigosTableAccounts object
     * @return null
     */
	function onAfterSaveAccounts( $row )
	{
		$success = null;
		
	    if (!$this->_isInstalled())
        {
            return $success;    
        }
		
        if (!empty($row->_isNew) && !empty($row->enabled) && !empty($row->approved))
        {
            $helper = Ambra::getClass( "AmbraHelperPoint", 'helpers.point' );
            if ($helper->createLogEntry( $row->userid, 'com_amigos', 'onAfterSaveAccounts' ))
            {
                JFactory::getApplication()->enqueueMessage( $helper->getError() );
            }
        }
        
		return $success;
	}
}
