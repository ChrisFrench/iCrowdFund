<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this -> form; ?>
<?php $row = @$this -> row; ?>
<?php JHTML::_('behavior.calendar');
	JHtml::_('behavior.formvalidation');
?>


<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" >

	<fieldset>
		<legend>
			<?php echo JText::_("BASIC INFORMATION"); ?>
		</legend>

		<table class="admintable">
			<tr>
				<td class="key"> <?php echo JText::_('id'); ?>: </td>
				<td> <?php echo @$row -> id; ?> </td>
			</tr>

			<tr>
				<td class="key"> <?php echo JText::_('Title'); ?>: </td>
				<td>
				<input name="title" value="<?php echo @$row -> title; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('Form'); ?>: </td>
				<td>
				<input name="form" value="<?php echo @$row -> form; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('Component'); ?>: </td>
				<td>
				<input name="component" value="<?php echo @$row -> component; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('XML File'); ?>: </td>
				<td>
				<input name="xmlfile" value="<?php echo @$row -> xmlfile; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
	            
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('Effect form side'); ?>: </td>
				<td>
				<?php echo ExtendformSelect::side(@$row -> side); ; ?>
	            
				</td>
			</tr>

			
			
		</table>
	</fieldset>
	<div>
		<input type="hidden" name="id" id="id" value="<?php echo @$row -> id; ?>" />
		<input type="hidden" name="params" id="params" value="" />
		<input type="hidden" name="task" value="" />
	</div>
</form>

<?php
// works for now maybe replace with Jform stuff  later when rest is done

/*
if(!empty($row -> type)){
echo $this -> loadTemplate($row -> type);
} else {
echo 'Select a Type, and save for more options';
}*/
?>

