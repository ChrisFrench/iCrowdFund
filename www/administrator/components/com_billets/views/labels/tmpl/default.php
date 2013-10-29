<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php JHTML::_('script', 'core.js', 'media/system/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<button style="float: left;" onclick="document.adminForm.toggle.checked=true; checkAll(<?php echo count( @$items ); ?>); document.getElementById('task').value='savelabels'; document.adminForm.submit();"><?php echo JText::_('COM_BILLETS_SAVE_LABELS'); ?></button>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
    <table>
        <tr>
            <td align="left" width="100%">
            </td>
            <td nowrap="nowrap">
                <input id="createlabel_title" name="createlabel_title" value="" />
                <button onclick="document.getElementById('task').value='createlabel'; document.adminForm.submit();"><?php echo JText::_('COM_BILLETS_CREATE_LABEL'); ?></button>
            </td>
        </tr>
    </table>
    
	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="text-align: left; width: 50%;">
                	<?php echo JText::_('COM_BILLETS_TITLE'); ?>
                </th>
                <th style="text-align: left;">
                	<?php echo JText::_('COM_BILLETS_COLOR'); ?>
                </th>
				<th>
				</th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
					<?php echo DSCGrid::checkedout( $item, $i, 'id' ); ?>
				</td>
				<td style="text-align: left;">
					<input type="text" name="title[<?php echo $item->id; ?>]" value="<?php echo $item->title; ?>" />
				</td>
				<td style="text-align: left;">
					<?php echo BilletsSelect::color( $item->color, "color[$item->id]", '', '', true, false, 'COM_BILLETS_DEFAULT_COLOR' ); ?>
				</td>
				<td style="text-align: center;">
					[<a href="index.php?option=com_billets&view=tickets&task=deletelabel&cid[]=<?php echo $item->id; ?>">
						<?php echo JText::_('COM_BILLETS_DELETE_LABEL'); ?>	
					</a>
					]
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('COM_BILLETS_NO_ITEMS_FOUND'); ?>
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

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" id="task" value="editlabels" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>