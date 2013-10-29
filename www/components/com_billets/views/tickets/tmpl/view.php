<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.mootools');  JHTML::_('behavior.framework', true); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php JHTML::_('stylesheet', 'billets.css', 'media/com_billets/css/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );   ?>

<div class='componentheading'>
	<span><?php echo JText::_('COM_BILLETS_VIEW_TICKET'); ?></span>
</div>

<?php echo BilletsHelperBase::browserNotice(); ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data">
	
	<?php
	 
	if (Billets::getInstance()->get( 'enable_frontend_editing', '0' ) == '1')
	{
	    ?>
	    <span style="float: right;">
	    <?php echo "<a href='".JRoute::_("index.php?option=com_billets&view=tickets&task=edit&id=".$row->id)."'>".JText::_('COM_BILLETS_EDIT_TICKET_PROPERTIES')."</a>"; ?>
	    </span>
	    <?php	    
	}
	?>
	
	
	<?php
	echo "<< <a href='".JRoute::_("index.php?option=com_billets&view=tickets")."'>".JText::_('COM_BILLETS_RETURN_TO_TICKET_LIST')."</a>";
	?>
		
	<table width="100%">
	<tbody>
		<tr>
			<td style="vertical-align: top; min-width: 70%; width: 70%;">
			
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
						<strong><?php echo JText::_('COM_BILLETS_SUBJECT'); ?>: </strong>
						<?php echo Billets::wraplongword(htmlspecialchars( $row->title ),30,' '); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
						$fulltext = nl2br( htmlspecialchars( $row->description ) );
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
						echo "<div class='ticket_description'>" . Billets::wraplongword($fulltext,30,' ') . "</div>";
						echo BilletsHelperTicket::displayMessageFiles( 0, $this->files );
						?>
					</td>
				</tr>
			</tbody>
			</table>
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
						<?php echo JText::_('COM_BILLETS_DISCUSSION'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
		
				if (BilletsHelperTicket::canView( $row->id, JFactory::getUser()->id )) 
				{
					?>
					<tr>
					<td colspan="2">
					
                    <?php $click_here = JText::_('COM_BILLETS_CLICK_HERE_TO_ADD_A_COMMENT_OR_AN_ATTACHMENT'); ?>
					<span class="href" id="showhideaddcomment" onclick="Dsc.displayDiv ('addcomment', 'showhideaddcomment', '<?php echo $click_here; ?>', '<?php echo $click_here; ?>');">
                        <?php echo $click_here; ?>    					
					</span>
					
	                <div id="addcomment" style="display: none">
						<?php
						$bbcode = "";
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderForm', array( 'document.adminForm.message', &$bbcode) );
						echo $bbcode;
						?>
						<textarea name="message" id="billets_message" class="text_area" rows="10" style="width: 98%;" ></textarea>
						
						<?php if (Billets::getInstance()->get('files_enable', '1')) 
						{
						?>
							<div class="note"><?php echo JText::_('COM_BILLETS_ATTACHMENT_INTRO'); ?></div>
							<p><input class="text_area" name="userfile[]" type="file" size="25" /> <a href="javascript:void(0);" onclick="Billets.addAnotherFile('<?php echo JText::_('COM_BILLETS_REMOVE');?>')"><?php echo JText::_('COM_BILLETS_ADD_MORE_FILES');?></a></p>
							<div id="more_files"></div>

						<?php 
						}
						?>
						<br/>
						<button onclick="document.adminForm.tasks.value='addcomment'; document.adminForm.submit();"><?php echo JText::_('COM_BILLETS_SUBMIT_COMMENT_AND_ATTACHMENT'); ?></button>
	                </div>
					</td>
					</tr>
					<?php
				}
				
				$messages = BilletsHelperTicket::getMessages( $row->id );
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
					  	echo " (".JHTML::_('date', $message->datetime, JText::_($datelayout))."):<br>";
					  	echo BilletsHelperTicket::displayMessageFiles( $message->id );
						echo "<div class='comment_description'>";
							$fulltext = nl2br( htmlspecialchars( $message->message ) );
							$dispatcher = JDispatcher::getInstance();
							$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
							echo $fulltext;
						echo "</div>";
                    ?>
                    </td>
                    </tr>
	                <?php
				}
				
				
                if (!empty($messages))
                {
                    ?>
                    <tr>
                    <td colspan='2'>
                    <span class="href" id="showhideaddcomment_2" onclick="Dsc.displayDiv ('addcomment', 'showhideaddcomment_2', '<?php echo $click_here; ?>', '<?php echo $click_here; ?>'); document.adminForm.billets_message.focus();">
                        <?php echo $click_here; ?>                      
                    </span>
                    </td>
                    </tr>
                    <?php
                }
				?>
				
			</tbody>
			</table>
			
			</td>
			<td style="vertical-align: top; min-width: 30%; width: 30%;">
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
						<?php echo JText::_('COM_BILLETS_TICKET_INFO'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_USER'); ?>
					</th>
					<td>
						<?php echo JFactory::getUser( @$row->sender_userid )->username; ?>
						[
						<?php echo @$row->sender_userid; ?>
						]
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_NAME'); ?>
					</th>
					<td>
						<?php echo JFactory::getUser( @$row->sender_userid )->name; ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_EMAIL'); ?>
					</th>
					<td>
						<?php echo JFactory::getUser( @$row->sender_userid )->email; ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_TICKET_ID'); ?>
					</th>
					<td>
						<?php echo @$row->id; ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_CATEGORY'); ?>
					</th>
					<td>
						<?php echo BilletsHelperCategory::getTitle( @$row->categoryid ); ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_CREATED'); ?>
					</th>
					<td>
						<?php echo JHTML::_('date', @$row->created_datetime, JText::_($datelayout)); ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_LAST_MODIFIED'); ?>
					</th>
					<td>
						<?php echo JHTML::_('date', @$row->last_modified_datetime, JText::_($datelayout)); ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_STATUS'); ?>
					</th>
					<td>
						
						<?php echo BilletsHelperTicketstate::getImage( @$row->stateid ) ? BilletsHelperTicketstate::getImage( @$row->stateid )."<br/>" : ""; ?>
						<?php if (!empty( $row->state_title )) { ?>
							<?php echo JText::_( @$row->state_title ); ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_FEEDBACK'); ?>
					</th>
					<td>
						<?php echo BilletsHelperTicket::getRatingImage( @$row->feedback_rating ); ?>
					</td>
				</tr>
			</tbody>
			</table>
			
			
            <?php if (JFactory::getUser()->id && Billets::getInstance()->get('enable_tienda', '1') && BilletsHelperTienda::isInstalled()) 
            {
            ?>
                <table class="adminlist">
                <thead>
                    <tr>
                        <th colspan="2">
                        <?php echo JText::_('COM_BILLETS_ORDER_INFO'); ?> 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo JText::_('COM_BILLETS_ORDER_ID'); ?>
                        </td>
                        <td>
                            <?php echo @$row->tienda_orderid; ?>
                        </td>
                    </tr>
                </tbody>
                </table>
                <?php 
            }
            ?>
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
						<?php echo JText::_('COM_BILLETS_ADDITIONAL_INFO'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
					<?php
						$fields = BilletsHelperCategory::getFields( @$row->categoryid );
						if (empty($fields))
						{
							echo "<tr>";
							echo "<td>";
							echo JText::_('COM_BILLETS_NONE');
							echo "</td>";
							echo "</tr>";
						}
						
						foreach (@$fields as $field)
						{
						  	echo "<tr>";
							echo "<th>".JText::_( $field->title )."</th>";
							echo "<td>";
								$name = $field->db_fieldname;
								$value = BilletsField::displayValue( $field, @$row->$name );
								echo $value ? stripslashes( wordwrap ( $value, 30, '<br />', true ) ) : JText::_('COM_BILLETS_NOT_PROVIDED');
							echo "</td>";
						  	echo "</tr>";
						}
					?>
			</tbody>
			</table>
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="3">
						<?php echo JText::_('COM_BILLETS_ATTACHMENTS'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
					<?php
					$attachments = BilletsHelperTicket::getAttachments( $row->id );
					if (empty($attachments))
					{
						echo "<tr>";
						echo "<td colspan='3'>";
						echo JText::_('COM_BILLETS_NONE');
						echo "</td>";
						echo "</tr>";
					}
					
					$obj = new BilletsFile();
					$dir = $obj->getDirectory();
					foreach ( @$attachments as $file ) 
					{
						$link_a = "index.php?option=com_billets&view=tickets&task=downloadfile&id=".@$row->id."&fileid=".$file->id;
						$link_a = JRoute::_( $link_a, false );
					    echo "<tr>";
					    echo "<td>";					
						  echo "<img src='".JURI::root()."/media/com_billets/images/attachment_16.png'>";
					    echo "</td>";					
					    echo "<td>";
                            $fileexists = JFile::exists( $dir.DS.$file->physicalname);
                            if ($fileexists || $file->fileisblob) 
							{
								echo "<a href='".$link_a."'>";
								echo $file->filename;
								echo "</a>";
							} else {
								echo $file->filename;
							}
							echo "<br/>";
							echo JHTML::_('date', @$file->datetime, JText::_($datelayout));
					    echo "</td>";
					    echo "<td>";
							echo $file->filesize;
					    echo "</td>";
					    echo "</tr>";
					}
					?>
			</tbody>
			</table>
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="3">
						<?php echo JText::_('COM_BILLETS_HOURS_SPENT'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center;">
					<?php					
						echo @$row->hours_spent;					
					?>
					</td>
				</tr>
			</tbody>
			</table>
			
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="3">
						<?php echo JText::_('COM_BILLETS_USERS_TICKETS'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: left;">
					<?php 
						//Displaying links to user's tickets						
						$counter = 0;
						foreach(@$this->ticket_list as $ticket)
						{
							echo '<a href="index.php?option=com_billets&view=tickets&task=view&id='.$ticket->id.'" target="_blank">[#'.$ticket->id.'] - '.$ticket->title.'<br/>('.JHTML::_('date', $ticket->created_datetime, JText::_($datelayout)).')</a><br/><br/>';
							$counter++;
							if($counter==Billets::getInstance()->get( 'number_of_tickets_links' ))//Setting from config (Display), how tickets we will display
								break;
						}
					?>
					</td>
				</tr>
			</tbody>
			</table>

			<?php
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onAfterDisplayTicketInfo', array( $row, JFactory::getUser() ) );					
			?>
			
			</td>
		</tr>
	</table>	
	<?php if ( @$row->stateid != Billets::getInstance()->get('state_closed'))
	{
	?>
		<table class="adminlist">
		<thead>
			<tr>
				<th style="text-align: left;">
				<?php echo JText::_('COM_BILLETS_CLOSE_TICKET'); ?> 
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
				<div class="note"><?php echo JText::_('COM_BILLETS_CLOSE_TICKET_INTRO'); ?></div>
				</td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="apply_rating" id="apply_rating" value="" />
					
					<input type='button' class='button' style="float: left; margin-right: 5px;" onclick="if (confirm('<?php echo JText::_('COM_BILLETS_CLOSE_AND_RATE_TICKET_WARNING');?>')) { document.adminForm.apply_rating.value='1'; document.adminForm.tasks.value='closeticket'; document.adminForm.submit(); }" value='<?php echo JText::_('COM_BILLETS_UNSATISFACTORY'); ?>' />
					<input type='button' class='button' style="float: left; margin-right: 5px;" onclick="if (confirm('<?php echo JText::_('COM_BILLETS_CLOSE_AND_RATE_TICKET_WARNING');?>')) { document.adminForm.apply_rating.value='2'; document.adminForm.tasks.value='closeticket'; document.adminForm.submit(); }" value='<?php echo JText::_('COM_BILLETS_POOR'); ?>' />
					<input type='button' class='button' style="float: left; margin-right: 5px;" onclick="if (confirm('<?php echo JText::_('COM_BILLETS_CLOSE_AND_RATE_TICKET_WARNING');?>')) { document.adminForm.apply_rating.value='3'; document.adminForm.tasks.value='closeticket'; document.adminForm.submit(); }" value='<?php echo JText::_('COM_BILLETS_AVERAGE'); ?>' />
					<input type='button' class='button' style="float: left; margin-right: 5px;" onclick="if (confirm('<?php echo JText::_('COM_BILLETS_CLOSE_AND_RATE_TICKET_WARNING');?>')) { document.adminForm.apply_rating.value='4'; document.adminForm.tasks.value='closeticket'; document.adminForm.submit(); }" value='<?php echo JText::_('COM_BILLETS_GOOD'); ?>' />
					<input type='button' class='button' style="float: left; margin-right: 5px;" onclick="if (confirm('<?php echo JText::_('COM_BILLETS_CLOSE_AND_RATE_TICKET_WARNING');?>')) { document.adminForm.apply_rating.value='5'; document.adminForm.tasks.value='closeticket'; document.adminForm.submit(); }" value='<?php echo JText::_('COM_BILLETS_GREAT'); ?>' />
				</td>
			</tr>
		</tbody>
		</table>
	<?php } ?>
	
		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplayTicket', array( @$row, JFactory::getUser() ) );
			?>
		</div>
	<input type="hidden" name="count_files"	id="count_files" value="1" />
	<input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
	<input type="hidden" name="task" id="tasks" value="" />
	<?php echo @$form['validate']; ?>
</form>