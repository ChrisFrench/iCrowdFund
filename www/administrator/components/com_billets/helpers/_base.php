<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if(!class_exists('BilletsHelperBase')){
class BilletsHelperBase extends DSCHelper
{
	
	/**
	 * Nicely format a number
	 * 
	 * @param $number
	 * @return unknown_type
	 */
    public static function number($number, $options='' )
	{
		$config = Billets::getInstance();
        $options = (array) $options;
        
        $thousands = isset($options['thousands']) ? $options['thousands'] : $config->get('currency_thousands', ',');
        $decimal = isset($options['decimal']) ? $options['decimal'] : $config->get('currency_decimal', '.');
        $num_decimals = isset($options['num_decimals']) ? $options['num_decimals'] : $config->get('currency_num_decimals', '2');
		
		$return = number_format($number, $num_decimals, $decimal, $thousands);
		return $return;
	}
	
	

	
	
	/**
	 * Displays a friendly notice to evil IE users
	 * :-)
	 * 
	 * @return html
	 */
	static  function browserNotice()
	{
	    $html = '';
	  
	    $browser = new DSCBrowser;
        if ( $browser->getBrowser() == DSCBrowser::BROWSER_IE && $browser->getVersion() >= '7' && Billets::getInstance()->get( 'display_ie8_notice', '1') ) 
        {
            // if IE, show 
            $html = "<div class='note_pink'>".JText::_('COM_BILLETS_BILLETS_IE8_WARNING')."</div>";            
        }

        return $html;
	}
}
}
