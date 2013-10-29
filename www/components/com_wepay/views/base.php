
<?php
/**
 *
 *
 * @version 1.5
 * @package Wepay
 * @author  Dioscouri Design
 * @link  http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class WepayViewBase extends DSCViewSite
{
    function display( $tpl=null ) {
        $config = Wepay::getInstance();
        if ( $config->get( 'include_site_css' ) ) {
            JHTML::_( 'stylesheet', 'common.css', 'media/com_wepay/css/' );
        }
        JHTML::_( 'script', 'common.js', 'media/com_wepay/js/' );

        parent::display( $tpl );
    }
}
