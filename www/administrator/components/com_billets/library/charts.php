<?php
/**
 * @version 1.5
 * @package Billets
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class BilletsCharts extends DSCCharts
{
    /**
     * renderGoogleChart function.
     * 
     * @access public
     * @param mixed $data
     * @param string $title. (default: 'A Google Chart')
     * @param string $type. (default: 'Column')
     * @param int $width. (default: 900)
     * @param int $height. (default: 250)
     * @return void
     */
    public static function renderFusionChart($data, $title='A Fusion Chart', $type='Column', $width=550, $height=250)
    {
        $chart = 'No chart produced yet.';
        
        if (!empty($data)) 
        {
            // Include the FusionCharts Class file
            require_once( JPATH_SITE.DS.'media'.DS.'com_billets'.DS.'fusioncharts'.DS.'FusionCharts_Gen.php' );
            JHTML::_('script', 'FusionCharts.js', 'media/com_billets/fusioncharts/');

            $title  = JText::_( $title );

			//Column2D    Single Series Column 2D Chart
			//Column3D    Single Series Column 3D Chart
			//Line    Single Series Line Chart
			//Pie3D   Single Series Pie 3D Chart
			//Pie2D   Single Series Pie 2D Chart
			//Bar2D   Single Series Bar 2D Chart
			//Area2D  Single Series Area 2D Chart
			//Doughnut2D  Single Series Doughnut 2D Chart
			//MSColumn3D  Multi-Series Column 3D Chart
			//MSColumn2D  Multi-Series Column 2D Chart
			//MSArea2D    Multi-Series Column 2D Chart
			//MSLine  Multi-Series Line Chart
			//MSBar2D     Multi-Series Bar Chart
			//StackedColumn2D     Stacked Column 2D Chart
			//StackedColumn3D     Stacked Column 3D Chart
			//StackedBar2D    Stacked Bar 2D Chart
			//StackedArea2D   Stacked Area 2D Chart
			//MSColumn3DLineDY    Combination Dual Y Chart
			//(Column 3D + Line)
			//MSColumn2DLineDY    Combination Dual Y Chart
			//(Column 2D + Line) 
            
            // Chart types
            $categories = false;
            switch ($type) {
                case 'MSColumn2D':
                    $type = 'MSColumn2D';
                    $categories = true;
                    break;
                case 'MSColumn3D':
                    $type = 'MSColumn3D';
                    $categories = true;
                    break;
            	case 'Column3D':
                    $type = 'Column3D';
                    break;
                case 'Pie3D':
                    $type = 'Pie3D';
                    break;
                case 'Pie':
                case 'Pie2D':
                    $type = 'Pie2D';
                    break;
                case 'Bar':
                case 'Bar2D':
                    $type = 'Bar2D';
                    break;
                case 'Line':
                    $type = 'Line';
                    break;
                default:
                    $type = 'Column2D';
                    break;
            }

            // Create a new instance of the FusionCharts class
            $FC = new FusionCharts($type, $width, $height);

            // Tell the object where the SWF files live.
            $app = JFactory::getApplication();
            if ($app->isAdmin())
            {
                $FC->setSWFPath( "../media/com_billets/fusioncharts/Charts/");	
            }
                else
            {
                $FC->setSWFPath( "media/com_billets/fusioncharts/Charts/");	
            }

            $max = 0;
            foreach ($data as $dataset)
            {
            	if (!empty($dataset['categories']) && $categories)
            	{
            	   foreach ($dataset['categories'] as $cat)
            	   {
            	       $FC->addCategory($cat);
            	   }	
            	}
            	
            	$FC->addDataset($dataset['title']);
	            foreach ($dataset['data'] as $obj) 
	            {
	                $max = ($obj->value > $max) ? $obj->value : $max;
	                $FC->addChartData($obj->value, 'name='.$obj->label);
	            }
            }

            // Set chart attributes
            $option = ($max == 0) ? 'yAxisMaxValue=100' : ""; // "yAxisMaxValue={$max}";
            $strParam="caption=$title;showValues=0;rotateNames=1;decimalPrecision=0;formatNumberScale=0;$option";
            $FC->setChartParams($strParam);

            ob_start();
            $FC->renderChart();
            $chart = ob_get_contents();
            ob_end_clean();

        }
        
        return $chart;
    }
	
    
}