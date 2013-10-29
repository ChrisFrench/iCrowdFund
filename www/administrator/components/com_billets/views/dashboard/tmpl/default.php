<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); 

?>
<?php DSC::loadHighcharts(); ?>
<table style="width: 100%;">
<tr>
	<td style="width: 70%; max-width: 70%; vertical-align: top; padding: 0px 5px 0px 5px;">
	
		<?php DSC::loadHighcharts(); ?>
	
		
		<?php
		
		jimport('joomla.html.pane');
		$tabs = JPane::getInstance( 'tabs' );

		echo $tabs->startPane("tabone");
		echo $tabs->startPanel( JText::_('COM_BILLETS_NEW_TICKETS'), "newtickets" );

			echo "<h2>".@$this->lastThirty->title."</h2>";
			echo @$this->lastThirty->image;

		echo $tabs->endPanel();
		echo $tabs->endPane();
		?>
	
		<?php
		$modules = JModuleHelper::getModules("billets_dashboard_main");
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$attribs 	= array();
		$attribs['style'] = 'xhtml';
		foreach ( @$modules as $mod )
		{
			echo $renderer->render($mod, $attribs);
		}
		?>
	</td>
	<td style="vertical-align: top; width: 30%; min-width: 30%; padding: 0px 5px 0px 5px;">

        <?php
        // since this is the only module that doesn't have a variable length, display it first, lest it get lost below the fold
        if (!empty($this->feedbackstats))
        {
            echo $this->feedbackstats;
        } 
        ?>
	
        <table class="adminlist" style="margin-bottom: 5px;">
        <thead>
            <tr>
                <th colspan="3"><?php echo JText::_('COM_BILLETS_TICKETS_PER_STATUS'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php /*
        $chartdata = array();
        foreach (@$this->ticketstates as $ticketstate) :
		$chartdata[] = array( $ticketstate->title , $ticketstate->count );
		 ?>
           
        <?php endforeach; ?>	
        	
        <?php
		
		
		// HIGHROLLER PIE CHART
		
		
		$chart = new HighRollerPieChart();
		
		$chart -> chart -> renderTo = 'chart';
		$chart -> chart -> plotBackgroundColor = NULL;
		$chart -> chart -> plotBorderWidth = NULL;
		$chart -> chart -> plotShadow = false;
		$chart -> title -> text = 'Pie Chart';
		
		$chart -> plotOptions = new stdClass();
		$chart -> plotOptions -> pie = new stdClass();
		$chart -> plotOptions -> pie -> allowPointSelect = true;
		$chart -> plotOptions -> pie -> cursor = 'pointer';
		$chart -> plotOptions -> pie -> dataLabels = new stdClass();
		$chart -> plotOptions -> pie -> dataLabels -> enabled = true;
		$chart -> plotOptions -> pie -> dataLabels -> color = '#000000';
		$chart -> plotOptions -> pie -> dataLabels -> connectorColor = '#000000';

		$series = new HighRollerSeriesData();
		$series -> addName('Rating') -> addData($chartdata);
		//$series -> type = 'pie';
		$chart -> addSeries($series);
		
		?>
		
        <div id="chart" style="width: 100%;"></div>
        
        <script type="text/javascript">
          <?php echo $chart->renderChart();?>
        </script>*/	?>
        	
        	
        <?php foreach (@$this->ticketstates as $ticketstate) : ?>
            <tr>
                <th>
                    <a href="<?php echo $this->link."&filter_labelid=&filter_stateid=".$ticketstate->id; ?>">
                    <?php echo JText::_( $ticketstate->title ); ?>
                    </a>
                </th>
                <td style="text-align: right;"><?php echo BilletsHelperBase::number( $ticketstate->count ); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
        		
		<table class="adminlist" style="margin-bottom: 5px;">
		<thead>
			<tr>
				<th colspan="3"><?php echo JText::_('COM_BILLETS_OPEN_TICKETS_PER_LABEL'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach (@$this->labels as $label) : ?>
			<tr>
				<th>
				    <a href="<?php echo $this->link."&filter_labelid=".$label->id."&filter_stateid=".Billets::getInstance()->get( 'state_new' ); ?>">
				    <?php echo BilletsHelperTicket::displayLabel($label); ?>
				    </a>
				</th>
				<td style="text-align: right;"><?php echo BilletsHelperBase::number( $label->count ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
        		
		<?php
		$modules = JModuleHelper::getModules("billets_dashboard_right");
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$attribs 	= array();
		$attribs['style'] = 'xhtml';
		foreach ( @$modules as $mod )
		{
			echo $renderer->render($mod, $attribs);
		}
		?>
	</td>
</tr>
</table>