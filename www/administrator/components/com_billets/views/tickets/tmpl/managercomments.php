<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php $row = @$this->row; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );   ?>
<h3><?php echo JText::_('COM_BILLETS_MANAGERS_COMMENTS'); ?></h3>

<form action="<?php echo JRoute::_( @$form['_action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<table class="adminlist">
	<thead>
		<tr>
			<th colspan="2">
				<?php echo JText::_('COM_BILLETS_PRIVATE_DISCUSSION'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
		<td colspan="2">
	
		<span class="href" id="showhideaddcomment" onclick="Dsc.displayDiv ('addcomment', 'showhideaddcomment', '<?php echo JText::_('COM_BILLETS_ADD_COMMENT'); ?>', '<?php echo JText::_('COM_BILLETS_ADD_COMMENT'); ?>');">
		<?php echo JText::_('COM_BILLETS_ADD_COMMENT'); ?>
		</span>
		
			<div id="addcomment" style="display: none">
			<textarea name="message" class="text_area" rows="10" style="width: 98%;" ></textarea>
			<button onclick="document.getElementById('task').value='addmanagercomment'; this.form.submit();"><?php echo JText::_('COM_BILLETS_ADD_COMMENT'); ?></button>
			</div>
		</td>
		</tr>
		<?php
		$messages = $items;
		if (empty($messages))
		{
			echo "<tr>";
			echo "<td>";
			echo JText::_('COM_BILLETS_NONE');
			echo "</td>";
			echo "</tr>";
		}
		
		foreach (@$messages as $message)
		{
			?>
	                <tr>
	                <td style="width: 50px; vertical-align: top;">
	                <?php					
						$message->authorimage = "<img src='".JURI::root()."/media/com_billets/images/comment.png'>";
						$dispatcher	= JDispatcher::getInstance();
						$dispatcher->trigger('onBeforeDisplayCommentAuthorImage', array( $message, $row, JFactory::getUser() ) );
					  	echo $message->authorimage;
	                echo "</td>";					
	                echo "<td>";
	                	$name = "";
	                	$config = Billets::getInstance();
					  	$name_display = $config->get( 'display_name', '1');
					  	if ($name_display == '3') { $name = $message->user_email; } elseif($name_display == '2') { $name = $message->user_username; } else { $name = $message->user_name; }
						echo "<strong>$name</strong>";
					  	echo " (".JHTML::_('date', $message->datetime, $datelayout)."):<br>";
						echo "<div>";
						echo nl2br( htmlspecialchars( $message->message ) );
						echo "</div>";
	                echo "</td>";
	                echo "</tr>";
				}
				?>
	</tbody>
	</table>
	
	<input type="hidden" name="task" id="task" value="selectusers" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>