<?php
/**
 * @version    1.5
 * @package    Ambra
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Ambra::load('AmbraViewBase','views._base');

class AmbraViewDashboard extends AmbraViewBase
{
    /**
     * 
     * 
     * @access public
     * @param mixed $tpl. (default: null)
     * @return void
     */
    function getLayoutVars($tpl=null)
    {
         
        if (empty($this->hidestats))
        {        	
	        $model = $this->getModel();
	        $state = $model->getState();
	        $state->stats_interval = JRequest::getVar('stats_interval', 'last_thirty');
	        
	        // set the model state
	        $this->assign( 'state', $state );
	            
	        $stats_interval = $state->stats_interval;
	        
            // Get a reference to the global cache object.
            $cache = JFactory::getCache();
            $cache->setCaching( 1 );
	        
            switch($stats_interval)
            {
                case 'today':
                    $this->_today();
                    break;
                case 'yesterday':
                    $this->_yesterday();
                    break;
                case 'last_seven':
                    //$this->_lastSeven();
                    $cache->call( array( $this, '_lastSeven' ) );
                    break;
                case 'ytd':
                    //$this->_thisYear();
                    $cache->call( array( $this, '_thisYear' ) );
                    break;
                case 'last_thirty':
                default:
                    //$this->_lastThirty();
                    $cache->call( array( $this, '_lastThirty' ) );
                    break;
            }
        }

        // form
            $validate = JUtility::getToken();
            $form = array();
            $controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
            $view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
            $action = $this->get( '_action', "index.php?option=com_ambra&controller={$controller}&view={$view}" );
            $form['action'] = $action;
            $form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
            $this->assign( 'form', $form );
    }

    /**
     *
     * @return unknown_type
     */
    function _lastThirty()
    {
        $database = JFactory::getDBO();
        $base = new AmbraHelperBase();
        $today = $base->getToday();
        $end_datetime = $today;
        $query = " SELECT DATE_SUB('".$today."', INTERVAL 1 MONTH) ";
        $database->setQuery( $query );
        $start_datetime = $database->loadResult();

        $runningtotal = 0;
        $runningsum = 0;
        $data = new stdClass();
        $cdata = new stdClass();
  
        $num = 0;
        $result = array();
        $curdate = $start_datetime;
        $enddate = $end_datetime;
        while ($curdate <= $enddate)
        {
            // set working variables
            $variables = AmbraHelperBase::setDateVariables( $curdate, $enddate, 'daily' );
            $thisdate = $variables->thisdate;
            $nextdate = $variables->nextdate;
            
            // set the query
            $query = new DSCQuery();
            $query->select( 'tbl.id' );
            $query->from( '#__users AS tbl' );
            $query->where("tbl.registerDate >= '".$thisdate."'");
            $query->where("tbl.registerDate <= '".$nextdate."'");
                        
            // grab all records
            $model = JModel::getInstance( 'Users', 'AmbraModel' );
            $model->setQuery( $query );
            
            //$model->setState( 'filter_date_from', $thisdate );
            //$model->setState( 'filter_date_to', $nextdate );
            
            //// Get a reference to the global cache object.
            //$cache = & JFactory::getCache();
            //$cache->setCaching( 1 );
            
            //jimport( 'joomla.error.profiler' );
            //// Run the test without caching.
            //$profiler = new JProfiler();
            $rows = $model->getList();
            //echo $profiler->mark( ' without caching' )."<br/>";
             
            // Run the test with caching.
            //$profiler = new JProfiler();
            //$rows  = $cache->call( array( $model, 'getList' ) );
            //echo $profiler->mark( ' with caching' )."<br/><br/>";
            

            //$rows = $model->getList();

            $total = count( $rows );
//            $model->setState('select', 'SUM(`order_total`)');
//            $ordersQuery = $model->getResultQuery();
//            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
//            $model->setResultQuery($ordersQuery);
//            $sum = $model->getResult();

            //store the value in an array
            $result[$num]['rows']       = $rows;
            $result[$num]['datedata']   = getdate( strtotime($thisdate) );
            $result[$num]['countdata']  = $total;
//            $value = AmbraHelperBase::number( $sum );
//            $result[$num]['sumdata']    = empty($value) ? $sum : $value;
            $runningtotal               = $runningtotal + $total;
//            $runningsum                 = $runningsum + $sum;

            // increase curdate to the next value
            $curdate = $nextdate;
            $num++;

        } // end of the while loop

        $data->rows         = $result;          // Array
        $data->total        = $runningtotal;    // Int
//        $data->sum          = $runningsum;      // Int

//        $this->getChartDaily( $data, 'Last Thirty Days', 'graph' );
//        $this->getChartDaily( $data, 'Last Thirty Days', 'graphSum', 'sumdata', 'Line' );
//        $this->assign( 'graphData', $data );

        $categories = array();
        if (is_array($data->rows)) { foreach ($data->rows as &$r) {
            $r['label'] = $r['datedata']['mon']."/".$r['datedata']['mday'];
            $r['value'] = $r['countdata'];
            if (!in_array($r['label'], $categories)) {
                $categories[] = $r['label'];
            }
        } } // end foreach
        
        $data->title = 'Registrations';
        $data->categories = $categories;
        $cdata->datasets = @array( $data );
          
        $this->getChartBarDaily( $cdata, 'This Month', 'thisMonthRegistrations' );
        
        
    }
    
    /**
     *
     * @return unknown_type
     */
    function _today()
    {
    	$base = new AmbraHelperBase();
    	
		$database = JFactory::getDBO();
		$database->setQuery("SELECT UTC_DATE()");
		$today = $database->loadResult();
        
        $runningtotal = 0;
        $runningsum = 0;
        $data = new stdClass();
        $num = 0;
        $result = array();
        
        for ($curhour = 0; $curhour < 24; $curhour++)
        {
            $thishour = $curhour < 10 ? '0'.$curhour : $curhour;
            
            $start_ts = $base->getOffsetDate( $today." $thishour:00:00" );
            $end_ts = $base->getOffsetDate( $today." $thishour:59:59" );
            
            // grab all records
            $model = JModel::getInstance( 'Orders', 'AmbraModel' );
            $model->setState( 'filter_date_from', $start_ts );
            $model->setState( 'filter_date_to', $end_ts );
            // set query for orderstate range
            $ordersQuery = $model->getQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setQuery($ordersQuery);
            $rows = $model->getList();
            
            $total = count( $rows );            
            $model->setState('select', 'SUM(`order_total`)');
            $ordersQuery = $model->getResultQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setResultQuery($ordersQuery);
            $sum = $model->getResult();

            //store the value in an array
            $result[$num]['rows']       = $rows;
            //$result[$num]['datedata']   = getdate( strtotime($thisdate) );
            $result[$num]['datedata']   = $thishour.':00';
            $result[$num]['countdata']  = $total;
            $value = AmbraHelperBase::number( $sum );
            $result[$num]['sumdata']    = empty($value) ? $sum : $value;
            $runningtotal               = $runningtotal + $total;
            $runningsum                 = $runningsum + $sum;

            $num++;

        } // end of the while loop

        $data->rows         = $result;          // Array
        $data->total        = $runningtotal;    // Int
        $data->sum          = $runningsum;      // Int

        $this->getChartHourly( $data, 'Today (Hourly), UTC', 'graph' );
        $this->getChartHourly( $data, 'Today (Hourly), UTC', 'graphSum', 'sumdata', 'Line' );

        $this->assign( 'graphData', $data );
        
        return;
    }
    
    /**
     *
     * @return unknown_type
     */
    function _yesterday()
    {   
    	$base = new AmbraHelperBase();
    	   
        $database = JFactory::getDBO();
        $database->setQuery("SELECT UTC_DATE()");
        $today = $database->loadResult();
        
        $database =& JFactory::getDBO();
        $database->setQuery("SELECT SUBDATE('".$today."', INTERVAL 1 DAY)");
        $yesterday = $database->loadResult();

        $runningtotal = 0;
        $runningsum = 0;
        $data = new stdClass();
        $num = 0;
        $result = array();
        
        for($curhour = 0; $curhour < 24; $curhour++)
        {
            $thishour = $curhour<10?'0'.$curhour:$curhour;
            
            $start_ts = $base->getOffsetDate( $yesterday." $thishour:00:00" );
            $end_ts = $base->getOffsetDate( $yesterday." $thishour:59:59" );
            
            // grab all records
            $model = JModel::getInstance( 'Orders', 'AmbraModel' );
            $model->setState( 'filter_date_from', $start_ts );
            $model->setState( 'filter_date_to', $end_ts );
            // set query for orderstate range
            $ordersQuery = $model->getQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setQuery($ordersQuery);
            $rows = $model->getList();
            
            $total = count( $rows );
            $model->setState('select', 'SUM(`order_total`)');
            $ordersQuery = $model->getResultQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setResultQuery($ordersQuery);
            $sum = $model->getResult();

            //store the value in an array
            $result[$num]['rows']       = $rows;
            //$result[$num]['datedata']   = getdate( strtotime($thisdate) );
            $result[$num]['datedata']   = $thishour.':00';
            $result[$num]['countdata']  = $total;
            $value = AmbraHelperBase::number( $sum );
            $result[$num]['sumdata']    = empty($value) ? $sum : $value;
            $runningtotal               = $runningtotal + $total;
            $runningsum                 = $runningsum + $sum;

            $num++;

        } // end of the while loop
        
        $data->rows         = $result;          // Array
        $data->total        = $runningtotal;    // Int
        $data->sum          = $runningsum;      // Int

        $this->getChartHourly( $data, "Yesterday (Hourly), $yesterday", 'graph' );
        $this->getChartHourly( $data, "Yesterday (Hourly), $yesterday", 'graphSum', 'sumdata', 'Line' );

        $this->assign( 'graphData', $data );
        
        return;
    }
    
    /**
     *
     * @return unknown_type
     */
    function _lastSeven()
    {
        $database = JFactory::getDBO();
        $base = new AmbraHelperBase();
        $today = $base->getToday();
        $end_datetime = $today;
        $query = " SELECT DATE_SUB('".$today."', INTERVAL 7 DAY) ";
        $database->setQuery( $query );
        $start_datetime = $database->loadResult();

        $runningtotal = 0;
        $runningsum = 0;
        $data = new stdClass();
        $num = 0;
        $result = array();
        $curdate = $start_datetime;
        $enddate = $end_datetime;
        while ($curdate <= $enddate)
        {
            // set working variables
            $variables = AmbraHelperBase::setDateVariables( $curdate, $enddate, 'daily' );
            $thisdate = $variables->thisdate;
            $nextdate = $variables->nextdate;

            // grab all records
            $model = JModel::getInstance( 'Orders', 'AmbraModel' );
            $model->setState( 'filter_date_from', $thisdate );
            $model->setState( 'filter_date_to', $nextdate );
            // set query for orderstate range
            $ordersQuery = $model->getQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setQuery($ordersQuery);
            $rows = $model->getList();
            
            $total = count( $rows );
            $model->setState('select', 'SUM(`order_total`)');
            $ordersQuery = $model->getResultQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setResultQuery($ordersQuery);
            $sum = $model->getResult();

            //store the value in an array
            $result[$num]['rows']       = $rows;
            $result[$num]['datedata']   = getdate( strtotime($thisdate) );
            $result[$num]['countdata']  = $total;
            $value = AmbraHelperBase::number( $sum );
            $result[$num]['sumdata']    = empty($value) ? $sum : $value;
            $runningtotal               = $runningtotal + $total;
            $runningsum                 = $runningsum + $sum;

            // increase curdate to the next value
            $curdate = $nextdate;
            $num++;

        } // end of the while loop

        $data->rows         = $result;          // Array
        $data->total        = $runningtotal;    // Int
        $data->sum          = $runningsum;      // Int

        $this->getChartDaily( $data, 'Last Seven Days', 'graph' );
        $this->getChartDaily( $data, 'Last Seven Days', 'graphSum', 'sumdata', 'Line' );

        $this->assign( 'graphData', $data );
    }
    
    /**
     *
     * @return unknown_type
     */
    function _thisYear()
    {
    	$year = gmdate('Y');
    	
    	$base = new AmbraHelperBase();
    	$newyear = $base->getOffsetDate( "$year-01-01 00:00:00" );
    	
        $database = JFactory::getDBO();

        $runningtotal = 0;
        $runningsum = 0;
        $data = new stdClass();
        $num = 0;
        $result = array();
        
        for ($curmonth = 1; $curmonth <= 12; $curmonth++)
        {
            $thismonth = $curmonth < 10 ? '0'.$curmonth : $curmonth;
            
            $start_ts = $base->getOffsetDate( "$year-$thismonth-01 00:00:00" );
	        $database = JFactory::getDBO();
	        $database->setQuery("SELECT ADDDATE('".$start_ts."', INTERVAL 1 MONTH)");
	        $nextmonth = $database->loadResult();
            $end_ts = $base->getOffsetDate( $nextmonth );
            
            // grab all records
            $model = JModel::getInstance( 'Orders', 'AmbraModel' );
            $model->setState( 'filter_date_from', $start_ts );
            $model->setState( 'filter_date_to', $end_ts );
            // set query for orderstate range
            $ordersQuery = $model->getQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setQuery($ordersQuery);
            $rows = $model->getList();
            
            $total = count( $rows );
            $model->setState('select', 'SUM(`order_total`)');
            $ordersQuery = $model->getResultQuery();
            $ordersQuery->where("tbl.order_state_id IN (".$this->getStatesCSV().")");
            $model->setResultQuery($ordersQuery);
            $sum = $model->getResult();

            //store the value in an array
            $result[$num]['rows']       = $rows;
            //$result[$num]['datedata']   = getdate( strtotime($thisdate) );
            $result[$num]['datedata']   = $thismonth;
            $result[$num]['countdata']  = $total;
            $value = AmbraHelperBase::number( $sum );
            $result[$num]['sumdata']    = empty($value) ? $sum : $value;
            $runningtotal               = $runningtotal + $total;
            $runningsum                 = $runningsum + $sum;

            $num++;

        } // end of the while loop
        
        $data->rows         = $result;          // Array
        $data->total        = $runningtotal;    // Int
        $data->sum          = $runningsum;      // Int

        $this->getChartHourly( $data, "Year to Date (Monthly)", 'graph' );
        $this->getChartHourly( $data, "Year to Date (Monthly)", 'graphSum', 'sumdata', 'Line' );

        $this->assign( 'graphData', $data );
        
        return;
    }

    /**
     * getChartDaily function.
     * 
     * @access public
     * @param mixed $data
     * @param mixed $chart_title
     * @param mixed $variable_name
     * @param string $type. (default: 'countdata')
     * @param string $chart_type. (default: 'Column')
     * @return void
     */
    function getChartBarDaily( $data, $chart_title, $variable_name, $chart_type='Column' )
    {
        $args = array();

        /** Charts expect data to come as an array of objects where the objects
         *  look like:
         *  JObject(){
         *     public $value;
         *     public $label;
         *  }
         */
        $args['data'] = array();
        $datasets = array();
        
        if (!empty($data)) 
        {
            foreach ($data->datasets as $key=>$dataset)
            {
                $datasets[$key]['categories'] = $dataset->categories;
                $datasets[$key]['title'] = $dataset->title;
                $datasets[$key]['data'] = array();
                foreach ($dataset->rows as $r) 
                {
                    $obj = new JObject;
                    $obj->value = floatval(str_replace(',', '', $r['value']));
                    $obj->label = $r['label'];
                    $datasets[$key]['data'][] = $obj;
                }   
            }
        }
        
        $args['datasets'] = $datasets;
        $args['title'] = $chart_title;
        $args['type']  = $chart_type;

        // Try to render the chart via an installed plugin first.
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('renderAmbraChart', $args);

        if (empty($results)) {
            // No Ambra Charts plugin enabled, use Google Charts.
            JLoader::import( 'com_ambra.library.charts', JPATH_ADMINISTRATOR.'/components' );
            $chart = AmbraCharts::renderFusionChart($args['datasets'], $chart_title, $chart_type);
        } else {
            $chart = $results[0];
        }

        $row = new JObject();
        $row->image = $chart;
        $this->assign( "$variable_name", $row);
    }
    
    /**
     *
     * @param unknown_type $data
     * @param unknown_type $chart_title
     * @param unknown_type $variable_name
     * @return unknown_type
     */
    function getChartHourly( $data, $chart_title, $variable_name, $type='countdata', $chart_type='Column' )
    {
        $args = array();

        /** Ambra Charts expect data to come as an array of objects where the objects
         *  look like:
         *  JObject(){
         *     public $value;
         *     public $label;
         *  }
         */
        $args['data'] = array();
        
        if (!empty($data)) 
        {
            foreach ($data->rows as $r) 
            {
                $obj = new JObject;
                $obj->value = floatval(str_replace(',', '', $r[$type]));
                $obj->label = $r['datedata'];
                //$obj->label = $r['datedata']['mon'].'/'.$r['datedata']['mday'];
                $args['data'][] = $obj;
            }
        }

        $args['title'] = $chart_title;
        $args['type']  = $chart_type;

        // Try to render the chart via an installed plugin first.
        $dispatcher =& JDispatcher::getInstance();
        $results = $dispatcher->trigger('renderAmbraChart', $args);

        if (empty($results)) {
            // No Ambra Charts plugin enabled, use Google Charts.
            JLoader::import( 'com_ambra.library.charts', JPATH_ADMINISTRATOR.DS.'components' );
            $chart = AmbraCharts::renderGoogleChart($args['data'], $chart_title, $chart_type);
        } else {
            $chart = $results[0];
        }

        $row = new JObject();
        $row->image = $chart;
        $this->assign( "$variable_name", $row);
        
        return;
    	
        JLoader::import( 'com_synk.libchart.classes.libchart', JPATH_SITE.DS.'media' );
        $row = new JObject();
        // Create Chart from Data
            // first specify the chart type and dimensions
            $chart = new VerticalBarChart(600, 250);

            // then set title
            $row->title = JText::_( $chart_title );
            $chart->setTitle( null );

            // then create a dataset
            $dataSet = new XYDataSet();

            $max = 0;
            if (is_array($data->rows)) { foreach ($data->rows as $r) {
                if ($r[$type] > $max) { $max = $r[$type]; }
                $dataSet->addPoint(new Point($r['datedata'], $r[$type]));
            } } // end foreach

            // link dataset to chart
            $chart->setDataSet($dataSet);
            $chart->bound->setUpperBound($max);

            // render to a png file
            jimport('joomla.user.helper');
            $newfilename = JUserHelper::genRandomPassword();
            $tmp_path = JFactory::getApplication()->getCfg('tmp_path');
            $chart->render( $tmp_path.DS.$newfilename.'.png' );

            $row->image = JHTML::_( "image.site", "$newfilename.png", "../tmp/" );

        $this->assign( "$variable_name", $row );
    }
}