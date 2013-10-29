<?php defined('_JEXEC') or die('Restricted access'); ?>
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
                <?php echo BilletsSelect::fieldtype( @$state->filter_typeid, 'filter_typeid', $attribs, 'typeid', true, false, 'COM_BILLETS_SELECT_TYPE' ); ?>
                <?php echo BilletsSelect::category( @$state->filter_categoryid, 'filter_categoryid', $attribs, 'categoryid', true, true, 'COM_BILLETS_SELECT_CATEGORY', 'COM_BILLETS_NOT_ASSIGNED' ); ?>
                <?php echo BilletsSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true, 'COM_BILLETS_ENABLED_STATE' ); ?>
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
                <th>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_TITLE', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_TYPE', "tbl.typeid", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'COM_BILLETS_ORDER', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th>
    	            <?php echo DSCGrid::sort( 'COM_BILLETS_ENABLED', "tbl.published", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_CATEGORIES'); ?>
                </th>
                <th>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_DISPLAY_IN_TICKET_LIST', "tbl.listdisplayed", @$state->direction, @$state->order ); ?>
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
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo JText::_( $item->title ); ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo BilletsField::getFieldTypeTitle($item->typeid);  ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order($item->id); ?>
					<?php echo DSCGrid::ordering($item->id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->published, $i, 'published.' ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo JText::_('COM_BILLETS_CATEGORIES_ASSIGNED'); ?>: <?php echo count( BilletsField::getCategories( $item->id ) ); ?>
					<br/>
					[<?php echo BilletsUrl::popup( @$item->link_selectcategories, JText::_("COM_BILLETS_SELECT_CATEGORIES") ); ?>]
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->listdisplayed, $i, 'listdisplayed.' ); ?>
				</td>
			</tr>
				<?php
	            if (isset($item->description) && strlen($item->description) > 1) 
				{
					$text_display = "[ + ]";
					$text_hide = "[ - ]";
					$onclick = "Dsc.displayDiv(\"description_{$item->id}\", \"showhidedescription_{$item->id}\", \"{$text_display}\", \"{$text_hide}\");";
					?>
			        <tr class='row<?php echo $k; ?>'>
		            	<td style="vertical-align: top; white-space:nowrap;">
							<span class='href' id='showhidedescription_<?php echo $item->id; ?>' onclick='<?php echo $onclick; ?>'><?php echo $text_display; ?></span>
		            	</td>
		            	<td colspan='10'> 
							<div id='description_<?php echo $item->id; ?>' style='display: none;'>
							<?php echo nl2br( strip_tags( stripslashes( $item->description ) ) ); ?>
							</div>
		            	</td>
			        </tr>
			    	<?php
				}
				?>
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