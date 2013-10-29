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



class TiendaViewCampaigns extends DSCViewCSV 
{	
	

	/**
     * Displays a layout file
     *
     * @param unknown_type $tpl
     * @return unknown_type
     */
    public function display($tpl = null) 
    {
           // Load the model
       // $model = $this->getModel();

        $items = $this->items;
        $this->assignRef( 'items', $items );

        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/csv');
        JResponse::setHeader('Pragma','public');
        JResponse::setHeader('Expires','0');
        JResponse::setHeader('Cache-Control','must-revalidate, post-check=0, pre-check=0');
        JResponse::setHeader('Cache-Control','public', false);
        JResponse::setHeader('Content-Description','File Transfer');
        JResponse::setHeader('Content-Disposition','attachment; filename='.$this->csvFilename);

        JError::setErrorHandling(E_ALL,'ignore');
        if(is_null($tpl)) $tpl = 'csv';
        $result = $this->loadTemplate($tpl);
        JError::setErrorHandling(E_WARNING,'callback');

        if($result instanceof JException) {
            // Default CSV behaviour in case the template isn't there!
            if(empty($items)) return;

          //  if($this->csvHeader) {
                $item = array_pop($items);
                $keys = get_object_vars($item);
                //var_dump( $keys);
                //die();
                $items[] = $item;
                reset($items);

                $csv = array();
                foreach($keys as $k => $v) {
                    
                        $csv[] = '"' . str_replace('"', '""', $k) . '"';
                    
                }
                echo implode(",", $csv) . "\r\n";
            //}

            foreach($items as $item) {
                $csv = array();
                $keys = get_object_vars($item);
                
                foreach($item as $k => $v) {
                     if(!is_object($v) && !is_array($v)) {
                        $csv[] = '"' . str_replace('"', '""', $v) . '"';
                    }
                    
                }
                echo implode(",", $csv) . "\r\n";
            }

            return;
        }
    }
	
    
}
  