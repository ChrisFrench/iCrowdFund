<?php defined('_JEXEC') or die('Restricted access'); ?>

<div>
	
	<h1>Rules</h1>
	
	<div class="row ">
		
	</div>

	<br />
	<div class="row ">	
		<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns&layout=form' );  ?>">
  
  <label class="checkbox">
	<input type="checkbox" name="terms" id="terms">I Agree to Terms & Conditions above</br> </label>
  <button id="submit" type="submit" disabled="disabled" class="btn btn-primary">Accept</button>
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




