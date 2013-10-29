<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php $messages = @$this->messages; ?>
<?php $files = @$this->files; ?>

<?php 
	
    echo DSCGrid::pagetooltip( "merge_tickets" );
?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">
	
	<?php echo JText::_('COM_BILLETS_MERGE_THIS_TICKET_WITH'); ?><input type="text" name="ticket_ids" id="ticket_ids" value="" />
	
	<table class="adminlist">
			<thead>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_SUBJECT'); ?>
						+
						<?php echo JText::_('COM_BILLETS_DESCRIPTION'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div style="float: right;">
						<?php echo BilletsHelperTicket::displayLabel( $row ); ?>
						</div>
						<h3><?php echo Billets::wraplongword(htmlspecialchars( $row->title ),30,' '); ?></h3>
					</td>
				</tr>
				<tr>
					<td>
						<?php
						$fulltext = nl2br( htmlspecialchars( $row->description ) );
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
						echo Billets::wraplongword($fulltext,30,' ');
						echo BilletsHelperTicket::displayMessageFiles( 0, $this->files );
						?>									
					</td>
				</tr>
			</tbody>
	</table>
	
	
	<table class="adminlist" style="clear: both;">
		<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_('COM_BILLETS_DISCUSSION'); ?>
					</th>
				</tr>
		</thead>
		<tbody>
		<?php if ( empty( $messages ) ) : ?>
	  			<tr>
					<td>
					<?php echo JText::_('COM_BILLETS_NONE'); ?>
					</td>
				</tr>
		<?php else: ?>		
			<?php foreach (@$messages as $message) : ?>
				<tr>
	                <td style="width: 50px; vertical-align: top;">
	                <?php								
						$dispatcher	= JDispatcher::getInstance();
						$dispatcher->trigger('onBeforeDisplayCommentAuthorImage', array( $message, $row, JFactory::getUser() ) );
					  	echo $message->authorimage;
					?>
	                </td>					
	                <td>
	                	<strong><?php echo $message->name; ?></strong> 
					  	<?php echo " (".JHTML::_('date', $message->datetime, "%a, %d %b %Y, %I:%M%p")."):"; ?><br/>
                        <?php echo BilletsHelperTicket::displayMessageFiles( $message->id ); ?>
						<div>
							<?php 
								$fulltext = nl2br( htmlspecialchars( $message->message ) );
								$dispatcher = JDispatcher::getInstance();
								$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
								echo $fulltext;
							?>
						</div>						
	                </td>
	            </tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
<?php //JError::raiseWarning(21, JText::_('Variables: '.Billets::dump($row))); ?>
	<input type="hidden" name="task" id="task" value="" />
	
	<?php echo $this->form['validate']; ?>
</form>