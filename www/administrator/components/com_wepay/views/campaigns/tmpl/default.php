<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'wepay.js', 'media/com_wepay/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php //Tienda::load( 'TiendaHelperCampaign', 'helpers.campaign' ); ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
      <table>
        <tr>
            <td align="left" width="100%">
            </td>
            <td nowrap="nowrap">
                <input type="text" name="filter" value="<?php echo @$state->filter; ?>" />
                <button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_('COM_WEPAY_SEARCH'); ?></button>
                <button class="btn btn-danger"onclick="Dsc.FormReset(this.form);"><?php echo JText::_('COM_WEPAY_RESET'); ?></button>
            </td>
        </tr>
    </table>

	<table class="table table-striped table-bordered" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_('COM_WEPAY_NUM'); ?>
                </th>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'COM_WEPAY_ID', "tbl.campaign_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 50px;">
                </th>
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'COM_WEPAY_NAME', "tbl.campaign_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'COM_WEPAY_ORDER', "tbl.lft", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'COM_WEPAY_ENABLED', "tbl.campaign_enabled", @$state->direction, @$state->order ); ?>
                </th>
            </tr>
            <tr class="filterline">
                <th colspan="3">
	                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                	<div class="range">
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('COM_WEPAY_FROM'); ?>" id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input input-tiny" />
                        </div>
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('COM_WEPAY_TO'); ?>" id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input input-tiny" />
                        </div>
                    </div>
                </th>
                <th>
                </th>
                <th style="text-align: left;">
                	<input type="text" id="filter_name" name="filter_name" value="<?php echo @$state->filter_name; ?>" size="25"/>
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
    	            <?php echo WepaySelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
                </th>
            </tr>
			<tr>
				<th colspan="20" style="font-weight: normal;">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<?php echo @$this->pagination->getPagesLinks(); ?>
				</td>
			</tr>
		</tfoot>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::checkedout( $item, $i, 'campaign_id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->campaign_id; ?>
					</a>
				</td>
                <td style="text-align: center;">
                    <?php //echo TiendaHelperCategory::getImage($item->campaign_full_image, '', JText::_($item->campaign_name) ); ?>
                </td>
				<td style="text-align: left;">
					
					<a href="<?php echo $item->link; ?>"><?php 
					echo  $item->campaign_name;
					?></a>
					
				</td>
                <td style="text-align: center;">
                  
                </td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order($item->campaign_id); ?>
					
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->campaign_enabled, $i, 'campaign_enabled.' ); ?>
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('COM_WEPAY_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>