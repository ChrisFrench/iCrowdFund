<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'core.js', 'media/system/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php $row = @$this->row; ?>


<h3><?php echo JText::_('COM_BILLETS_SELECT_CATEGORIES_FOR'); ?>: <?php echo JText::_( $row->title ); ?></h3>
	
<div class="note" style="width: 95%; text-align: center; margin-left: auto; margin-right: auto;">
	<?php echo JText::_('COM_BILLETS_FOR_CHECKED_ITEMS'); ?>:
	<button onclick="document.getElementById('task').value='required_switch'; document.adminForm.submit();"> <?php echo JText::_('COM_BILLETS_CHANGE_REQUIRED'); ?></button>
	<button onclick="document.getElementById('task').value='selected_switch'; document.adminForm.submit();"> <?php echo JText::_('COM_BILLETS_CHANGE_SELECTED'); ?></button>
</div>
    
<form action="<?php echo JRoute::_( @$form['_action'] )?>" method="post" name="adminForm" id="adminForm" class="adminForm adminform" enctype="multipart/form-data">

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
    	            <?php echo DSCGrid::sort( 'COM_BILLETS_ORDER', "tbl.ordering", @$state->direction, @$state->order ); ?>
                </th>
                <th>
	                <?php echo JText::_('COM_BILLETS_REQUIRED'); ?>
                </th>
                <th>
	                <?php echo JText::_('COM_BILLETS_SELECTED'); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
        	<?php $already = BilletsField::isCategory( $row->id, $item->id, '1' ); ?>
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
						<?php echo BilletsHelperCategory::getTitle( $item->id, 'flat' ); ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo $item->ordering; ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable(!empty($already->required), $i, 'required_'); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable(isset($already->fieldid), $i, 'selected_'); ?>
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

	<input type="hidden" name="task" id="task" value="selectcategories" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>