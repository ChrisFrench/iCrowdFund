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
						<label for="role_name">
						<?php echo JText::_( 'Name' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="role_name" id="role_name" size="48" maxlength="250" value="<?php echo @$row->role_name; ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<label for="parent_id">
						<?php echo JText::_( 'Parent' ); ?>:
						</label>
					</td>
					<td>
						<?php echo AmbraSelect::role( @$row->parent_id, 'parent_id', '', 'parent_id', false, true ); ?>
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<label for="enabled">
						<?php echo JText::_( 'Enabled' ); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'role_enabled', '', @$row->role_enabled ); ?>
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<label for="role_description">
						<?php echo JText::_( 'Description' ); ?>:
						</label>
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'role_description',  @$row->role_description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Role Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="role_params" id="role_params" rows="10" cols="35"><?php echo @$row->role_params; ?></textarea>
                    </td>
                </tr>
			</table>

			<input type="hidden" name="id" value="<?php echo @$row->role_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>