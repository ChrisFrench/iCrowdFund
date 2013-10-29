<h2> <?php echo JText::_('COM_AMBRA_DELETE_ACCOUNT') ?></h2>
<form action="<?php echo JRoute::_('index.php?option=com_ambra&view=delete') ?>" method="post">
<input type="submit" name="submit" value="CONFIRM DELETE ME FOREVER" class="button btn btn-danger" >
<div class="alert alert-danger">THIS CAN NOT BE UNDONE</div>
<input type="hidden" name="confirm" value="1">
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="doDelete">
</form>