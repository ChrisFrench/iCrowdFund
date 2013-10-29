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

class AmbraHelperBase extends DSCHelper
{
    /**
     * constructor
     * make it protected where necessary
     */
    function __construct()
    {
        parent::__construct();
    }
    
     /**
    * Generate an html message
    * used for validation errors
    * 
    * @param string message
    * @return html message
    */


   function generateMessage($msg, $include_li=true)
   {
       $html = '<dl id="system-message">
                   <dt class="notice">notice</dt>
                   <dd class="notice message fade">
                       <ul style="padding: 10px 10px 10px 25px;">';
       
       $html .= $msg;
       
       $html .= "</ul>
                   </dd>
                   </dl>";
       
       return $html;
   }
   
   /**
    * Generates a validation message
    * 
    * @param unknown_type $text
    * @param unknown_type $type
    * @return unknown_type
    */
   function validationMessage( $text, $type='fail' )
   {
       switch (strtolower($type))
       {
           case "success":
               $src = Ambra::getUrl( 'images' ).'accept_16.png';
               $html = "<div class='ambra_validation'><img src='$src' alt='".JText::_( "Success" )."'><span class='validation-success'>".JText::_( $text )."</span></div>";
               break;
           default:
               $src = Ambra::getUrl( 'images' ).'remove_16.png';
               $html = "<div class='ambra_validation'><img src='$src' alt='".JText::_( "Error" )."'><span class='validation-fail'>".JText::_( $text )."</span></div>";
               break;
       }
       return $html;
    }

    

}