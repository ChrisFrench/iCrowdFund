<?php
	defined('_JEXEC') or die('Restricted access');
	$tabs = @$vars->tabs;
	$row = @$vars->row;
	// Tab
?>

 <div class="tab-pane" id="campaigns">

		<div class="tienda_campaigns">
			<fieldset>
			<legend>Campaigns</legend>
			<div class="well">Not sure what needs to go here yet</div>  
			</fieldset>
		</div>
<?php
	// fire plugin event here to enable extending the form
	JDispatcher::getInstance()->trigger('onDisplayProductFormCampaignForms', array( $row ) );                    
?>
	  <div style="clear: both;"></div>
</div>