<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );   ?>
<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
    <table>
        <tr>
            <td align="left" width="100%">
            	<div style="padding-bottom: 3px;">
            	<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='moveticket'; document.adminForm.submit();"); ?>
				<?php echo BilletsSelect::category( '', 'apply_categoryid', $attribs, 'apply_categoryid', true, false, 'COM_BILLETS_MOVE_TICKET' ); ?>

            	<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='changestatus'; document.adminForm.submit();"); ?>
				<?php echo BilletsSelect::ticketstate( '', 'apply_stateid', $attribs, 'apply_stateid', true, false, 'COM_BILLETS_CHANGE_STATUS' ); ?>
				
				<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='addlabel'; document.adminForm.submit();"); ?>	
               	<?php echo BilletsSelect::label( '', 'apply_labelid', $attribs, 'apply_labelid', true, true, 'COM_BILLETS_APPLY_LABEL' ); ?>
                
                <?php echo BilletsUrl::popup( "index.php?option=com_billets&view=tickets&task=editlabels&tmpl=component", JText::_( "COM_BILLETS_EDIT_LABELS" ), '', '', 0, 0, '', true, false  ); ?>
                </div>
            </td>
            <td nowrap="nowrap" style="text-align: right">
                <input id="search" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_('COM_BILLETS_SEARCH_ALL'); ?></button>
                <button onclick="document.getElementById('search').value=''; this.form.submit();"><?php echo JText::_('COM_BILLETS_RESET'); ?></button>
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
                	<?php echo DSCGrid::sort( 'COM_BILLETS_SUBJECT', "tbl.title", @$state->direction, @$state->order ); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_CATEGORY', "tbl.categoryid", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_FROM', "tbl.sender_userid", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 150px;">
                	<?php echo DSCGrid::sort( 'COM_BILLETS_LAST_MODIFIED', "tbl.last_modified_datetime", @$state->direction, @$state->order ); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_CREATED', "tbl.created_datetime", @$state->direction, @$state->order ); ?>
                </th>
                <th>
					<?php echo JText::_('COM_BILLETS_REPLIES'); ?>
                </th>
                <th>
                	<?php echo DSCGrid::sort( 'COM_BILLETS_STATUS', "tbl.stateid", @$state->direction, @$state->order ); ?>
                </th>
                <th>
					<?php echo JText::_('COM_BILLETS_HOURS_SPENT'); ?>
                </th>
				<?php foreach (@$this->fields as $field) : ?>
	                <th>
	                	<?php echo DSCGrid::sort( $field->title, "td.{$field->db_fieldname}", @$state->direction, @$state->order ); ?>	
	                </th>
                <?php endforeach; ?>
            </tr>
            <tr class="filter">
                <th colspan="3">
                	<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>

                	<div class="range">
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_('COM_BILLETS_FROM'); ?>:</span> <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input" />
	                	</div>
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_('COM_BILLETS_TO'); ?>:</span> <input id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input" />
	                	</div>
                	</div>
                </th>                
                <th style="text-align: left;">
                	<input id="filter_title" name="filter_title" value="<?php echo @$state->filter_title; ?>" size="25" style="margin-bottom: 2px;" />
	                <?php echo BilletsSelect::label( @$state->filter_labelid, 'filter_labelid', $attribs, 'labelid', true, true ); ?>
	                <?php echo BilletsSelect::category( @$state->filter_categoryid, 'filter_categoryid', $attribs, 'categoryid', true, true ); ?>
                </th>
                <th style="text-align: left;">
					<input id="filter_user" name="filter_user" value="<?php echo @$state->filter_user; ?>" size="20"/>
                </th>
                <th>
                	<div class="range">
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_('COM_BILLETS_FROM'); ?>:</span>
	                		<?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", JText::_($datelayout) ); ?>
	                	</div>
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_('COM_BILLETS_TO'); ?>:</span>
	                		<?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", JText::_($datelayout) ); ?>
	                	</div>
	                	<div class="rangeline">
	                		<span class="label"><?php echo JText::_('COM_BILLETS_TYPE'); ?>:</span>
	                		<?php echo BilletsSelect::datetype( @$state->filter_datetype, 'filter_datetype', '', 'datetype' ); ?>
	                	</div>
                	</div>
                </th>
                <th colspan="3">
					<?php echo BilletsSelect::ticketstate( @$state->filter_stateid, 'filter_stateid', $attribs, 'stateid', true ); ?>
                </th>
				<?php foreach (@$this->fields as $field) : ?>
	                <th>
	                </th>
                <?php endforeach; ?>
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
					<?php echo DSCGrid::checkedout( $item, $i, 'id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->id; ?>
					</a>
				</td>
				<td>
					<?php
					$label = BilletsHelperTicket::displayLabel( $item ); 
					echo $label; 
					?>
					<p class="label" style="padding-top: 5px;">
						<a href="<?php echo $item->link; ?>">
							<?php echo Billets::wraplongword(htmlspecialchars($item->title),30,' '); ?>
						</a>
					</p>
					<p <?php if (empty($label)) { echo "style='margin-top: -5px;'"; } ?>>
						<a href="<?php echo $item->link; ?>">
							<?php echo @$item->category_title; ?>
						</a>
					</p>
				</td>
				<td style="text-align: left;">
                    <?php 
                      
                        if(BilletsHelperUser::isLoggedIn($item->sender_userid))
                        {
                            echo '<img src="'.JURI::root().'/media/com_billets/images/asterisk_orange.png" alt="'.JText::_('COM_BILLETS_ONLINE').'" title="'.JText::_('COM_BILLETS_ONLINE').'" style="float:right"/>';
                        }
                    ?>
					<?php echo $item->user_name; ?> [<?php echo @$item->user_username; ?>]					
					<br />	                
					<?php echo stripslashes(@$item->user_email); ?> [<?php echo @$item->sender_userid; ?>]
	                <?php
	                // Fire a plugin here to allow special info to be displayed, such as a list of active subscriptions                
					$dispatcher = JDispatcher::getInstance();
					$dispatcher->trigger( 'onDisplayFromColumn', array( $item, JFactory::getUser($item->sender_userid) ) );
	                ?>
				</td>
				<td style="text-align: center;">
					<?php echo JHTML::_('date', $item->last_modified_datetime,  JText::_($datelayout)); // strftime formatting ?>
					<br/>---<br/>
					<?php echo JHTML::_('date', $item->created_datetime,  JText::_($datelayout)); // strftime formatting ?>
				</td>
				<td style="text-align: center;">
					<h1><?php echo BilletsHelperTicket::getResponses( @$item->id, @$item->sender_userid ); ?></h1>
				</td>
				<td style="text-align: center;">
					
					<?php echo BilletsHelperTicketstate::getImage( @$item->stateid ) ? BilletsHelperTicketstate::getImage( @$item->stateid )."<br/>" : ""; ?>
					<?php if (!empty( $item->state_title )) { ?>
						<?php echo JText::_( @$item->state_title ); ?>
					<?php } ?>
					<br/>
					<?php echo BilletsHelperTicket::getRatingImage( @$item->feedback_rating ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo @$item->hours_spent; ?>
				</td>
				<?php foreach (@$this->fields as $field) : ?>
	                <td style="text-align: center;">
	                	<?php $db_fieldname = $field->db_fieldname; ?>
	                	<?php $value = BilletsField::displayValue( $db_fieldname, @$item->$db_fieldname ); ?>
	                	<?php echo $value ? stripslashes( $value ) : JText::_('COM_BILLETS_NOT_PROVIDED'); ?>
	                </td>
                <?php endforeach;?>
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