<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_featureditems/js/' ); ?>
<?php JHTML::_( 'stylesheet', 'categories.css', 'templates/default/css/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php Featureditems::load( 'FeatureditemsHelperCategory', 'helpers.category' ); ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" enctype="multipart/form-data">
			  
	<?php echo DSCGrid::pagetooltip( JRequest::getVar( 'view' ) ); ?>
	
    <ul class="unstyled dsc-flat pad-left pull-right">
        <li>
            <input class="search-query" type="text" name="filter" value="<?php echo @$state->filter; ?>" />
        </li>
        <li>
            <button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
        </li>
        <li>
            <button class="btn btn-danger" onclick="Dsc.resetFormFilters(this.form);"><?php echo JText::_( 'Reset' ); ?></button>
        </li>
    </ul>
																
	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_( "Num" ); ?>
                </th>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'ID', "tbl.category_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'Name', "tbl.category_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_( 'grid.order', @$items ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Enabled', "tbl.category_enabled", @$state->direction, @$state->order ); ?>
                </th>
            </tr>
            <tr class="filterline">
                <th colspan="3">
	                <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();' ); ?>
	                <div class="range">
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('From'); ?>" id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input input-tiny" />
                        </div>
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('To'); ?>" id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input input-tiny" />
                        </div>
                    </div>
                	
                </th>
                <th style="text-align: left;">
                	<input type="text" id="filter_name" name="filter_name" value="<?php echo @$state->filter_name; ?>" size="25"/>
                </th>
                <th>
                </th>
                <th>
    	            <?php echo FeatureditemsSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
                </th>
            </tr>
			<tr>
				<th colspan="20" style="font-weight: normal;">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter( ); ?></div>
					<div style="float: left;"><?php echo @$this->pagination->getListFooter( ); ?></div>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter( ); ?></div>
					<?php echo @$this->pagination->getPagesLinks( ); ?>
				</td>
			</tr>
		</tfoot>
        <tbody>
		<?php $i = 0;
		$k = 0;
		?>
        <?php foreach ( @$items as $item ) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::checkedout( $item, $i, 'category_id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->category_id; ?>
					</a>
				</td>
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php echo JText::_( $item->category_name ); ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order( $item->category_id ); ?>
					<?php echo DSCGrid::ordering( $item->category_id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable( $item->category_enabled, $i, 'category_enabled.' ); ?>
				</td>
			</tr>
			<?php $i = $i + 1;
				$k = ( 1 - $k );
			?>
			<?php endforeach; ?>
			
			<?php if ( !count( @$items ) ) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_( 'No items found' ); ?>
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