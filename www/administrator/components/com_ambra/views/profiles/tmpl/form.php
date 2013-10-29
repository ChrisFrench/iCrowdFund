<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

	<fieldset>
		<legend><?php echo JText::_('Form'); ?></legend>
			<table class="admintable">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<input type="text" name="profile_name" id="profile_name" size="48" maxlength="250" value="<?php echo @$row->profile_name; ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Enabled' ); ?>:
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'profile_enabled', '', @$row->profile_enabled ); ?>
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Points Allowed Per Day' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="profile_max_points_per_day" id="profile_max_points_per_day" size="10" maxlength="11" value="<?php echo @$row->profile_max_points_per_day; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Image' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="profile_img" id="profile_img" size="48" maxlength="250" value="<?php echo @$row->profile_img; ?>" />
                    </td>
                </tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Description' ); ?>:
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'profile_description',  @$row->profile_description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Profile Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="profile_params" id="profile_params" rows="10" cols="35"><?php echo @$row->profile_params; ?></textarea>
                    </td>
                </tr>
			</table>

			<input type="hidden" name="id" value="<?php echo @$row->profile_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>