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

Billets::load( 'BilletsViewBase', 'views._base' );
Billets::load( 'BilletsHelperBase', 'helpers._base' );


class BilletsViewDashboard extends BilletsViewBase  
{
	
	/**
     * The default toolbar for a list
     * @return unknown_type
     */
    function _defaultToolbar()
    {
    }
	
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function display($tpl=null) 
	{
		
	
		if (empty($this->hidestats))
		{
			$config = Billets::getInstance();
			
			$this->assign( 'link', "index.php?option=com_billets&view=tickets&filter_order=tbl.last_modified_datetime&filter_direction=ASC" );
			
			if ($config->get('display_dashboard_statistics', '1'))
			{
				$this->_lastThirty();
			}

		    if ($config->get('display_dashboard_ticketstatestatistics', '1'))
            {
                $this->_ticketstates();
            }
			
		    if ($config->get('display_dashboard_labelstatistics', '1'))
            {
                $this->_labels();
            }
            
		    if ($config->get('display_dashboard_feedbackstats', '1'))
            {
                $this->_feedbackstats();
            }
		}
		
		// form
			$validate = JUtility::getToken();
			$form = array();
			$controller = strtolower( $this->get( '_controller', JRequest::getVar('controller', JRequest::getVar('view') ) ) );
			$view = strtolower( $this->get( '_view', JRequest::getVar('view') ) );
			$action = $this->get( '_action', "index.php?option=com_billets&controller={$controller}&view={$view}" );
			$form['action'] = $action;
			$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
			$this->assign( 'form', $form );
		
        parent::display($tpl);
    }
    
    /**
     *
     * @return unknown_type
     */
    function _lastThirty()
    {
    	$database = JFactory::getDBO();
    	$base = new BilletsHelperBase();
		$today = $base->getToday();
		$end_datetime = $today;
			$query = " SELECT DATE_SUB('".$today."', INTERVAL 1 MONTH) ";
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
				$variables = BilletsHelperBase::setDateVariables( $curdate, $enddate, 'daily' );
				$thisdate = $variables->thisdate;
				$nextdate = $variables->nextdate;

			// grab all records
				$model = JModel::getInstance( 'Tickets', 'BilletsModel' );
				$model->setState( 'select', 'tbl.id' ); // only select id to save memory
				$model->setState( 'filter_date_from', $thisdate );
				$model->setState( 'filter_date_to', $nextdate );
				$rows = $model->getList();
				$total = count($rows);

			//store the value in an array
			$result[$num]['rows']		= $rows;
			$result[$num]['datedata'] 	= getdate( strtotime($thisdate) );
			$result[$num]['countdata']	= $total;
			// $value = BilletsHelperBase::number( $sum );
			//$result[$num]['sumdata']	= empty($value) ? $sum : $value;
			$runningtotal 				= $runningtotal + $total;
			//$runningsum 				= $runningsum + $sum;

			// increase curdate to the next value
			$curdate = $nextdate;
			$num++;

		} // end of the while loop

		$data->rows 		= $result;
		$data->total 		= $runningtotal;
		//$data->sum 			= $runningsum;
		
		// format the data
		$cdata = new JObject();
        $categories = array();
        if (is_array($data->rows)) { foreach ($data->rows as &$r) {
            $r['label'] = $r['datedata']['mon']."/".$r['datedata']['mday'];
            $r['value'] = $r['countdata'];
            if (!in_array($r['label'], $categories)) {
                $categories[] = $r['label'];
            }
        } } // end foreach
        
        $data->title = 'New Tickets';
        $data->categories = $categories;
        $cdata->datasets = array( $data );
        $this->getChartBarDaily( $cdata, 'Last Thirty Days', 'lastThirty' );

    }
    
    /**
     *
     * @param unknown_type $data
     * @param unknown_type $chart_title
     * @param unknown_type $variable_name
     * @return unknown_type
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
        //$args['data'] = array();
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
        $results = $dispatcher->trigger('renderBilletsChart', $args);

        if (empty($results)) 
        {
         
            $browser = new BilletsBrowser;
            if ( $browser->getBrowser() == BilletsBrowser::BROWSER_IE && $browser->getVersion() < '8' ) 
            {
                // if IE7, use Google Charts 
                $chart = BilletsCharts::renderGoogleChart($args['datasets'], $chart_title, $chart_type);            
            }
                else
            {
                // No Charts plugin enabled, use Fusion Charts.
                $chart = BilletsCharts::renderFusionChart($args['datasets'], $chart_title, $chart_type);
            }
        } 
            else 
        {
            $chart = $results[0];
        }

        $row = new JObject();
        $row->image = $chart;
        $this->assign( "$variable_name", $row);
    }
	
	/**
	 * 
	 * @param $text
	 * @param $link
	 * @param $img
	 * @return unknown_type
	 */
	function _row( $title, $link='', $img='' ) 
	{
		$text = "";
		
		$innertext = "";
		if ($link) {
			$innertext .= "<a href='{$link}'>";
		}
		$innertext .= $title;
		if ($link) {
			$innertext .= "</a>";
		}
		
		$icon = '';
		if ($img) {
			$icon .= "<img src='{$img}'>";	
		}
		
		$text .= "
			<tr>
				<td>{$innertext}</td>
			</tr>
		";
		
		return $text;
	}
	
	/**
	 * 
	 * @param $status
	 * @return unknown_type
	 */
	function _closed( $status='2' )
	{
		static $results;
		
		if (!is_array($results))
		{
			$results = array();
		}
		
		if (empty($results[$status]))
		{
			$database = JFactory::getDBO();
			$query = "
				SELECT 
					COUNT(*)
				FROM 
					#__billets_tickets as db
				WHERE 1
				AND
					db.stateid = '{$status}'
			";
			$database->setQuery($query);
			$results[$status] = $database->loadResult();	
		}
		
		return $results[$status];
	}
	
	/**
	 * 
	 * @param $status
	 * @return unknown_type
	 */
	function _closedWithResponse( $status='2' )
	{
		static $results;
		
		if (!is_array($results))
		{
			$results = array();
		}
		
		if (empty($results[$status]))
		{
			$database = JFactory::getDBO();
			$query = "
				SELECT 
					COUNT(*)
				FROM 
					#__billets_tickets as db
				WHERE 1
				AND
					db.stateid = '{$status}'
				AND 
					db.firstresponse_by > '0'
				AND
					db.feedback_rating > '0'
			";
			$database->setQuery($query);		
			$results[$status] = $database->loadResult();	
		}
		
		return $results[$status];
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function _avgFeedback( $status='2' )
	{
		$result = null;
		$total	= $this->_closedWithResponse();
		if ($total)
		{
			$database = JFactory::getDBO();
			$query = "
				SELECT 
					SUM(db.feedback_rating)
				FROM 
					#__billets_tickets as db
				WHERE 1
				AND
					db.stateid = '{$status}'
				AND 
					db.firstresponse_by > '0'
				AND
					db.feedback_rating > '0'
			";
			$database->setQuery($query);		
			$ratings = $database->loadResult();
		
			$result = $ratings / $total;
		}	
		return $result;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function _avgFeedbackLastMonth( $status='2' )
	{
		$results = new JObject();
		$today = BilletsHelperBase::getToday();

			$database = JFactory::getDBO();
			$start = getdate( strtotime($today) );
			// first day of month
			$thismonth = date("Y-m-d", strtotime($start['year']."-".$start['mon']."-01"));		
				$query = " SELECT DATE_SUB('".$thismonth."', INTERVAL 1 MONTH) ";
				$database->setQuery( $query );
			$lastmonth = $database->loadResult();
	
			$query = "
				SELECT 
					COUNT(*) AS count, SUM(db.feedback_rating) AS ratings
				FROM 
					#__billets_tickets as db
				WHERE 1
				AND
					db.stateid = '{$status}'
				AND 
					db.firstresponse_by > '0'
				AND 
					db.closed_datetime >= '".$database->getEscaped( trim( strtolower( $lastmonth ) ) )."'
				AND
					db.closed_datetime < '".$database->getEscaped( trim( strtolower( $thismonth ) ) )."'
			";
			$database->setQuery($query);		
			$results = $database->loadObject();
			$results->result = '';
			if (!empty($results->count))
			{
				$results->result = $results->ratings / $results->count;
			}
			
		return $results;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	function _avgFeedbackThisMonth( $status='2' )
	{
		$results = new JObject();
		$today = BilletsHelperBase::getToday();

			$database = JFactory::getDBO();
			$start = getdate( strtotime($today) );
			// first day of month
			$thismonth = date("Y-m-d", strtotime($start['year']."-".$start['mon']."-01"));
	
			$query = "
				SELECT 
					COUNT(*) AS count, SUM(db.feedback_rating) AS ratings
				FROM 
					#__billets_tickets as db
				WHERE 1
				AND
					db.stateid = '{$status}'
				AND 
					db.firstresponse_by > '0'
				AND 
					db.closed_datetime >= '".$database->getEscaped( trim( strtolower( $thismonth ) ) )."'
				AND
					db.closed_datetime < '".$database->getEscaped( trim( strtolower( $today ) ) )."'
			";
			$database->setQuery($query);		
			$results = $database->loadObject();
			$results->result = '';
			if (!empty($results->count))
			{
				$results->result = $results->ratings / $results->count;
			}
			
		return $results;
	}
	
	/**
	 * TODO Move this to a module
	 *
	 * @param 	array		holds the old user data
	 * @param 	boolean		true if a new user is stored
	 */
	function _feedbackstats() 
	{
		// feedback last month
		// feedback this month
		
		$status = Billets::getInstance()->get( 'state_closed' );
		$link = "index.php?option=com_billets&view=tickets&filter_order=tbl.last_modified_datetime&filter_direction=DESC&filter_labelid=&filter_stateid=".$status;
		
		$text = '';
			$text .= "<table class='adminlist'>";
				$text .= "<thead><tr><th>".JText::_('COM_BILLETS_FEEDBACK_STATISTICS')."</th></tr></thead>";

			// closed total
			    $status = Billets::getInstance()->get( 'state_closed' );			
				$total	= $this->_closed( $status );
				$total 	= number_format( $total, '0', '', ',' );
				$title 	= $total." ".JText::_('COM_BILLETS_CLOSED');
				$text	.= $this->_row( $title, $link );
				
			// closed total	w/ response	
			    $status = Billets::getInstance()->get( 'state_closed' );
				$total	= $this->_closedWithResponse( $status );
				$total 	= number_format( $total, '0', '', ',' );
				$title 	= $total." ".JText::_('COM_BILLETS_CLOSED_WITH_RESPONSE');
				$text	.= $this->_row( $title, $link );

			// avg feedback
				$status = Billets::getInstance()->get( 'state_closed' );
				$total	= $this->_avgFeedback( $status );
				$total 	= number_format( $total, '1', '.', ',' );
				$title	= "<span style='float: right;'>";
				$title	.= BilletsHelperTicket::getRatingImage( $total );
				$title	.= "</span>";
				$title 	.= JText::_('COM_BILLETS_AVERAGE_FEEDBACK').": ".$total;
				$text	.= $this->_row( $title, $link );

			// avg feedback last month
				$status = Billets::getInstance()->get( 'state_closed' );
				$result = $this->_avgFeedbackLastMonth( $status );
				$total 	= number_format( (float) @$result->result, '1', '.', ',' );
				$title	= "<span style='float: right;'>";
				$title	.= BilletsHelperTicket::getRatingImage( $total );
				$title	.= "</span>";
				$title 	.= JText::_('COM_BILLETS_LAST_MONTH').": ".$total." ({$result->count})";
				$text	.= $this->_row( $title, $link );

			// avg feedback this month
				$status = Billets::getInstance()->get( 'state_closed' );
				$result	= $this->_avgFeedbackThisMonth( $status );
				$total 	= number_format( (float) @$result->result, '1', '.', ',' );
				$title	= "<span style='float: right;'>";
				$title	.= BilletsHelperTicket::getRatingImage( $total );
				$title	.= "</span>";
				$title 	.= JText::_('COM_BILLETS_THIS_MONTH').": ".$total." ({$result->count})";
				$text	.= $this->_row( $title, $link );
				
			$text .= "</table>";
		
		$this->assign( 'feedbackstats', $text );	
		return;
	}
    
    function _statistics()
    {
    	return true;
    	$row = new JObject();
    	$database = JFactory::getDBO();
    	$model = $this->getModel();
    	
    	// date 
			$today = $model->getToday();
			$query = " SELECT DATE_ADD('".$today."', INTERVAL 1 DAY) ";
			$database->setQuery( $query );
			$tomorrow = $database->loadResult();
			$start = getdate( strtotime($today) );
			$thismonth = $model->getOffsetDate( date("Y-m-d", strtotime($start['year']."-".$start['mon']."-01")) );
			$query = " SELECT DATE_SUB('".$thismonth."', INTERVAL 1 MONTH) ";
			$database->setQuery( $query );
			$lastmonth = $database->loadResult();
    	
    	// commissions
    	$model = JModel::getInstance('Commissions', 'BilletsModel');
	    	$row->sumLastMonthCommissions = $model->selectPeriodData( $lastmonth, $thismonth, "SUM(`value`)", "result" );
	    	$row->lastMonthCommissions = $model->selectPeriodData( $lastmonth, $thismonth, "COUNT(*)", "result" );
	    	$row->sumThisMonthCommissions = $model->selectPeriodData( $thismonth, $tomorrow, "SUM(`value`)", "result" );
	    	$row->thisMonthCommissions = $model->selectPeriodData( $thismonth, $tomorrow, "COUNT(*)", "result" );
	    	$row->sumTotalCommissions = $model->selectTotal( "SUM(`value`)" );
	    	$row->totalCommissions = $model->selectTotal();
	    	$row->disabledCommissions = $model->selectDisabled();
    	
    	// accounts
    	$model = JModel::getInstance('Accounts', 'BilletsModel');
	    	$row->lastMonthAccounts = $model->selectPeriodData( $lastmonth, $thismonth, "COUNT(*)", "result" );
	    	$row->thisMonthAccounts = $model->selectPeriodData( $thismonth, $tomorrow, "COUNT(*)", "result" );
	    	$row->unapprovedAccounts = $model->selectUnapproved();
	    	$row->disabledAccounts = $model->selectDisabled();
	    	$row->totalAccounts = $model->selectTotal();
    	
    	// logs
    	$model = JModel::getInstance('Logs', 'BilletsModel');
	    	$row->lastMonthLogs = $model->selectPeriodData( $lastmonth, $thismonth, "COUNT(*)", "result" );
	    	$row->thisMonthLogs = $model->selectPeriodData( $thismonth, $tomorrow, "COUNT(*)", "result" );
	    	$row->totalLogs = $model->selectTotal();
	    	
    	$this->assign('statistics', $row);
    }
    
    function _labels()
    {
     
        $model = JModel::getInstance('Labels', 'BilletsModel');
        $model->setState( 'order', 'title' ); 
        $labels = $model->getList();

        foreach (@$labels as $label)
        {
			$label->label_title = $label->title;
			$label->labelid = $label->id;
			$label->label_color = $label->color;
			$model_t = JModel::getInstance('Tickets', 'BilletsModel');
			$model_t->setState('filter_labelid', $label->id);
			$model_t->setState('filter_stateid', Billets::getInstance()->get( 'state_new', '1' ) );
			$label->count = $model_t->getTotal();
        }
        
        // do the ones with no label
        $label = $model->getTable();
        $label->labelid     = '-1';
        $label->id          = '-1';
        $label->label_title = 'No Label';
        $label->label_color = '#FFFFFF';
        
        $model_t = JModel::getInstance('Tickets', 'BilletsModel');
        $model_t->setState('filter_labelid', $label->id);
        $model_t->setState('filter_stateid', Billets::getInstance()->get( 'state_new', '1' ) );
        $label->count = $model_t->getTotal();
        
        array_unshift( $labels, $label );
        
        $this->assign( 'labels', $labels );
    }
    
    function _ticketstates()
    {
	
		$items = BilletsTaxonomies::getTree( 'ticketstates' );
        foreach ($items as $item)
        {
            $model_t = JModel::getInstance('Tickets', 'BilletsModel');
            $model_t->setState('filter_stateid', $item->id );
            $item->count = $model_t->getTotal();
        }
        $this->assign( 'ticketstates', $items );
    }
}