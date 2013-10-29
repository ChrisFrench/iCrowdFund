<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; 
JFilterOutput::objectHTMLSafe( $row );
$url = 'index.php?option=com_ambra&format=raw&task=doTaskAjax&element=codegenerator&elementTask=generate_code'; 
?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

	<fieldset>
		<legend><?php echo JText::_('Form'); ?></legend>
			<table class="admintable">
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Code' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_code" id="pointcoupon_code" size="48" maxlength="250" value="<?php echo @$row->pointcoupon_code; ?>" />
                        <input value="<?php echo JText::_( "Auto-generate" ); ?>" onclick="ambraDoTask('<?php echo $url; ?>', 'pointcoupon_code', document.adminForm, 'Generating');" class="modal-button" type="button" />                       
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Points' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_value" id="pointcoupon_value" size="10" maxlength="11" value="<?php echo @$row->pointcoupon_value; ?>" />
                    </td>
                </tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Enabled' ); ?>:
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'pointcoupon_enabled', '', @$row->pointcoupon_enabled ); ?>
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Expires' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->expire_date, "expire_date", "expire_date", '%Y-%m-%d %H:%M:%S' ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_uses_max" id="pointcoupon_uses_max" size="10" maxlength="11" value="<?php echo @$row->pointcoupon_uses_max; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses Per User' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_uses_per_user" id="pointcoupon_uses_per_user" size="10" maxlength="11" value="<?php echo @$row->pointcoupon_uses_per_user; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses Per User Per Day' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_uses_per_user_per_day" id="pointcoupon_uses_per_user_per_day" size="10" maxlength="11" value="<?php echo @$row->pointcoupon_uses_per_user_per_day; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Name' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointcoupon_name" id="pointcoupon_name" size="48" maxlength="250" value="<?php echo @$row->pointcoupon_name; ?>" />
                    </td>
                </tr>
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Description' ); ?>:
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'pointcoupon_description',  @$row->pointcoupon_description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="pointcoupon_params" id="pointcoupon_params" rows="10" cols="35"><?php echo @$row->pointcoupon_params; ?></textarea>
                    </td>
                </tr>
			</table>

			<input type="hidden" name="id" value="<?php echo @$row->pointcoupon_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>