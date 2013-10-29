<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_featureditems/js/' ); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			  
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
    
    <table class="dsc-clear dsc-table table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_( "Num" ); ?>
                </th>
                <th style="width: 20px;">
                   	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                    <?php echo DSCGrid::sort( 'ID', "tbl.item_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 50px;">
                </th>
                <th style="text-align: left;">
                    <?php echo DSCGrid::sort( 'Short Title', "tbl.item_short_title", @$state->direction, @$state->order ); ?> +
                    <?php echo JText::_( 'Long Title' ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo DSCGrid::sort( 'Type', "tbl.item_type", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 150px;">
                    <?php echo DSCGrid::sort( 'Label', "tbl.item_label", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 150px;">
                    <?php echo DSCGrid::sort( 'Category', "c.category_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo DSCGrid::sort( 'Publish Up', "tbl.publish_up", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo DSCGrid::sort( 'Publish Down', "tbl.publish_down", @$state->direction, @$state->order ); ?>
                </th>
                <th class="dsc-order">
    	            <?php echo DSCGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_( 'grid.order', @$items ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo DSCGrid::sort( 'Enabled', "tbl.item_enabled", @$state->direction, @$state->order ); ?>
                </th>
            </tr>
            <tr class="filterline">
            	<th>
                </th>
                <th colspan="2">
                    <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();' ); ?>
                    <div class="range">
                        <div class="rangeline">
                            <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input input-tiny" placeholder="<?php echo JText::_( "From" ); ?>" type="text" />
                        </div>
                        <div class="rangeline">
                            <input id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input input-tiny" placeholder="<?php echo JText::_( "To" ); ?>" type="text" />
                        </div>
                    </div>
                </th>
                <th>
                </th>
                <th style="text-align: left;">
                    <input id="filter_name" name="filter_name" value="<?php echo @$state->filter_name; ?>" size="25" type="text" />
                </th>
                <th>
                    <?php echo FeaturedItemsSelect::item_type( @$state->filter_type, 'filter_type', $attribs, 'filter_type', true ); ?>
                </th>
                <th>
                    <input id="filter_label" name="filter_label" value="<?php echo @$state->filter_label; ?>" size="10" type="text" />
                </th>
                <th>
                    <input id="filter_category" name="filter_category" value="<?php echo @$state->filter_category; ?>" size="10" type="text" />
                </th>
                <th colspan="2">
                	<div class="range">
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_("From"); ?>:</span>
	                		<?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", '%Y-%m-%d', array('size'=>'10') ); ?>
	                	</div>
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_("To"); ?>:</span>
	                		<?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", '%Y-%m-%d', array('size'=>'10') ); ?>
	                	</div>
                	</div>
                </th>
                <th>
                </th>
                <th>
                    <?php echo DSCSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'filter_enabled', true ); ?>
                </th>
            </tr>
			<tr>
				<th colspan="20" style="font-weight: normal;">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
				</th>
			</tr>
        </thead>
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
                   	<?php echo DSCGrid::checkedout( $item, $i, 'item_id' ); ?>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->item_id; ?>
                    </a>
                </td>
                <td style="text-align: center;">
                    <?php
					if ( !empty( $item->image_src ) )
					{
					    ?>
                        <a href="<?php echo $item->link; ?>">
                        <img src="<?php echo $item->image_src; ?>" style="max-height: 36px; max-width: 36px" />
                        </a>
                        <?php
					}
					?>
                </td>
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->short_title; ?>
                    </a>
                    <br/>
                    <?php echo $item->long_title; ?>                    
                </td>
                <td style="text-align: center;">
                    <?php echo $item->item_type; ?>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->item_label; ?>
                    </a>
                </td>
                <td style="text-align: center;">
                    <?php echo $item->category_name; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $item->publish_up; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo ($item->publish_down != '0000-00-00') ? $item->publish_down : JText::_( "Never" ); ?>
                </td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order( $item->item_id ); ?>
					<?php echo DSCGrid::ordering( $item->item_id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable( $item->item_enabled, $i, 'item_enabled.' ); ?>
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
        <tfoot>
            <tr>
                <td colspan="20">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter( ); ?></div>
                    <?php echo @$this->pagination->getPagesLinks( ); ?>
                </td>
            </tr>
        </tfoot>
        
    </table>

    <div>
        <input type="hidden" name="order_change" value="0" />
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="boxchecked" value="" />
        <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
        <input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
        <?php echo $this->form['validate']; ?>
    </div>
</form>