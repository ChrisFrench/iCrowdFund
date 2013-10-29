<?php
/**
 * @package Dioscouri
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemJfactoryoverride extends JPlugin
{
    function onAfterInitialise()
    {
        if (!class_exists('DSC'))
        {
            if (!JFile::exists(JPATH_SITE.'/libraries/dioscouri/dioscouri.php')) {
                return false;
            }
            require_once JPATH_SITE.'/libraries/dioscouri/dioscouri.php';
        }

        DSC::loadLibrary();

        if ( !class_exists('Messagebottle') ) {
            JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
        }

        JFactory::$mailer = Messagebottle::getClass('Bottle', 'helpers.bottle');
    }

}
?>