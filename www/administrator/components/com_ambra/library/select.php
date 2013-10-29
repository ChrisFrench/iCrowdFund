<?php
/**
* @version		0.1.0
* @package		Ambra
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

if ( !class_exists('AmbraSelect') ) {
class AmbraSelect extends DSCSelect
{
	
    public static function usejQuery( $selected, $name = 'filter_usejquery', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        
        $list[] = JHTML::_('select.option',  '0', JText::_( "Mootools" ) );
        $list[] = JHTML::_('select.option',  '1', JText::_( "jQuery" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }

    public static function gratardefaults( $selected, $name = 'gravatar_default', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        $list[] = JHTML::_('select.option',  'default', JText::_( "Default" ) );
        $list[] = JHTML::_('select.option',  'identicon', JText::_( "identicon" ) );
        $list[] = JHTML::_('select.option',  'wavatar', JText::_( "wavatar" ) );
        $list[] = JHTML::_('select.option',  'monsterid', JText::_( "monsterid" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    /**
    * Generates a created/modified select list
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function datetype( $selected, $name = 'filter_datetype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }
        $list[] = JHTML::_('select.option',  'registered', JText::_( "Registered" ) );
        $list[] = JHTML::_('select.option',  'visited', JText::_( "Visited" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
    * Generates a created/modified select list
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function pointhistory_datetype( $selected, $name = 'filter_datetype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }
        $list[] = JHTML::_('select.option',  'created', JText::_( "Created" ) );
        $list[] = JHTML::_('select.option',  'expires', JText::_( "Expires" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function category($selected, $name = 'filter_categoryid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Category', $title_none = 'No Category', $enabled = null )
    {
        // Build list
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'category_id', 'category_name' );
        }
        if($allowNone) {
            $list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'category_id', 'category_name' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'Categories', 'AmbraModel' );
        $model->setState( 'order', 'category_name' );
        $model->setState( 'direction', 'ASC' );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->category_id, JText::_($item->category_name), 'category_id', 'category_name' );
        }

        return self::genericlist($list, $name, $attribs, 'category_id', 'category_name', $selected, $idtag );
    }
 	
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function role($selected, $name = 'filter_parentid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Role', $title_none = 'No Parent', $enabled = null )
    {
        // Build list
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'role_id', 'role_name' );
        }
        if ($allowNone) {
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $root = JTable::getInstance('Roles', 'AmbraTable')->getRoot();
            $list[] =  self::option( $root->role_id, "- ".JText::_( $title_none )." -", 'role_id', 'role_name' );
        }

        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'Roles', 'AmbraModel' );
        $model->setState('order', 'tbl.lft');
        if (intval($enabled) == '1')
        {
            // get only the enabled items in the tree
            // this would be used for the front-end
            $items = $model->getTable()->getTree();
        }
            else
        {
            $items = $model->getList();
        }

        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->role_id, str_repeat( '.&nbsp;', $item->level-1 ).JText::_($item->name), 'role_id', 'role_name' );
        }
        return self::genericlist($list, $name, $attribs, 'role_id', 'role_name', $selected, $idtag );
    }

	/**
	 *
	 * @param $selected
	 * @param $name
	 * @param $attribs
	 * @param $idtag
	 * @param $allowAny
	 * @return unknown_type
	 */
	public static function profile($selected, $name = 'filter_profileid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Profile', $title_none = 'No Profile', $enabled = null )
 	{
 		// Build list
        $list = array();
		if ($allowAny) {
			$list[] =  self::option('', "- ".JText::_( $title )." -", 'profile_id', 'profile_name' );
		}
 	 	if($allowNone) {
 	 		$list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'profile_id', 'profile_name' );
		}

		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
		$model = JModel::getInstance( 'Profiles', 'AmbraModel' );
		$model->setState( 'order', 'profile_name' );
		$model->setState( 'direction', 'ASC' );
		$items = $model->getList();
        foreach (@$items as $item)
        {
        	$list[] =  self::option( $item->profile_id, JText::_($item->profile_name), 'profile_id', 'profile_name' );
        }

		return self::genericlist($list, $name, $attribs, 'profile_id', 'profile_name', $selected, $idtag );
 	}
 	
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function pointrule($selected, $name = 'filter_pointrule', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Rule', $title_none = 'No Rule', $enabled = null )
    {
        // Build list
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'pointrule_id', 'pointrule_name' );
        }
        if($allowNone) {
            $list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'pointrule_id', 'pointrule_name' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'PointRules', 'AmbraModel' );
        $model->setState( 'order', 'pointrule_name' );
        $model->setState( 'direction', 'ASC' );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->pointrule_id, JText::_($item->pointrule_name), 'pointrule_id', 'pointrule_name' );
        }

        return self::genericlist($list, $name, $attribs, 'pointrule_id', 'pointrule_name', $selected, $idtag );
    }
 	
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function field($selected, $name = 'filter_field', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Field', $title_none = 'No Field', $enabled = null )
    {
        // Build list
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'field_id', 'field_name' );
        }
        if($allowNone) {
            $list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'field_id', 'field_name' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'Fields', 'AmbraModel' );
        $model->setState( 'order', 'field_name' );
        $model->setState( 'direction', 'ASC' );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->field_id, JText::_($item->field_name), 'field_id', 'field_name' );
        }

        return self::genericlist($list, $name, $attribs, 'field_id', 'field_name', $selected, $idtag );
    }
 	
    /**
     *
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @return unknown_type
     */
    public static function act($selected, $name = 'filter_actid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Act', $title_none = 'No Act', $enabled = null )
    {
        // Build list
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'act_id', 'act_name' );
        }
        if($allowNone) {
            $list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'act_id', 'act_name' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'Acts', 'AmbraModel' );
        $model->setState( 'order', 'act_name' );
        $model->setState( 'direction', 'ASC' );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->act_id, JText::_($item->act_name), 'act_id', 'act_name' );
        }

        return self::genericlist($list, $name, $attribs, 'act_id', 'act_name', $selected, $idtag );
    }
    
    /**
     * 
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @param $allowNone
     * @param $title
     * @param $title_none
     * @return unknown_type
     */
    public static function fieldtype($selected, $name = 'filter_fieldtype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Type', $title_none = 'No Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'id', 'title' );
        }

        JLoader::import( 'com_ambra.library.field', JPATH_ADMINISTRATOR.DS.'components' );
        $items = AmbraField::getTypes();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->id, JText::_($item->title), 'id', 'title' );
        }
        return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag );
    }
    
    /**
    * Generates points type select list
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function pointtype( $selected, $name = 'filter_pointtype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Point Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }
        $list[] = JHTML::_('select.option',  'current', JText::_( "Current" ) );
        $list[] = JHTML::_('select.option',  'total', JText::_( "Total" ) );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     * 
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     * @return unknown_type
     */
    public static function scope($selected, $name = 'filter_scope', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Scope' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'id', 'title' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'PointRules', 'AmbraModel' );
        $model->setState( 'select', 'DISTINCT(pointrule_scope) AS pointrule_id' );
        $model->setState( 'order', 'pointrule_scope' );
        $model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->group( 'pointrule_scope' );
        $model->setQuery( $query );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->pointrule_id, $item->pointrule_id, 'id', 'title' );
        }
        return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag );
    }
    
    public static function event($selected, $name = 'filter_event', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Event' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'id', 'title' );
        }

        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance( 'PointRules', 'AmbraModel' );
        $model->setState( 'select', 'DISTINCT(pointrule_event) AS pointrule_id' );
        $model->setState( 'order', 'pointrule_event' );
        $model->setState( 'direction', 'ASC' );
        $query = $model->getQuery();
        $query->group( 'pointrule_event' );
        $model->setQuery( $query );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->pointrule_id, $item->pointrule_id, 'id', 'title' );
        }
        return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag );
    }
    
    /**
    * 
    *
    * @param string The value of the HTML name attribute
    * @param string Additional HTML attributes for the <select> tag
    * @param mixed The key that is selected
    * @returns string HTML for the radio list
    */
    public static function prvkey( $selected, $name = 'filter_prvkey', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Key Type' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }
        
        $list[] = JHTML::_('select.option',  'ambra_profile', JText::_( "Ambra Profile" ) );
        $list[] = JHTML::_('select.option',  'joomla_acl', JText::_( "Joomla ACL" ) );
        // $list[] = JHTML::_('select.option',  'ambra_role', JText::_( "Ambra Role" ) );
        // TODO if juga is installed, add juga_group
        // TODO if AS is installed, add AS as_subtype
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
     * 
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     */
	public static function listmonths( $selected, $name = 'select_int1', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Range' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }
		
        for($count=0;$count<=12;$count++ )
		{
        $list[] = JHTML::_('select.option',  $count, JText::_( $count ) );
		}

        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
	/**
     * 
     * Enter description here ...
     * @param unknown_type $selected
     * @param unknown_type $name
     * @param unknown_type $attribs
     * @param unknown_type $idtag
     * @param unknown_type $allowAny
     * @param unknown_type $title
     * @return unknown_type
     */
    public static function relationship( $selected, $name = 'filter_relationtype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Relationship' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

        $list[] = JHTML::_('select.option',  'relates', JText::_( "Relationship Relates" ) );
        $list[] = JHTML::_('select.option',  'member', JText::_( "Relationship Member" ) );
                
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
}
}
