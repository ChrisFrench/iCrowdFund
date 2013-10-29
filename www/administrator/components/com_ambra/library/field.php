<?php
/**
* @version      0.1.0
* @package      Ambra
* @copyright    Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.dioscouri.com
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class AmbraField 
{
    /**
     * Returns a list of types
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function getTypes()
    {
        $types = array();
        $return = array();
        
        $types['1'] = "TEXT";
        $types['2'] = "TEXTAREA";
        $types['3'] = "SELECTLIST";
        $types['4'] = "RADIOLIST";
        $types['5'] = "CHECKBOX";
        $types['6'] = "DATE";
        // $types['7'] = "FILE";
        $types['8'] = "SEPARATOR";
        $types['9'] ="MULTIPLESELECT";
        
        foreach ($types as $id=>$title) 
        {
            $type = new stdClass(); 
            $type->id = $id;
            $type->title = $title;
            $return[] = $type;
        }
        
        return $return;     
    }
    
    /**
     * Returns a cleaned title
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function cleanTitle( $fieldtitle, $length='25' ) 
    {
        // trim whitespace
        $trim_title = strtolower( trim( str_replace( " ", "", $fieldtitle ) ) );
        
        // strip all html tags
        $wc = strip_tags($trim_title);
        
        // remove 'words' that don't consist of alphanumerical characters or punctuation
        $pattern = "#[^(\w|\d|\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]+#";
        $wc = trim(preg_replace($pattern, "", $wc));
        
        // remove one-letter 'words' that consist only of punctuation
        $wc = trim(preg_replace("#\s*[(\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]\s*#", "", $wc));
        
        // remove superfluous whitespace
        $wc = preg_replace("/\s\s+/", "", $wc);     
        
        // cut title to length
        $cut_title = substr($wc, 0, $length);
        
        $data = $cut_title;
        
        return $data;
    }
    
    /**
     * Wrapper for legacy compat
     * 
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
 public static function getFieldTypes() 
    {
        return AmbraField::getTypes();
    }
    
    /**
     * 
     * @param $default
     * @param $exception
     * @return unknown_type
     */
 public static   function getSelectListFieldTypes( $field, $default='', $options='' ) 
    {
        static $list; 
        
        if (empty($list)) 
        {
            $types = array();
            $items = AmbraField::getFieldTypes();
            $types[] = JHTML::_('select.option', '', '- '.JText::_( 'Select from List' ).' -' );
            if ($items) { foreach ($items as $item) {
                $types[] = JHTML::_('select.option', $item->id, JText::_( $item->title ) );
            } }
            $list = JHTML::_('select.genericlist', $types, $field, $options, "value", "text", $default );
        
        }

        return $list;
    }

    /**
     * Returns HTML
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function draw( $field, $control_name='params', $default=null ) 
    {
        $return = AmbraField::display( $field, $control_name, $default );
        return $return;
    }

    /**
     * Returns HTML
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function display( $field, $control_name='', $default=null ) 
    {
        $success = false;
        $return = "";
        $database = JFactory::getDBO();
        
        if (!is_object($field)) 
        {
            if (is_numeric($field)) 
            {
                JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
                unset($table);
                $table = JTable::getInstance( 'Fields', 'AmbraTable' );
                $table->load( $field );
                if (empty($table->field_id)) { return $success; }
                $field = $table;
            } else {
                return $success;
            }
        }
        
        if (!isset($field->type_id)) { return $success; }
                        
        switch ($field->type_id) 
        {
            case "1": // TEXT
                $maxlength = $field->maxlength ? 'maxlength="'.$field->maxlength.'"' : "";
                $size = $field->size ? 'size="'.$field->size.'"' : "";
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $value = $default ? $default : $field->default;
                $readonly  = $field->readonly ? 'readonly="readonly"': "";
                $name = empty($control_name) ? $field->db_fieldname : $control_name.'['.$field->db_fieldname.']';
                $return = '<input type="text" name="'.$name.'" id="'.$control_name.$field->db_fieldname.'" value="'.$value.'" '.$class.' '.$size.''.$readonly.' />';
              break;
            case "2": // TEXTAREA
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $value = $default ? $default : $field->default;
                $value = str_replace('<br />', "\n", $value);
                $name = empty($control_name) ? $field->db_fieldname : $control_name.'['.$field->db_fieldname.']';
                $readonly  = $field->readonly ? 'readonly="readonly"': "";
                $return = '<textarea name="'.$name.'" cols="'.$field->cols.'" rows="'.$field->rows.'" '.$class.''.$readonly.' id="'.$control_name.$field->db_fieldname.'" >'.$value.'</textarea>';
              break;
              
            case "3": // SELECTLIST
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $disabled = $field->readonly ? 'disabled="disabled"':'';
                $value = $default ? $default : $field->default;
                $paramsObject = new DSCParameter( $field->options );
                $params = $paramsObject->toArray();
                $options = array();
                if (is_array($params)) { foreach ($params as $val=>$text) {
                    $options[] = JHTML::_('select.option', $val, JText::_( stripslashes($text) ));
                } }
                
                if ($disabled == ''){
                    // Normal mode
                    $return = JHTML::_('select.genericlist', $options, $control_name.'['.$field->db_fieldname.']', "$class $disabled", 'value', 'text', $value, $control_name.$field->db_fieldname);
                }
                    else
                {
                    // Readonly mode. Create a disabled dropdown and a hidden one to really post via the form
                    $return = JHTML::_('select.genericlist', $options, 'inactive_'.$control_name.'['.$field->db_fieldname.']', "$class $disabled", 'value', 'text', $value, 'incative_'.$control_name.$field->db_fieldname);
                    $return .= JHTML::_('select.genericlist', $options, $control_name.'['.$field->db_fieldname.']', "$class style=\"display: none;\"", 'value', 'text', $value, $control_name.$field->db_fieldname);
                }
                
              break;
            case "4": // RADIOLIST
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $disabled = $field->readonly ? 'disabled="disabled"':'';
                $value = $default ? $default : $field->default;
                $paramsObject = new DSCParameter( $field->options );
                $params = $paramsObject->toArray();
                $options = array();
                if (is_array($params)) { foreach ($params as $val=>$text) {
                    $options[] = JHTML::_('select.option', $val, JText::_($text));
                } }
                
                if($disabled == ''){
                    // Normal mode
                    $return = JHTML::_('select.radiolist', $options, $control_name.'['.$field->db_fieldname.']', "$class $disabled", 'value', 'text', $value, $control_name.$field->db_fieldname);
                }
                    else
                {
                    // Readonly mode. Create a disabled radio-list and a hidden one to really post via the form
                    $return = JHTML::_('select.radiolist', $options, 'inactive_'.$control_name.'['.$field->db_fieldname.']', "$class $disabled", 'value', 'text', $value, 'incative_'.$control_name.$field->db_fieldname);
                    $return .= '<span style="display: none;">'.JHTML::_('select.radiolist', $options, $control_name.'['.$field->db_fieldname.']', $class, 'value', 'text', $value, $control_name.$field->db_fieldname).'</span>';
                }
              break;
            case "5": // CHECKBOX
                $size = $field->size ? 'size="'.$field->class.'"' : "";
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $disabled = $field->readonly ? 'disabled="disabled"':'';
                $value = $default ? $default : $field->default;
                $value = strtolower($value);
                $checked = '';
                if ($value == "checked" || $value == '1' || $value=="true" )
                {
                    $checked = " checked ";
                }                
                if ($disabled == '') {
                    // Normal mode
                    $return = '<input '.$disabled.' type="checkbox" name="'.$control_name.'['.$field->db_fieldname.']" id="'.$control_name.$field->db_fieldname.'" value="'.$value.'" '.$class.' '.$size.' '.$checked.' />';
                }
                    else
                {
                    // Readonly mode. Create the disabled checkbox and a hidden one
                    $return = '<input '.$disabled.' type="checkbox" name="inactive_'.$control_name.'['.$field->db_fieldname.']" id="inactive_'.$control_name.$field->db_fieldname.'" value="'.$value.'" '.$class.' '.$size.' '.$checked.' />';
                    $return .= '<input style="display: none;" type="checkbox" name="'.$control_name.'['.$field->db_fieldname.']" id="'.$control_name.$field->db_fieldname.'" value="'.$value.'" '.$class.' '.$size.' '.$checked.' />';
                }
                
              break;
            case "6": // DATE
                $value = $default ? $default : $field->default;
                $class = $field->class ? $field->class : 'inputbox';
                $name = empty($control_name) ? $field->db_fieldname : $control_name.'['.$field->db_fieldname.']';
                
                $disabled = $field->readonly ? 'readonly="readonly"': "";
                if($disabled){
                $attribute['disabled']=$disabled;   
                $return = '<input type="text" name="'.$name.'" id="'.$control_name.$field->db_fieldname.'" value="'.$value.'" '.$class.' '.$size.' '.$disabled.' />';
                }   
                else{                
                $return = JHTML::_('calendar', $value, $name, $control_name.$field->db_fieldname, '%Y-%m-%d', $attribute);
                }
              break;
            case "7": // FILE
                $value = $default ? $default : $field->default;
                $return = "";
              break;
            case "8": // SEPARATOR
                $value = $default ? $default : $field->default;
                $class = $field->class ? 'class="'.$field->class.'"' : 'class=""';
                if ($value) {
                    $return = '<div '.$class.'>'.$value.'</div>';
                } else {
                    $return = '<hr '.$class.'/>';
                }
              break;
            case "9": //MULTIPLESELECT
                $class = $field->class ? 'class="'.$field->class.'"' : 'class="text_area"';
                $attribute['class']=$class;
                $attribute['MULTIPLE']="TRUE";
                $attribute['SIZE']="5";
                $value = $default ? $default : $field->default;   
                $disabled = $field->readonly ? 'disabled="disabled"': "";
                if ($disabled) {
                    $attribute['disabled']=$disabled;   
                }               
                
                $paramsObject = new DSCParameter( $field->options );
                $params = $paramsObject->toArray();
                $options = array();
                if (is_array($params)) { foreach ($params as $val=>$text) {
                    $options[] = JHTML::_('select.option', $val, JText::_($text));
                } }
                
                                
                $name=empty($control_name) ? $field->db_fieldname : $control_name.'['.$field->db_fieldname.'][]';
                                    
                $return = JHTML::_('select.genericlist', $options, $name, $attribute, 'value', 'text', $value, $control_name.$field->db_fieldname);
              break;
            default:
                return $success;
              break;          
        }
        
        $success = true;
        return $return;     
    }

    /**
     * Returns Value
     * @param object 
     * @param mixed value
     * @return array
     */
 public static   function displayValue( $field, $value ) 
    {
        $success = false;
        $return = "";

        if (!is_object($field)) 
        {
            if (is_numeric($field)) 
            {
                JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
                unset($table);
                $table = JTable::getInstance( 'Fields', 'AmbraTable' );
                $table->load( $field );
                if (empty($table->field_id)) { return $success; }
                $field = $table;
            }
                else 
            {
                // attempt to grab from the db_fieldname
                $field = strtolower( strval( $field ) );
                JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
                unset($table);
                $table = JTable::getInstance( 'Fields', 'AmbraTable' );
                $table->load( array( 'db_fieldname'=>$field ) );
                if (empty($table->field_id)) { return $success; }
                $field = $table;
            }
        }
        
        if (!isset($field->type_id)) { return $success; }
                
        // if the field has options, find the title for the appropriate value
        // otherwise, if the value exists, return it
        // otherwise, return null
        switch ($field->type_id) 
        {
            case "7": // FILE
                return $return;
              break;
            case "3": // SELECTLIST
            case "4": // RADIOLIST
                $paramsObject = new DSCParameter( $field->options );
                $params = $paramsObject->toArray();
                if (is_array($params)) { foreach ($params as $val=>$text) {
                    if ($val == $value) { $return = JText::_($text); }
                } }
              break;
            case "9": //MULTIPLESELECT              
                $paramsObject = new DSCParameter( $field->options );
                $params = $paramsObject->toArray();
                if (is_array($params)) { foreach ($params as $val=>$text) {
                    if ($val == $value) { $return = JText::_($text); }
                } }
              break;
            case "1": // TEXT
            case "2": // TEXTAREA
            case "5": // CHECKBOX
            case "6": // DATE
            case "8": // SEPARATOR
            default:
                $return = $value ? $value : "";
              break;
        }       
        
        return $return;
    }
    
    /**
     * Returns a list of types
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function getFieldTypeTitle( $id ) 
    {
        $return = "";
        
        $types = AmbraField::getTypes();
        foreach ($types as $t) 
        {
            if ( $t->id == $id ) { $return = JText::_( $t->title ); }
        }

        return $return;
    }
    
    /**
     * Returns an array of objects
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
 public static   function getCategories( $id ) 
    {
        $database = JFactory::getDBO();

        $cat_query = " AND t.fieldid = '".$id."' ";
        
        $query = "
            SELECT 
                c.*, t.* 
            FROM 
                #__ambra_f2c AS t
            LEFT JOIN
                #__ambra_categories AS c ON t.categoryid = c.id
            WHERE 1
                {$cat_query}
        ";

        $database->setQuery( $query );
        $data = $database->loadObjectList();
        return $data;
    }

    /**
     * Will be deprecated -- here for backwards compat
     * @param $id
     * @return unknown_type
     */
  public static  function getFieldCategories( $id )
    {
        $data = AmbraField::getCategories( $id );
        return $data;
    }
    
    /**
     * Returns yes/no
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function isFieldCategory( $fieldid, $catid ) 
    {
        $success = false;
        $data = &AmbraField::getFieldCategories( $fieldid );

        for ($i=0; $i<count($data); $i++) {
            if ($data[$i]->id == $catid) { $success = true; }
        }

        return $success;    
    }

    /**
     * Returns yes/no
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
  public static  function addToCategory( $fieldid, $catid ) 
    {
        $success = false;
        $database = JFactory::getDBO();
        
        $query = " INSERT INTO #__ambra_f2c "
                ." SET `categoryid` = '{$catid}', "
                ." `fieldid` = '{$fieldid}' "
                ;
        $database->setQuery( $query );
        if ($database->query()) { $success = true; }
        
        return $success;        
    }

    /**
     * Returns yes/no
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
 public static   function removeFromCategory( $fieldid, $catid ) 
    {
        $success = false;
        $database = JFactory::getDBO();
        
        $query = " DELETE FROM #__ambra_f2c "
                ." WHERE `categoryid` = '$catid' "
                ." AND `fieldid` = '$fieldid' "
                ;
        $database->setQuery( $query );
        if ($database->query()) { $success = true; }
        
        return $success;        
    }

    /**
     * Returns a field object
     * from a db_fieldname
     * 
     * @return 
     * @param $fieldname Object
     */
 public static   function getFromFieldname( $db_fieldname )
    {
        $success = false;
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
        unset($table);
        $table = JTable::getInstance( 'Fields', 'Table' );
        $table->load( strtolower($db_fieldname), 'db_fieldname' );
        if ( !empty($table->id) ) { $success = $table; }
        
        return $success;
    }
    
    /**
     * 
     * @return 
     * @param $fieldid Object
     * @param $catid Object
     */ 
  public static  function isRequired( $fieldid, $categoryid, $returnObject='' )
    {
        $success = false;
        $database = JFactory::getDBO();
        
        $query = "
            SELECT 
                *
            FROM 
                #__ambra_f2c
            WHERE
                `fieldid` = '{$fieldid}'
            AND 
                `categoryid` = '{$categoryid}'
            LIMIT 1
        ";
        $database->setQuery( $query );
        $data = $database->loadObject();
        if (intval($data->required) == '1' ) { 
            $success = true; 
        }
        
        if ($returnObject == '1') {
            $success = $data;
        }
        
        return $success;        
    }

    /**
     * 
     * @return 
     * @param $fieldid Object
     * @param $catid Object
     */ 
 public static   function setRequired( $fieldid, $categoryid, $required='1' )
    {
        $success = false;
        $database = JFactory::getDBO();
        
        $query = "
            UPDATE 
                #__ambra_f2c
            SET 
                `required` = '{$required}'
            WHERE
                `fieldid` = '{$fieldid}'
            AND 
                `categoryid` = '{$categoryid}'
            LIMIT 1
        ";
        $database->setQuery( $query );
        if ( $database->query() ) { $success = true; }
        
        return $success;        
    }   
}

?>