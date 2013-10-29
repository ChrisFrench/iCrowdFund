<?php
/**
 * @package    Ambra
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class modAmbraUserStatisticsHelper extends JObject
{
	var $_stats = null;
    var $_params = null;

    /**
     * Constructor to set the object's params
     * 
     * @param $params
     * @return unknown_type
     */
    function __construct( $params )
    {
        parent::__construct();
        $this->_params = $params;
    }
    
    function isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php';
        if (JFile::exists($filePath))
        {
            JLoader::register('Ambra', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php');
            Ambra::load( 'AmbraConfig', 'defines' );
            Ambra::load( 'AmbraQuery', 'library.query' );
            Ambra::load( 'AmbraHelperBase', 'helpers._base' );
            $success = true;
        }           
        return $success;
    }
    
	/**
	 * Gets the users statistics object, 
	 * creating it if it doesn't exist
	 * 
	 * @return unknown_type
	 */
	function getStatistics()
	{
		if (empty($this->_stats))
		{
			$this->_stats = $this->_statistics();
		}
		return $this->_stats;
	}
	
    /**
     * _statistics function.
     * 
     * @access private
     * @return void
     */
    function _statistics()
    {
        $stats = new JObject();
        $stats->link = "index.php?option=com_ambra&view=users&task=list&filter_order=tbl.registerDate&filter_direction=DESC";

        $stats->lifetime = $this->_lifetime();
        $stats->today = $this->_today();
        $stats->yesterday = $this->_yesterday();
        $stats->lastseven = $this->_lastSeven();
        $stats->lastmonth = $this->_lastMonth();
        $stats->thismonth = $this->_thisMonth();
        $stats->lastyear = $this->_lastYear();
        $stats->thisyear = $this->_thisYear();

        return $stats;
    }

    /**
     * _today function.
     * 
     * @access private
     * @return void
     */
    function _today()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $startdate = $today;

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        $query->where("tbl.registerDate >= '$startdate'");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();

        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _yesterday()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate >= DATE_SUB('".$today."', INTERVAL 1 DAY)");
        $query->where("tbl.registerDate < '$today'");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _lastSeven()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $enddate = $today;
        $query = " SELECT DATE_SUB('".$today."', INTERVAL 1 DAY) ";
        $database->setQuery( $query );
        $startdate = $database->loadResult();

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate >= DATE_SUB('".$today."', INTERVAL 6 DAY)");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _thisMonth()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $start = getdate( strtotime($today) );
        // first day of month
        $startdate = date("Y-m-d", strtotime($start['year']."-".$start['mon']."-01"));

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate >= '$startdate'");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _lastMonth()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $start = getdate( strtotime($today) );
        // first day of month
        $enddate = date("Y-m-d", strtotime($start['year']."-".$start['mon']."-01"));

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate < '$enddate'");
        $query->where("tbl.registerDate >= DATE_SUB('".$enddate."', INTERVAL 1 MONTH)");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _thisYear()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $start = getdate( strtotime($today) );
        // first day of year
        $startdate = date("Y-m-d", strtotime($start['year']."-01-01"));

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate >= '$startdate'");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     *
     * @return unknown_type
     */
    function _lastYear()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $start = getdate( strtotime($today) );
        // first day of year
        $enddate = date("Y-m-d", strtotime($start['year']."-01-01"));

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->from('#__users AS tbl');
        
        $query->where("tbl.registerDate < '$enddate'");
        $query->where("tbl.registerDate >= DATE_SUB('".$enddate."', INTERVAL 1 YEAR)");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     * _lifetime function.
     * 
     * @access private
     * @return void
     */
    function _lifetime()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $firstuser = $this->_firstuser();
        $firstuser_date = empty($firstuser->date) ? '0000-00-00' : $firstuser->date;
        $lastuser = $this->_lastuser();
        $lastuser_date = empty($lastuser->date) ? '0000-00-00' : $lastuser->date; 

        $query = new AmbraQuery();
        $query->select( 'COUNT(*) AS num' );
        $query->select( "DATEDIFF('{$lastuser_date}','{$firstuser_date}') AS days" );
        $query->from('#__users AS tbl');
        

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        $days = ($return->days > 0) ? $return->days : 1;
        $return->average_daily = $return->num / $days;
        return $return;
    }

    /**
     * _firstuser function.
     * 
     * @access private
     * @return void
     */
    function _firstuser()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $startdate = $today;

        $query = new AmbraQuery();
        $query->select( 'tbl.registerDate AS date' );
        $query->from('#__users AS tbl');
        
        $query->order("tbl.registerDate ASC");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

    /**
     * _lastuser function.
     * 
     * @access private
     * @return void
     */
    function _lastuser()
    {
        $database = JFactory::getDBO();
        $today = AmbraHelperBase::getToday();

        $startdate = $today;

        $query = new AmbraQuery();
        $query->select( 'tbl.registerDate AS date' );
        $query->from('#__users AS tbl');
        
        $query->order("tbl.registerDate DESC");

        $database->setQuery( (string) $query );
        $return = $database->loadObject();
        return $return;
    }

}