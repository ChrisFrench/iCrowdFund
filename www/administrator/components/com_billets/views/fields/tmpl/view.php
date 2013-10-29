<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

	<?php echo DSCGrid::checkoutnotice( @$row ); ?>
	
	<fieldset>
		<legend><?php echo JText::_('COM_BILLETS_FORM'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="title">
						<?php echo JText::_('COM_BILLETS_TITLE'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="title" id="title" size="25" maxlength="250" value="<?php echo @$row->title; ?>" />
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="enabled">
						<?php echo JText::_('COM_BILLETS_ENABLED'); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'enabled', '', @$row->enabled ) ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="typeid">
						<?php echo JText::_('COM_BILLETS_TYPE'); ?>:
						</label>
					</td>
					<td>
						<?php echo BilletsSelect::fieldtype( @$row->typeid, 'typeid' ); ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="db_fieldname">
						<?php echo JText::_('COM_BILLETS_DATABASE_FIELD_NAME'); ?>:
						</label>
					</td>
					<td>
						<?php echo @$row->db_fieldname; ?>
					</td>
					<td>
						<?php echo JText::_('COM_BILLETS_DATABASE_FIELD_NAME_DESC'); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="default">
						<?php echo JText::_('COM_BILLETS_DEFAULT_VALUE'); ?>:
						</label>
					</td>
					<td>
						<textarea name="default" class="text_area" cols="30" rows="4"><?php echo @$row->default; ?></textarea>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="options">
						<?php echo JText::_('COM_BILLETS_OPTIONS'); ?>:
						</label>
					</td>
					<td>
						<textarea name="options" class="text_area" cols="30" rows="7"><?php echo @$row->options; ?></textarea>
					</td>
					<td>
						<?php echo JText::_('COM_BILLETS_OPTIONS_DESC'); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="size">
						<?php echo JText::_('COM_BILLETS_SIZE'); ?>:
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
						<?php echo JText::_('COM_BILLETS_MAXIMUM_LENGTH'); ?>:
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
						<?php echo JText::_('COM_BILLETS_COLUMNS'); ?>:
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
						<?php echo JText::_('COM_BILLETS_ROWS'); ?>:
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
						<?php echo JText::_('COM_BILLETS_CLASS'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="class" id="class" size="25" maxlength="250" value="<?php echo @$row->class; ?>" />
					</td>
					<td>
						<?php echo JText::_('COM_BILLETS_CLASS_DESC'); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="integer">
						<?php echo JText::_('COM_BILLETS_FORCE_VALUE_TO_BE_SAVED_AS_A_NUMBER'); ?>:
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
						<?php echo JText::_('COM_BILLETS_READ_ONLY'); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'readonly', 'class="inputbox"', @$row->readonly ); ?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="params">
						<?php echo JText::_('COM_BILLETS_OPTIONAL_PARAMETERS'); ?>:
						</label>
					</td>
					<td>
						<textarea name="params" class="text_area" cols="25" rows="4"><?php echo @$row->params; ?></textarea>
					</td>
					<td></td>
				</tr>			
				<tr>
					<td width="100" align="right" class="key">
						<label for="description">
						<?php echo JText::_('COM_BILLETS_DESCRIPTION'); ?>:
						</label>
					</td>
					<td colspan="2">
						<?php $editor = JFactory::getEditor(); ?>
						<?php echo $editor->display( 'description',  @$row->description, '100%', '450', '100', '20' ) ; ?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->id?>" />
			<input type="hidden" name="task" id="task" value="" />
	</fieldset>
</form>