<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.mootools');  JHTML::_('behavior.framework', true); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php JHTML::_('stylesheet', 'billets.css', 'media/com_billets/css/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );   ?>

<div class='componentheading'>
	<span><?php echo JText::_('COM_BILLETS_MY_TICKETS'); ?></span>
</div>

<?php echo DSCMenu::getInstance('submenu')->display();  ?>
		
<form action="<?php echo JRoute::_( @$form['action']."&limitstart=".@$state->limitstart )?>" method="post" name="adminForm" enctype="multipart/form-data">
	
    <table>
        <tr>
            <th style="text-align: left; width: 100%;">
                <?php echo JText::_('COM_BILLETS_FILTER_TICKETS'); ?>
            </th>
            <td style="white-space: nowrap; text-align: right; vertical-align: bottom;">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.adminForm.limitstart.value='0'; document.adminForm.submit();" ); ?>
                <?php echo BilletsSelect::ticketstate( @$state->filter_stateid, 'filter_stateid', $attribs, 'stateid', true ); ?>
                <?php echo BilletsSelect::category( @$state->filter_categoryid, 'filter_categoryid', $attribs, 'categoryid', true, true ); ?>

                <input id="search_filter" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="document.adminForm.submit();"><?php echo JText::_('COM_BILLETS_SEARCH'); ?></button>
                <button onclick="document.adminForm.search_filter.value=''; document.adminForm.submit();"><?php echo JText::_('COM_BILLETS_RESET'); ?></button>
            </td>
        </tr>
        <tr>
            <td align="left" style="padding: 5px;">
            </td>
            <td style="white-space: nowrap; text-align: right; vertical-align: bottom; padding: 5px;">
                <span class="label"><?php echo JText::_('COM_BILLETS_DATE_FROM'); ?>:</span>
                <?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", JText::_($datelayout) ); ?>
                <span class="label"><?php echo JText::_('COM_BILLETS_DATE_TO'); ?>:</span>
                <?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", JText::_($datelayout) ); ?>
                <span class="label"><?php echo JText::_('COM_BILLETS_DATE_TYPE'); ?>:</span>
                <?php echo BilletsSelect::datetype( @$state->filter_datetype, 'filter_datetype', '', 'datetype' ); ?>
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_ID', "tbl.id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_SUBJECT', "tbl.title", @$state->direction, @$state->order ); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_CATEGORY', "tbl.categoryid", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 150px;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_LAST_MODIFIED', "tbl.last_modified_datetime", @$state->direction, @$state->order ); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_CREATED', "tbl.created_datetime", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_STATUS', "tbl.stateid", @$state->direction, @$state->order ); ?>
                </th>
				<?php foreach (@$this->fields as $field) : ?>
	                <th>
	                	<?php echo DSCGrid::sort( $field->title, "td.{$field->db_fieldname}", @$state->direction, @$state->order ); ?>	
	                </th>
                <?php endforeach; ?>
            </tr>
			<tr>
				<th colspan="20" style="font-weight: normal;">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<div style="float: left;"><?php echo @$this->pagination->getPagesLinks(); ?></div>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20">
					<div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
					<a href="<?php echo JRoute::_( $item->link ); ?>">
						<?php echo $item->id; ?>
					</a>
				</td>
				<td style="text-align: left;">
					<a href="<?php echo JRoute::_( $item->link ); ?>">
						<strong><?php echo Billets::wraplongword(htmlspecialchars($item->title),30,' '); ?></strong>
					</a>
					<br/>
					<a href="<?php echo JRoute::_( $item->link ); ?>">
						<?php echo @$item->category_title; ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo JHTML::_('date', $item->last_modified_datetime, JText::_($datelayout)); // strftime formatting ?>
					<br/>---<br/>
					<?php echo JHTML::_('date', $item->created_datetime, JText::_($datelayout)); // strftime formatting ?>
				</td>
				<td style="text-align: center;">
					
					<?php echo BilletsHelperTicketstate::getImage( @$item->stateid ) ? BilletsHelperTicketstate::getImage( @$item->stateid )."<br/>" : ""; ?>
					<?php if (!empty( $item->state_title )) { ?>
						<?php echo JText::_( @$item->state_title ); ?>
					<?php } ?>
					<br/>
					<?php echo BilletsHelperTicket::getRatingImage( @$item->feedback_rating ); ?>
				</td>
				<?php foreach (@$this->fields as $field) : ?>
	                <td style="text-align: center;">
	                	<?php $db_fieldname = $field->db_fieldname; ?>
	                	<?php $value = BilletsField::displayValue( $db_fieldname, @$item->$db_fieldname ); ?>
	                	<?php echo $value ? stripslashes( $value ) : JText::_('COM_BILLETS_NOT_PROVIDED'); ?>
	                </td>
                <?php endforeach; ?>
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
							<?php
							$fulltext = nl2br( htmlspecialchars( $item->description ) );
							$dispatcher = JDispatcher::getInstance();
							$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
							echo Billets::wraplongword($fulltext,30,' ');
							?>
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
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>