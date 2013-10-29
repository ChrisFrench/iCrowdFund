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

class AmbraHelperCampaignMonitor extends AmbraHelperBase
{
    /**
     * Check if extension is installed
     * 
     * @return unknown_type
     */
    function isInstalled()
    {
        $success = false;
        
        // because there is no local joomla extension, this method simply checks that all settings are present
        $campaignmonitor_registration = AmbraConfig::getInstance()->get('campaignmonitor_registration');
        $campaignmonitor_api_key      = AmbraConfig::getInstance()->get('campaignmonitor_api_key');
        $campaignmonitor_listid       = AmbraConfig::getInstance()->get('campaignmonitor_listid');
        
        if (!empty($campaignmonitor_registration) && !empty($campaignmonitor_api_key) && !empty($campaignmonitor_listid))
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
        $campaignmonitor_listid = AmbraConfig::getInstance()->get('campaignmonitor_listid');
        if (!empty($campaignmonitor_listid))
        {
            return array( $campaignmonitor_listid );
        }
        return array();       
    }
}