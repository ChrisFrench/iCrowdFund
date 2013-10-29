<?php
/**
 * @version	1.5
 * @package	Featureditems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class FeatureditemsHelperDiagnostics extends DSCHelperDiagnostics 
{
    /**
     * Performs basic checks on your installation to ensure it is OK
     * @return unknown_type
     */
    function checkInstallation() 
    {
        $functions = array();
        $functions[] = 'checkItemsCategory';
        $functions[] = 'checkItemsEnabled';
        $functions[] = 'checkItemsCategoryID';
        
        foreach ($functions as $function)
        {
            if (!$this->{$function}())
            {
                return $this->redirect( JText::_("COM_FEATUREDITEMS_".$function."_FAILED") .' :: '. $this->getError(), 'error' );
            }            
        }
    }

    /**
     * 
     * @param unknown_type $fieldname
     * @param unknown_type $value
     */
    protected function setCompleted( $fieldname, $value='1' )
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/tables' );
        $config = JTable::getInstance( 'Config', 'FeatureditemsTable' );
        $config->load( array( 'config_name'=>$fieldname ) );
        $config->config_name = $fieldname;
        $config->value = '1';
        $config->save();
    }
    
    /**
     * 
     * @return boolean
     */
    private function checkItemsCategory()
    {
        if (Featureditems::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__featureditems_items';
        $definitions = array();
        $fields = array();
    
        $fields[] = "item_category";
        $definitions["item_category"] = "varchar(255) NOT NULL";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function checkItemsEnabled()
    {
        if (Featureditems::getInstance()->get( __FUNCTION__, '0' ))
        {
            return true;
        }
    
        $table = '#__featureditems_items';
        $definitions = array();
        $fields = array();
    
        $fields[] = "item_enabled";
        $definitions["item_enabled"] = "tinyint(1) NOT NULL";
    
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            $this->setCompleted( __FUNCTION__ );
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return boolean
     */
    private function checkItemsCategoryID()
    {
    	if (Featureditems::getInstance()->get( __FUNCTION__, '0' ))
    	{
    		return true;
    	}
    
    	$table = '#__featureditems_items';
    	$definitions = array();
    	$fields = array();
    
    	$fields[] = "category_id";
    	$definitions["category_id"] = "int(11) NOT NULL";
    
    	if ($this->insertTableFields( $table, $fields, $definitions ))
    	{
    		$this->setCompleted( __FUNCTION__ );
    		return true;
    	}
    	return false;
    }
}