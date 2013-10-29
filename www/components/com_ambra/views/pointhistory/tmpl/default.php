<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Points Earned" ); ?></span>
</div>

    <?php if ($menu = AmbraMenu::getInstance()) { $menu->display(); } ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

    <?php if (!empty($this->doCoupons)) : ?>
        <div id="pointhistory_couponform" class="note">
            <span><?php echo JText::_( "Submit Coupon Code Here" ); ?></span>
            <span id="pointhistory_couponform_inputs">
                <input type="text" size="20" name="pointcoupon_code" id="pointcoupon_code" ></input>
                <button onclick="this.form.task.value='submitcoupon'; this.form.submit();" class="button" ><?php echo JText::_('Submit Coupon'); ?></button>
            </span>
        </div>
	<?php endif; ?>
	
    <table>
        <tr>
            <th style="text-align: left; width: 100%;">
                <?php echo JText::_( "Apply Filters" ); ?>
            </th>
            <td style="white-space: nowrap; text-align: right; vertical-align: bottom;">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?php echo AmbraSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
                
                <input id="filter_filter" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();" class="button" ><?php echo JText::_('Search'); ?></button>
                <button onclick="ambraFormReset(this.form);" class="button" ><?php echo JText::_('Reset'); ?></button>
            </td>
        </tr>
        <tr>
            <td align="left" style="padding: 5px;">
            </td>
            <td style="white-space: nowrap; text-align: right; vertical-align: bottom; padding: 5px;">
                <span class="label"><?php echo JText::_("Date From"); ?>:</span>
                <?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", '%Y-%m-%d %H:%M:%S' ); ?>
                <span class="label"><?php echo JText::_("Date To"); ?>:</span>
                <?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", '%Y-%m-%d %H:%M:%S' ); ?>
                <span class="label"><?php echo JText::_("Date Type"); ?>:</span>
                <?php echo AmbraSelect::pointhistory_datetype( @$state->filter_datetype, 'filter_datetype', '', 'datetype' ); ?>
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_("Num"); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo DSCGrid::sort( 'Action', "tbl.pointhistory_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo DSCGrid::sort( 'Points', "tbl.points", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 175px;">
                    <?php echo DSCGrid::sort( 'Created', "tbl.created_date", @$state->direction, @$state->order ); ?>
                    +
                    <?php echo DSCGrid::sort( 'Expires', "tbl.expire_date", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Enabled', "tbl.pointhistory_enabled", @$state->direction, @$state->order ); ?>
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
				<td style="text-align: left;">
					<?php echo JText::_( $item->pointhistory_name ); ?>
				</td>
				<td style="text-align: center;">
					<h1><?php echo $item->points; ?></h1>
				</td>
				<td style="text-align: center;">
                    <?php
                    if ($item->created_date != '0000-00-00 00:00:00') {
                        echo JHTML::_('date', $item->created_date, "%a, %d %b %Y");
                        
                    }
                    echo '<br />';
                    echo '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';              
                    if ($item->expire_date == '0000-00-00 00:00:00') {
                        echo JText::_( 'No Expiration' );
                    } else { 
                        echo JHTML::_('date', $item->expire_date, "%a, %d %b %Y");
                    }                    
                    ?>
				</td>
                <td style="text-align: center;">
                    <?php echo DSCGrid::boolean($item->pointhistory_enabled ); ?>
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
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>