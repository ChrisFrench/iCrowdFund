<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php JHTML::_('stylesheet', 'billets.css', 'media/com_billets/css/'); ?>
<?php $items = @$vars->items; ?>
<?php //Function for wraping long words 
	function wraplongword($str, $width=75, $break="\n") {
  		return preg_replace('#(\S{'.$width.',})#e', "chunk_split('$1', ".$width.", '".$break."')", $str);
	}
?>		
	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 50px;">
                
                </th>
                <th style="text-align: left;">
                	<?php echo JText::_('COM_BILLETS_SUBJECT'); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo JText::_('COM_BILLETS_CATEGORY'); ?>
                </th>
                <th style="width: 150px;">
                	<?php echo JText::_('LASTMODIFIED'); ?>
                	<?php echo JText::_('COM_BILLETS_AND'); ?>
                	<?php echo JText::_('COM_BILLETS_CREATED'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_STATUS'); ?>
                </th>				
            </tr>           		
		</thead>				
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
						<strong><?php echo wraplongword(htmlspecialchars($item->title),30,' '); ?></strong>
					</a>
					<br/>
					<a href="<?php echo JRoute::_( $item->link ); ?>">
						<?php echo @$item->category_title; ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo JHTML::_('date', $item->last_modified_datetime, "%a, %d %b %Y, %H:%M"); // strftime formatting ?>
					<br/>---<br/>
					<?php echo JHTML::_('date', $item->created_datetime, "%a, %d %b %Y, %H:%M"); // strftime formatting ?>
				</td>
				<td style="text-align: center;">
					<?php JLoader::import( 'com_billets.helpers.ticketstate', JPATH_ADMINISTRATOR.DS.'components' ); ?>
					<?php echo BilletsHelperTicketstate::getImage( @$item->stateid ) ? BilletsHelperTicketstate::getImage( @$item->stateid )."<br/>" : ""; ?>
					<?php if (!empty( $item->state_title )) { ?>
						<?php echo JText::_( @$item->state_title ); ?>
					<?php } ?>
					<br/>
					<?php echo BilletsHelperTicket::getRatingImage( @$item->feedback_rating ); ?>
				</td>				
			</tr>
				<?php
	            if (isset($item->description) && strlen($item->description) > 1) 
				{
					$text_display = "[ + ]";
					$text_hide = "[ - ]";
					$onclick = "displayDiv(\"description_{$item->id}\", \"showhidedescription_{$item->id}\", \"{$text_display}\", \"{$text_hide}\");";
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
							echo wraplongword($fulltext,30,' ');
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
	
	<div style="float:left;">
	<a href="<?php echo JRoute::_("index.php?option=com_billets&view=tickets"); ?>"><?php echo JText::_('VIEWALLTICKETS');?></a>
	</div>