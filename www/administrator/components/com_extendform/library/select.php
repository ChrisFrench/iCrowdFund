<?php 

defined('_JEXEC') or die('Restricted access');


class ExtendformSelect extends DSCSelect
{
	/**
     * 
     * Enter description here ...
     * @param $selected
     * @param $name
     * @param $attribs
     * @param $idtag
     * @param $allowAny
     * @param $title
     * @return unknown_type
     */
    public static function side( $selected, $name = 'filter_side', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Side', $allowNone = false, $title_none = 'No Type' )
    {
        $list = array();
        if ($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
       }

		
		$list[] = JHTML::_('select.option', 'admin', 'Admin' );
		$list[] = JHTML::_('select.option', 'front', 'Front End' );
		$list[] = JHTML::_('select.option', 'both', 'Both' );
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
	
	
	
	
}