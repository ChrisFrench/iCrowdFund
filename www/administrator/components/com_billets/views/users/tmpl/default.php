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

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

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
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_('COM_BILLETS_NUM'); ?>
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_ID', "tbl.id", @$state->direction, @$state->order ); ?>
                </th>                
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_NAME', "tbl.name", @$state->direction, @$state->order ); ?>
                	+
                	<?php echo DSCGrid::sort( 'COM_BILLETS_USERNAME', "tbl.username", @$state->direction, @$state->order ); ?>
                	+
                	<?php echo DSCGrid::sort( 'COM_BILLETS_EMAIL', 'tbl.email', @$state->direction, @$state->order); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_TICKET_COUNT'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_HOUR_COUNT'); ?>
                </th>
				<th style="text-align: left;">
					<?php echo JText::_('COM_BILLETS_USER_DATA'); ?>
				</th>
                <th style="width: 100px;">
                    <?php echo JText::_('COM_BILLETS_EXCLUDED_FROM_GLOBAL_TICKET_LIMITING'); ?>
                </th>
                 <th style="width: 100px;">
                    <?php echo JText::_('COM_BILLETS_EXCLUDED_FROM_GLOBAL_HOUR_LIMITING'); ?>
                </th>
				<th>
					<?php echo JText::_('COM_BILLETS_CATEGORIES'); ?>
				</th>
				<th>
				</th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
					<?php echo DSCGrid::checkedout( $item, $i, 'id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->id; ?>
					</a>
				</td>	
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->name; ?>
					</a>
					<br/>
					&nbsp;&nbsp;&bull;&nbsp;&nbsp;<?php echo $item->username; ?>
                    <br/>
                    &nbsp;&nbsp;&bull;&nbsp;&nbsp;<?php echo $item->email; ?>
				</td>	
				<td style="text-align: center;">
					<?php if ($item->ticket_count == null) { echo JText::_('COM_BILLETS_NULL'); } else { echo (int) $item->ticket_count; } ?>
				</td>
				<td style="text-align: center;">
					<?php if ($item->hour_count == null) { echo JText::_('COM_BILLETS_NULL'); } else { echo (int) $item->hour_count; } ?>
				</td>
				<td style="text-align: left;">
				    <?php echo JText::_('COM_BILLETS_TICKET_MAX') . ": " . (int) $item->ticket_max; ?><br/>
				    <?php echo JText::_('COM_BILLETS_LIMIT_NUMBER_OF_TICKETS') . ": " . DSCGrid::enable($item->limit_tickets, $i, 'limit_tickets.' ); ?><br/>
				    <?php echo JText::_('COM_BILLETS_HOUR_MAX') . ": " . (int) $item->hour_max; ?><br/>
				    <?php echo JText::_('COM_BILLETS_LIMIT_NUMBER_OF_HOURS') . ": " . DSCGrid::enable($item->limit_hours, $i, 'limit_hours.' ); ?>
				</td>
				<td style="text-align: center;">
				    <?php echo DSCGrid::enable($item->limit_tickets_exclusion, $i, 'limit_tickets_exclusion.' ); ?>
                </td>
                <td style="text-align: center;">
				    <?php echo DSCGrid::enable($item->limit_hours_exclusion, $i, 'limit_hours_exclusion.' ); ?>
                </td>
                <td style="text-align: center;">
					<?php echo JText::_('COM_BILLETS_WITH_VIEWING_RIGHTS'); ?>: <?php echo count( BilletsHelperManager::getCategories( $item->id, 'view' ) ); ?>
					<br/>
					<?php echo JText::_('COM_BILLETS_RECEIVING_EMAILS'); ?>: <?php echo count( BilletsHelperManager::getEmailCategories( $item->id ) ); ?>
					<br/>
					[<?php echo BilletsUrl::popup( @$item->link_selectcategories, JText::_("COM_BILLETS_SELECT_CATEGORIES_TO_MANAGE") ); ?>]
				</td>
				<td style="text-align: center;">
					[
					<a href="<?php echo $item->link_createticket; ?>">
						<?php echo JText::_('COM_BILLETS_CREATE_TICKET'); ?>
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
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>