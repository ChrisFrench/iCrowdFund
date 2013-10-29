<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; 
JFilterOutput::objectHTMLSafe( $row );
?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

	<fieldset>
		<legend><?php echo JText::_('Form'); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<input type="text" name="action_name" id="action_name" size="48" maxlength="250" value="<?php echo @$row->action_name; ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<label for="enabled">
						<?php echo JText::_( 'Enabled' ); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'action_enabled', '', @$row->action_enabled ); ?>
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<label for="action_description">
						<?php echo JText::_( 'Description' ); ?>:
						</label>
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'action_description',  @$row->action_description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Action Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="action_params" id="action_params" rows="10" cols="35"><?php echo @$row->action_params; ?></textarea>
                    </td>
                </tr>
			</table>

			<input type="hidden" name="id" value="<?php echo @$row->action_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>