<?php
/**
 * @package    Ambra
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class modAmbraCouponsHelper extends JObject
{
    var $items = array();
    var $params = null;

    /**
     * Constructor to set the object's params
     * 
     * @param $params
     * @return unknown_type
     */
    function __construct( $params )
    {
        parent::__construct();
        $this->params = $params;
    }
    
    function isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php';
        if (JFile::exists($filePath))
        {
            JLoader::register('Ambra', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php');
            Ambra::load( 'AmbraConfig', 'defines' );
            Ambra::load( 'AmbraQuery', 'library.query' );
            Ambra::load( 'AmbraHelperBase', 'helpers._base' );
            $success = true;
        }           
        return $success;
    }

}