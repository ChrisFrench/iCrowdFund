<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

	<fieldset>
		<legend><?php echo JText::_('Form'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<input name="field_name" id="field_name" value="<?php echo @$row->field_name; ?>" size="25" maxlength="250" type="text" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Enabled' ); ?>:
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'field_enabled', '', @$row->field_enabled ) ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Type' ); ?>:
					</td>
					<td>
						<?php echo AmbraSelect::fieldtype( @$row->type_id, 'type_id' ); ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Database Field Name' ); ?>:
					</td>
					<td>
						<?php echo @$row->db_fieldname; ?>
					</td>
					<td>
						<?php echo JText::_("Custom Field Name"); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="default">
						<?php echo JText::_( 'Default Value' ); ?>:
						</label>
					</td>
					<td>
						<textarea name="default" class="text_area" cols="30" rows="4"><?php echo @$row->default; ?></textarea>
					</td>
					<td>
						<?php echo JText::_( "Default Value Desc" ); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="options">
						<?php echo JText::_( 'Options' ); ?>:
						</label>
					</td>
					<td>
						<textarea name="options" class="text_area" cols="30" rows="7"><?php echo @$row->options; ?></textarea>
					</td>
					<td>
						<?php echo JText::_("Options Desc"); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="size">
						<?php echo JText::_( 'Size' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="size" id="size" size="10" maxlength="250" value="<?php echo @$row->size; ?>" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="maxlength">
						<?php echo JText::_( 'Maximum Length' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="maxlength" id="maxlength" size="10" maxlength="250" value="<?php echo @$row->maxlength; ?>" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="cols">
						<?php echo JText::_( 'Columns' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="cols" id="cols" size="10" maxlength="250" value="<?php echo @$row->cols; ?>" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="rows">
						<?php echo JText::_( 'Rows' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="rows" id="rows" size="10" maxlength="250" value="<?php echo @$row->rows; ?>" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="class">
						<?php echo JText::_( 'Class' ); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="class" id="class" size="25" maxlength="250" value="<?php echo @$row->class; ?>" />
					</td>
					<td>
						<?php echo JText::_("Class Desc"); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="integer">
						<?php echo JText::_( 'Force Value to be Saved as a Number' ); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'integer', 'class="inputbox"', @$row->integer ); ?>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="readonly">
						<?php echo JText::_( 'Read Only' ); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'readonly', 'class="inputbox"', @$row->readonly ); ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Optional Parameters' ); ?>:
					</td>
					<td>
						<textarea name="field_params" class="text_area" cols="25" rows="4"><?php echo @$row->field_params; ?></textarea>
					</td>
					<td></td>
				</tr>			
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_( 'Description' ); ?>:
					</td>
					<td>
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'field_description',  @$row->field_description, '100%', '450', '100', '20' ) ; ?>
					</td>
					<td></td>
				</tr>
			</table>
			
			<input type="hidden" name="id" value="<?=@$row->field_id; ?>" />
			<input type="hidden" name="task" value="" />
	</fieldset>
</form>