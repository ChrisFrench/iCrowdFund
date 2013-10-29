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
				<td class="key"> <?php echo JText::_('Name'); ?>: </td>
				<td>
				<input name="name" value="<?php echo @$row -> name; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('user_id'); ?>: </td>
				<td>
				<input name="joomla_user_id" value="<?php echo @$row -> joomla_user_id; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('wepay_userid'); ?>: </td>
				<td>
				<input name="wepay_userid" value="<?php echo @$row -> wepay_userid; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('wepay_account_id'); ?>: </td>
				<td>
				<input name="wepay_account_id" value="<?php echo @$row -> wepay_account_id; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('wepay_account_uri'); ?>: </td>
				<td>
				<input name="wepay_account_uri" value="<?php echo @$row -> wepay_account_uri; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('wepay_access_token'); ?>: </td>
				<td>
				<input name="wepay_access_token" value="<?php echo @$row -> wepay_access_token; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('wepay_token_type'); ?>: </td>
				<td>
				<input name="wepay_token_type" value="<?php echo @$row -> wepay_token_type; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
				<tr>
				<td class="key"> <?php echo JText::_('wepay_expires_in'); ?>: </td>
				<td>
				<input name="wepay_expires_in" value="<?php echo @$row -> wepay_expires_in; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
				<tr>
				<td class="key"> <?php echo JText::_('oauth_code'); ?>: </td>
				<td>
				<input name="oauth_code" value="<?php echo @$row -> oauth_code; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
				<tr>
				<td class="key"> <?php echo JText::_('wepay_name'); ?>: </td>
				<td>
				<input name="wepay_name" value="<?php echo @$row -> wepay_name; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
				<tr>
				<td class="key"> <?php echo JText::_('wepay_description'); ?>: </td>
				<td>
				<input name="wepay_description" value="<?php echo @$row -> wepay_description; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('enabled'); ?>: </td>
				<td>
				<input name="enabled" value="<?php echo @$row -> enabled; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('datecreated'); ?>: </td>
				<td>
				<input name="datecreated" value="<?php echo @$row -> datecreated; ?>" size="50" maxlength="250" type="text" style="font-size: 20px;" />
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
