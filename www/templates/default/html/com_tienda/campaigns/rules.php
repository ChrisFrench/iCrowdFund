<?php defined('_JEXEC') or die('Restricted access'); ?>
<style>
ol.rules {
	padding-left: 85px;
}
ol.rules li {
margin-bottom: 10px;
margin-left: 10px;
list-style-type: upper-roman;
list-style: upper-roman;
}
</style>
<div>
	
	<h1 class="indent-60 lubefont">Project Guide Lines</h1>
		<br />
	
		<br />
	<div class="row ">
		<div class="rulesHolder"> 
			<ol class="rules">
				
		
							</ol>
		</div>
	</div>
	<br />
	<br />
	<div class="" style="margin-left: 45px;">	
		<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns&layout=form' );  ?>">
  
  <label class="checkbox">
			<input type="checkbox" name="terms" id="terms">	
<?php echo JText::_('COM_TIENDA_CAMPAIGN_CREATE_RULES'); ?>
</br> </label>
  <button id="submit" type="submit" disabled="disabled" class="btn btn-primary btn-martop">OK I Won’t Break the Rules</button>
  <input name="layout" type="hidden" value="campaign_type">
  <input name="accept" type="hidden" value="1">
</form>
		
	</div>
</div>

<script>
	jQuery(document).ready(function() {

		jQuery('#terms').change(function() {
			if (this.checked) {
				jQuery('#submit').removeAttr("disabled");
			} else {
				jQuery('#submit').attr("disabled", true);
			}

		});

	}); 
</script>




