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

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgSystemAmbra_points extends JPlugin 
{
    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * 
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;
        
        jimport('joomla.filesystem.file');
        if (!class_exists('DSC')) 
        {
            if (!JFile::exists(JPATH_SITE.'/libraries/dioscouri/dioscouri.php')) {
                return false;
            }
            require_once JPATH_SITE.'/libraries/dioscouri/dioscouri.php';
        }
        if (JFile::exists(JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php')) 
        {
            $success = true;
            JLoader::register('Ambra', JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php');
            Ambra::load( 'Ambra', 'defines' );
            Ambra::load( 'AmbraQuery', 'library.query' );
            Ambra::load( 'AmbraHelperBase', 'helpers._base' );
        }
                
        return $success;
    }
    
    /**
     * 
     * @return unknown_type
     */
    function onAfterInitialise() 
    {
        $success = null;

        if (!$this->_isInstalled()) 
        {
        	
            return $success;
        }
        // $this->expirePoints();
        
        // get the option variable
        $option = JRequest::getVar( 'option' );
        
        // does a connector exist for this option?
        if (!$this->connectorExists( $option )) 
        {
            // if not, quietly exit
            return $success;
        }        

        // if connector exists, create its object
		$name = str_replace("_", "", $option);
        $classname = 'plgAmbraPoints'.$name;
        $object = new $classname( );
        
        // then run ->createLogEntry()
        $object->createLogEntry();

        // then quietly exit
        return $success;
    }
    
    /**
     * Checks if a component-specific connector exists 
     * 
     * @return boolean
     */
    function connectorExists( $option )
    {
        $success = false;
        
        jimport('joomla.filesystem.file');
        $file = JPATH_SITE.'/plugins/system/ambra_points'.DS.$option.'.php';
        if (JFile::exists( $file )) 
        {
            require_once( $file );
            $success = true;
        }
                
        return $success;
    }
    
    /**
     * For expiring points 
     */
    function  expirePoints()
    {    
    $execute_expire=false;	
    $last_expired_points = AmbraConfig::getInstance()->get('last_expired_points');
    $cd  = strtotime($last_expired_points);
	$date2= mktime(date('H',$cd),date('i',$cd),date('s',$cd),date('m',$cd),date('d',$cd),date('Y',$cd));
    $date1=time();
	$dateDiff = $date1 - $date2;
	$fullDays = floor($dateDiff/(60*60*24));
	$nd= mktime(date('H',$date1),date('i',$date1),date('s',$date1),date('m',$date1)-2,date('d',$date1),date('Y',$cd));
	$datebefore=date("Y-m-d h:i:s",$nd);
	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/tables' );
	$pointhistory = JTable::getInstance('Pointhistory', 'AmbraTable');
	
	if ($fullDays >=1)
	{	
		JLoader::import( 'com_ambra.helpers.user', JPATH_ADMINISTRATOR.DS.'components' );
		$expirationpoints = AmbraHelperUser:: getExpiration($datebefore);
		foreach($expirationpoints as $keys)
		{
			$pointhistory_id=$keys->pointhistory_id;
			
			$pointhistory->load( $pointhistory_id, 'pointhistory_id' );
			$pointhistory->expired='1' ;
			$pointhistory->save();
		}
	
	$execute_expire=true;
    
	}
	if($fullDays >=1 || $last_expired_points=="" )
	{
		$date = JFactory::getDate();
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components/com_ambra/tables' );
		$config = JTable::getInstance( 'Config', 'AmbraTable' );
		$config->load( array( 'config_name'=>'last_expired_points') );
		$config->config_name = 'last_expired_points';
		$config->value = $date->toMySQL();
		$config->save();
		
	}
    }
}
