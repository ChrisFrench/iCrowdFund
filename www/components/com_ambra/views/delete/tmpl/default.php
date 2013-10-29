<?php
/*
IF you don't want to go through the confirm step and jut have the user delete from here,  just add 
<input> type="hidden" name="confirm" value="1">
<?php echo JHTML::_( 'form.token' ); ?>
<input> type="hidden" name="task" value="doDelete">
*/

 ?>

<h2> <?php echo JText::_('COM_AMBRA_DELETE_ACCOUNT') ?></h2>
<form action="<?php echo JRoute::_('index.php?option=com_ambra&view=delete') ?>" method="post">
<input type="submit" name="submit" value="Delete" class="button btn btn-danger" >
<input type="hidden" name="layout" value="confirm">
</form>