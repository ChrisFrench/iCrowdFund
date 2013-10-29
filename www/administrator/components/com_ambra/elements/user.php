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

if ( !class_exists('Ambra') ) 
    JLoader::register( "Ambra", JPATH_ADMINISTRATOR."/components/com_ambra/defines.php" );



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

class JFakeElementAmbraUser extends JFakeElementBase
{
var	$_name = 'ambrauser';
	
	public function getInput() 
	{
		return JFakeElementAmbraUser::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}
	

	public function fetchElement($name, $value, &$node, $control_name)
	{
	    
		$html = "";
		$doc 		= JFactory::getDocument();
		$fieldName	= $control_name ? $control_name.'['.$name.']' : $name;
		$title = JText::_('COM_AMBRA_SELECT_USER');
		if ($value) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_ambra/tables');
			$table = JTable::getInstance('Users', 'AmbraTable');
			$table->load($value);
			$title = $table->name;
		}
		else
		{
			$title=JText::_('COM_AMBRA_SELECT_USER');
		}

 		$js = "
			Dsc.selectelementproduct = function(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;";
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= 'window.parent.SqueezeBox.close()';
		}
		else {
			$js .= 'document.getElementById(\'sbox-window\').close()';
		}
	$js.=	"}";
		
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_ambra&controller=elementuser&view=elementuser&tmpl=component&object='.$name;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('COM_AMBRA_SELECT_USER').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('OM_AMBRA_SELECT').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';
		
		return $html;
	}
	
	
	
}

if(version_compare(JVERSION,'1.6.0','ge')) {
	class JFormFieldAmbraUser extends JFakeElementAmbraUser {}
} else {
	class JElementAmbraUser extends JFakeElementAmbraUser {}
}


?>