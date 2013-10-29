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
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgAmbraAmbra extends JPlugin 
{   
    var $params = null;

    function __construct(& $subject, $config)
    { 
        parent::__construct($subject, $config);
        $this->_isInstalled();
 
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
                $this->params = Ambra::getInstance();
            }
        }           
        return $success;
    }
        
    function onDisplayViewSiteComponentAmbra() {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();    
       
           // Add related CSS to the <head>
        if ($doc->getType() == 'html')
        {

            jimport('joomla.filesystem.file');
            if( $this->params->get('enable_css', '1')) {
                if (JFile::exists(JPATH_SITE.'/templates/'.$app->getTemplate().'/css'.'/ambra.css')) {
                    $doc->addStyleSheet(JURI::root(true).'/templates/'.$app->getTemplate().'/css/ambra.css');
                } else {
                   $doc->addStyleSheet(JURI::root(true).'/media/com_ambra/css/ambra.css'); 
                }
            }

            if( $this->params->get('enable_js', '1')) {
                if (JFile::exists(JPATH_SITE.'/templates/'.$app->getTemplate().'/js/ambra.js')) {
                    $doc->addScript(JURI::root(true).'/templates/'.$app->getTemplate().'/js/ambra.js');
                } else {
                    $doc->addScript(JURI::root(true).'/media/com_ambra/js/ambra.js'); 
                }
            }    
        }

    }
}
