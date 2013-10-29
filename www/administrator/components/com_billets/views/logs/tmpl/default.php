<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

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
            	<?php // TODO Here we can add different objects in select box (history for tickets, ticket states, users, etc. ?>
                
            	<?php // $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?php // echo BilletsSelect::category( @$state->filter_parentid, 'filter_parentid', $attribs, 'parentid', true, true ); ?>
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
                	<?php echo DSCGrid::sort( 'COM_BILLETS_ID', "tbl.log_id", @$state->direction, @$state->order ); ?>
                </th>   
                <th>
                	<?php echo JText::_('COM_BILLETS_OBJECT_TYPE'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_OBJECT_ID'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_PROPRETY_CHANGED'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_VALUE_FROM'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_VALUE_TO'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_CHANGED_BY'); ?>
                </th>
                <th>
                	<?php echo JText::_( "COM_BILLETS_DATETIME" ); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='item<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">				
						<?php echo $item->log_id; ?>
				</td>	
				<td style="text-align: center;">
						<?php echo $item->object_type; ?>						
				</td>
				<td style="text-align: center;">
						<?php echo $item->object_id; ?>						
				</td>
				<td style="text-align: center;">
						<?php echo $item->property_name; ?>						
				</td>
				<td style="text-align: center;">
						<?php echo $item->value_from; ?>
						<?php 
							if( $item->property_name == 'stateid' ) 
							{
								Billets::load( 'BilletsHelperTicketstate', 'helpers.ticketstate' );
								$type = BilletsHelperTicketstate::getType( $item->value_from );
								echo " - ( " . JText::_( $type->title )	. " )";
							}
						?>						
				</td>
				<td style="text-align: center;">
						<?php echo $item->value_to; ?>	
						<?php 
							if( $item->property_name == 'stateid' ) 
							{
								Billets::load( 'BilletsHelperTicketstate', 'helpers.ticketstate' );
								$type = BilletsHelperTicketstate::getType( $item->value_to );
								echo " - ( " . JText::_( $type->title )	. " )";
							}
						?>					
				</td>
				<td style="text-align: center;">
						<?php echo JFactory::getUser($item->user_id)->username; ?>						
				</td>
				<td style="text-align: center;">
						<?php echo $item->datetime; ?>						
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