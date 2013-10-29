<?php
/**
 * @version	1.5
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

if ( !class_exists('Wepay') ) 
    JLoader::register( "Wepay", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );

Wepay::load('WepayHelperCampaign', 'helpers.campaign');
if(!class_exists('JFakeElementBase')) {
	if(version_compare(JVERSION,'1.6.0','ge')) {
		class JFakeElementBase extends JFormField {
			// This line is required to keep Joomla! 1.6/1.7 from complaining
			public function getInput() {
			}
		}
	} else {
		class JFakeElementBase extends JElement {}
	}
}

class JFakeElementCampaignCategory extends JFakeElementBase
{
		var	$_name = 'CampaignCategory';

	public function getInput() 
	{
		$list = WepayHelperCampaign::getSectorsSelectList(@$this->value, $this->options['control'].$this->name); 
		//$list = Wepay::getClass( 'WepaySelect', 'library.select' )->category($this->value, $this->options['control'].$this->name, '', $this->options['control'].$this->name, false, false, 'Select Category', '', true );
		return $list;
		
	
	}
	


	function fetchElement($name, $value, &$node, $control_name)
	{
		 $list = WepayHelperCampaign::getSectorsSelectList(@$value, $control_name.'['.$name.']'); 	
		  // $list = Wepay::getClass( 'WepaySelect', 'library.select' )->category($value, $control_name.'['.$name.']', '', $control_name.$name, false, false, 'Select Category', '', true );
		
	   
		return $list;
	}
	
	
	
}

if(version_compare(JVERSION,'1.6.0','ge')) {
	class JFormFieldCampaignCategory extends JFakeElementCampaignCategory {}
} else {
	class JElementCampaignCategory extends JFakeElementCampaignCategory {}
}



?>