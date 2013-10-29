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
                    <td width="100" align="right" class="key">
                        <label for="sender_userid">
                        <?php echo JText::_( 'User' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?php echo $this->elementUser; ?>
                        <?php echo $this->resetUser; ?>             
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Points' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="points" id="points" size="10" maxlength="11" value="<?php echo @$row->points; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Rule' ); ?>:
                    </td>
                    <td>
                        <?php echo AmbraSelect::pointrule( @$row->pointrule_id, 'pointrule_id', '', '', false, true ); ?>
                    </td>
                </tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Enabled' ); ?>:
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'pointhistory_enabled', '', @$row->pointhistory_enabled ); ?>
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Created' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->created_date, "created_date", "created_date", 'Y-M-d h:i:A' ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Expires' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->expire_date, "expire_date", "expire_date", 'Y-M-d h:i:A' ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Activity' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointhistory_name" id="pointhistory_name" size="48" maxlength="250" value="<?php echo @$row->pointhistory_name; ?>" />
                    </td>
                </tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Description' ); ?>:
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'pointhistory_description',  @$row->pointhistory_description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
			</table>

			<input type="hidden" name="id" value="<?php echo @$row->pointhistory_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>