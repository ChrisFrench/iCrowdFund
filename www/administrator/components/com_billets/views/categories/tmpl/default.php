<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<script type="text/javascript">
window.addEvent('domready', function() {
	// Reload the page when the SqueezeBox closes to refresh values.
	SqueezeBox.presets.onClose = function(){ location.href = location.href; };
});
</script>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" id="adminForm" class="adminForm adminform" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
    <table>
        <tr>
            <td align="left" width="100%">
                <?php echo JText::_('COM_BILLETS_SEARCH'); ?>
                <input id="search" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_('COM_BILLETS_SEARCH'); ?></button>
                <button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_BILLETS_RESET'); ?></button>
            </td>
            <td nowrap="nowrap">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?php echo BilletsSelect::category( @$state->filter_parentid, 'filter_parentid', $attribs, 'parentid', true, true ); ?>
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_('COM_BILLETS_NUM'); ?>
                </th>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_ID', "tbl.id", @$state->direction, @$state->order ); ?>
                </th>                
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_TITLE', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'COM_BILLETS_ORDER', "tbl.lft", @$state->direction, @$state->order ); ?>
    	            <?php // echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th>
    	            <?php echo DSCGrid::sort( 'COM_BILLETS_ENABLED', "tbl.enabled", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_FIELDS'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_MANAGERS'); ?>
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
					<?php echo DSCGrid::checkedout( $item, $i, 'id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->id; ?>
					</a>
				</td>	
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php // echo JText::_($item->title);
						$repeats = $item->level-1 >= 0 ? $item->level-1 : 0;
						echo str_repeat( '.&nbsp;', $repeats ).JText::_($item->name);
						?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order($item->id); ?>
					<?php // echo DSCGrid::ordering($item->id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->enabled, $i ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo JText::_('COM_BILLETS_FIELDS_ASSIGNED'); ?>: <?php echo count( BilletsHelperCategory::getFields( $item->id ) ); ?>
					<br/>
					[<?php echo BilletsUrl::popup( @$item->link_selectfields, JText::_("COM_BILLETS_SELECT_FIELDS") ); ?>]
					
				</td>
				<td style="text-align: center;">
					<?php echo JText::_('COM_BILLETS_WITH_VIEWING_RIGHTS'); ?>: <?php echo count( BilletsHelperCategory::getViewingManagers( $item->id ) ); ?>
					<br/>
					<?php echo JText::_('COM_BILLETS_RECEIVING_EMAILS'); ?>: <?php echo count( BilletsHelperCategory::getEmailManagers( $item->id ) ); ?>
					<br/>
					[<?php echo BilletsUrl::popup( @$item->link_selectusers, JText::_("COM_BILLETS_SELECT_MANAGERS") ); ?>]
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
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>