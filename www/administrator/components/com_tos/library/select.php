<?php

defined('_JEXEC') or die('Restricted access');


class TosSelect extends DSCSelect
{


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
    public static function scope( $selected, $name = 'filter_scope', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title='Select Scope' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

      
        $model = Tos::getClass('TosModelScopes', 'models.scopes');
        $model->setState( 'order', 'scope_name' );
        $model->setState( 'direction', 'ASC' );
        $items = $model->getList();
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->scope_id, $item->scope_name );
        }
        
        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }

} ?>