
<?php
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosViewBase extends DSCViewSite
{
    function display($tpl=null)
    {
        $config = Tos::getInstance();
        if ($config->get('include_site_css')) 
        {
            JHTML::_('stylesheet', 'common.css', 'media/com_tos/css/');
        }
        JHTML::_('script', 'common.js', 'media/com_tos/js/');
    
        parent::display($tpl);
    }
}