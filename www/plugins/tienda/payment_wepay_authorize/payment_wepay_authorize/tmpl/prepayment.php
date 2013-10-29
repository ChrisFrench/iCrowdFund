<form action="<?php echo JRoute::_("index.php?option=com_tienda&view=checkout"); ?>" method="post" name="adminForm" enctype="multipart/form-data">

	
	<input type='hidden' name='order_id' value='<?php echo @$vars -> order_id; ?>'>
	<input type='hidden' name='orderpayment_id' value='<?php echo @$vars -> orderpayment_id; ?>'>
	<input type='hidden' name='orderpayment_type' value='<?php echo @$vars -> orderpayment_type; ?>'>
	<input type='hidden' name='task' value='confirmPayment'>
	<input type='hidden' name='paction' value='process'>

	<?php echo JHTML::_('form.token'); ?>

</form>
