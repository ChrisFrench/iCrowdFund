<?php
/**
* @package		Wepay
* @copyright	Copyright (C) 2012 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class WepaySelect extends DSCSelect
{
	
/**
     * A boolean radiolist that uses bootstrap
     *  
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $selected
     * @param unknown_type $yes
     * @param unknown_type $no
     * @param unknown_type $id
     * @return string
     */
	public static function btbooleanlist($name, $attribs = null, $selected = null, $yes = 'JYES', $no = 'JNO', $id = false)
	{
	    JHTML::_('script', 'bootstrapped-advanced-ui.js', 'media/dioscouri/js/');
	    JHTML::_('stylesheet', 'bootstrapped-advanced-ui.css', 'media/dioscouri/css/');
	    $arr = array(JHtml::_('select.option', '0', JText::_($no)), JHtml::_('select.option', '1', JText::_($yes)));
	    $html = '<div class="control-group"><div class="controls"><fieldset id="'.$name.'" class="radio btn-group">';
	    $html .=  WepaySelect::btradiolist( $arr, $name, $attribs, 'value', 'text', (int) $selected, $id);
	    $html .= '</fieldset></div></div>';

	    return $html;
	}
	
	/**
	 * A standard radiolist that uses bootstrap
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $name
	 * @param unknown_type $attribs
	 * @param unknown_type $optKey
	 * @param unknown_type $optText
	 * @param unknown_type $selected
	 * @param unknown_type $idtag
	 * @param unknown_type $translate
	 * @return string
	 */
	public static function btradiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false)
	{
	    reset($data);
	    $html = '';

	    if (is_array($attribs))
	    {
	        $attribs = JArrayHelper::toString($attribs);
	    }

	    $id_text = $idtag ? $idtag : $name;

	    foreach ($data as $obj)
	    {
	        $k = $obj->$optKey;
	        $t = $translate ? JText::_($obj->$optText) : $obj->$optText;
	        $id = (isset($obj->id) ? $obj->id : null);

	        $extra = '';
	        $extra .= $id ? ' id="' . $obj->id . '"' : '';
	        if (is_array($selected))
	        {
	            foreach ($selected as $val)
	            {
	                $k2 = is_object($val) ? $val->$optKey : $val;
	                if ($k == $k2)
	                {
	                    $extra .= ' selected="selected"';
	                    break;
	                }
	            }
	        }
	        else
	        {
	            $extra .= ((string) $k == (string) $selected ? ' checked="checked"' : '');
	        }
	        
	        $active ='';
	        if(!empty($k)) {
	            $active = 'active';
	        }
	        
	        $html .= "\n\t" . '<input type="radio" name="' . $name . '"' . ' id="' . $id_text . $k . '" value="' . $k . '"' . ' ' . $extra . ' '
	        . $attribs . '/>' . "\n\t" . '<label for="' . $id_text . $k . '"' . ' id="' . $id_text . $k . '-lbl" class="btn">' . $t
	        . '</label>';
	    }
	    
	    $html .= "\n";
	    
	    return $html;
	}

	/**
	* Generates Type list
	*
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	public static function wepayType( $selected, $name = 'filter_type', $attribs = array('class' => 'inputbox'), $idtag = null, $allowAny = false, $title = 'COM_WEPAY_TYPE' )
	{
	    $list = array();
		if($allowAny) {
			$list[] =  self::option('', "- ".JText::_( $title )." -" );
		}
	
		$list[] = JHTML::_('select.option',  'GOODS', JText::_('COM_WEPAY_GOODS') );
		$list[] = JHTML::_('select.option',  'SERVICE', JText::_('COM_WEPAY_SERVICE') );
		$list[] = JHTML::_('select.option',  'PERSONAL', JText::_('COM_WEPAY_PERSONAL') );
		$list[] = JHTML::_('select.option',  'EVENT', JText::_('COM_WEPAY_EVENT') );
		$list[] = JHTML::_('select.option',  'DONATION', JText::_('COM_WEPAY_DONATION') );
		

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
	}

	/**
	* Generates Type list
	*
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	public static function feeOptions( $selected, $name = 'filter_feeoptions', $attribs = array('class' => ''), $idtag = null, $allowAny = false, $title = 'COM_WEPAY_TYPE' )
	{
	    $list = array();
		
		
		$list[] = JHTML::_('select.option',  '0', JText::_('COM_WEPAY_APPFEE_BUILTIN') );
		$list[] = JHTML::_('select.option',  '1', JText::_('COM_WEPAY_APPFEE_USERPAYS') );
		

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
	}
	
	
	
} 
?>