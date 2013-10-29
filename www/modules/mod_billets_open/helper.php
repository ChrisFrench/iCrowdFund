<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/**
 * 
 * @return 
 */
class modBilletsOpenHelper 
{
    /**
     *
     * @return unknown_type
     */
    public static function _isInstalled()
    {
        $success = false;
        
        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'defines.php')) 
        {
            require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'defines.php' );
            $success = true;
        }
        return $success;
    }
    
    /**
     * Retrieves data
     *
     * @access public
     */    
    public static function getOpenTickets() 
    {
    	// TODO first check that is installed
    	
    	if (!modBilletsOpenHelper::_isInstalled()) { return 0; }
    	jimport( 'joomla.application.component.model' );
    	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
    	JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
    	$model = JModel::getInstance( 'Tickets', 'BilletsModel' );
		$model->setState( 'filter_userid', JFactory::getUser()->id );
		$model->setState( 'filter_stateid', Billets::getInstance()->get( 'state_new', '1' ) );
		$total = $model->getTotal();
    	return $total;
    }

    /**
     * Retrieves the Itemid
     *
     * @access public
     */
	public static function getItemid( $link = "index.php?option=com_billets&view=billets" ) 
	{
		$id = "";
		$database = JFactory::getDBO();
		$link = $database->getEscaped( strtolower( $link ) );
		
		$query = "
			SELECT 
				* 
			FROM 
				#__menu
			WHERE 1 
				AND LOWER(`link`) = '".$link."'
				AND `published` > '0'
			ORDER BY 
				`link` ASC
		";
	
		$database->setQuery($query);
		if ( $data = $database->loadObject() ) {
			$id = $data->id;		
		}

		return $id;
	}
}