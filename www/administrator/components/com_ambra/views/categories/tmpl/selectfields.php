<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php $row = @$this->row; ?>

<h1 style="margin-left: 2%; margin-top: 2%;"><?php echo JText::_( "Select Fields for" ); ?>: <?php echo $row->category_name; ?></h1>

<div class="note_green" style="width: 95%; text-align: center; margin-left: auto; margin-right: auto;">
	<?php echo JText::_( "For Checked Items" ); ?>:
	<button onclick="document.getElementById('task').value='fieldselected_switch'; document.adminForm.submit();"> <?php echo JText::_( "Change Status Enabled" ); ?></button>
	<button onclick="document.getElementById('task').value='fieldrequired_switch'; document.adminForm.submit();"> <?php echo JText::_( "Change Status Required" ); ?></button>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

    <table>
        <tr>
            <td align="left" width="100%">
                <input name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                <button onclick="ambraFormReset(this.form);"><?php echo JText::_('Reset'); ?></button>
            </td>
            <td nowrap="nowrap">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_("Num"); ?>
                </th>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo AmbraGrid::sort( 'ID', "tbl.field_id", @$state->direction, @$state->order ); ?>
                </th>                
                <th style="text-align: left;">
                	<?php echo AmbraGrid::sort( 'Name', "tbl.field_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Enabled' ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo JText::_( 'Required' ); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">
					<?php echo AmbraGrid::checkedout( $item, $i, 'field_id' ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo $item->field_id; ?>
				</td>	
				<td style="text-align: left;">
					<?php echo JText::_( $item->field_name ); ?>
				</td>
				<td style="text-align: center;">
					<?php $table = JTable::getInstance('CategoryFields', 'AmbraTable'); ?>
					<?php
                    $keynames = array();
                    $keynames['category_id'] = $row->category_id;
                    $keynames['field_id'] = $item->field_id;
					?>
					<?php $table->load( $keynames ); ?>
					<?php echo AmbraGrid::enable(!empty($table->field_id), $i, 'field_selected.'); ?>
				</td>
                <td style="text-align: center;">
                    <?php echo AmbraGrid::enable(!empty($table->required), $i, 'field_required.'); ?>
                </td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('No items found'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="20">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="task" id="task" value="selectfields" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>
</div>