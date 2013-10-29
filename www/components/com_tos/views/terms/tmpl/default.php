<?php 
/**
 * @package	Terms of Service
 * @author 	Ammonite Networks
 * @link 	http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<form class="form-inline center page" action="<?php echo $this->action; ?>" method="post" name="terms" id="terms">

<div>
	
	<h1><?php echo $this->row->terms_title; ?></h1>
	<br> <br> <br>
	<div class="row ">
		<div class="termsWrapper center  page " style="height:600px;overflow: auto;" style=""> <div style="" class="text">
		<?php echo $this->row->terms; ?>
			
		</div>
		</div>
	</div>
	<br />
	<br />
	<div class="row ">	
		
  <?php if(@$this->accepted && JFactory::getUser()->id) : ?>

<div class="alert alert-success"> You can already accepted these terms.</div>

<?php elseif(JFactory::getUser()->id) : ?>
  <label class="checkbox">
				<input type="checkbox" name="terms" id="terms" onClick="apply()" >
				I Agree to Terms & Conditions above</br> </label>
  <button id="submit" type="submit" disabled="disabled" class="btn btn-primary">Accept</button>
  <input name="task" type="hidden" value="accept">
  <?php echo JHTML::_( 'form.token' ); ?>
  <input name="accept" type="hidden" value="1">
  <input name="return" type="hidden" value="<?php echo $this->return; ?>">
  <input name="terms_id" type="hidden" value="<?php echo $this->row->terms_id; ?>">
  <input name="scope_id" type="hidden" value="<?php echo $this->row->scope_id; ?>">
</form>
	
<script type="text/javascript">
function apply()
{
  document.terms.submit.disabled=true;
  if(document.terms.terms.checked==true)
  {
    document.terms.submit.disabled=false;
  }
  if(document.terms.terms.checked==false)
  {
    document.terms.submit.enabled=false;
  }
}
</script> 
<?php endif; ?>
</div>
</div>

<?php /* we all hate hard coded onCLick events but for sake of usefuliness
Same script with  jQuery 
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
or Mootools 
<script>
var checkboxes = $("input[type='checkbox']"),
    submitButt = $("input[type='submit']");

checkboxes.change(function() {
    // disable submit button only if no checkboxes are checked
    submitButt.attr("disabled", !checkboxes.is(":checked"));
});​
</script>

*/ ?>
