<?php 
/**
 * Inspiration for Feedback dropdown from:
 * @author
 * Name: Sigrid & Radek Suski, Sigsiu.NET
 * Email: sobi@sigsiu.net
 * Url: http://www.sigsiu.net 
 */
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC1' );   ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

	<?php 
	if (Billets::getInstance()->get('locking_enabled')) 
	{ 
    	echo DSCGrid::checkoutnotice( @$row, $row->title, "view" ); 
	}
	?>
	
	<?php
	if (!Billets::getInstance()->get('locking_enabled') || JFactory::getUser()->id == @$row->checked_out)
	{
	?>
    <table>
        <tr>
            <td align="left" width="100%">
            	<input type="hidden" name="cid[]" value="<?php echo @$row->id?>" />
            	<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='moveticket'; document.adminForm.submit();"); ?>
				<?php echo BilletsSelect::category( '', 'apply_categoryid', $attribs, 'apply_categoryid', true, false, 'COM_BILLETS_MOVE_TICKET' ); ?>

            	<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='changestatus'; document.adminForm.submit();"); ?>
				<?php echo BilletsSelect::ticketstate( '', 'apply_stateid', $attribs, 'apply_stateid', true, false, 'COM_BILLETS_CHANGE_STATUS' ); ?>
            	
				<?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='addlabel'; document.adminForm.submit();"); ?>	
               	<?php echo BilletsSelect::label( '', 'apply_labelid', $attribs, 'apply_labelid', true, true, 'COM_BILLETS_APPLY_LABEL' ); ?>
              
                <?php echo BilletsUrl::popup( "index.php?option=com_billets&view=tickets&task=editlabels&tmpl=component", JText::_( "COM_BILLETS_EDIT_LABELS" ) ); ?>
            </td>
        </tr>
    </table>
	<?php
	}
	?>

	<table style="width: 100%;">
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
				if (!Billets::getInstance()->get('locking_enabled') || JFactory::getUser()->id == @$row->checked_out)
				{
					?>
					<tr>
					<td colspan="2">

					<span class="href" id="showhideaddcomment" onclick="Dsc.displayDiv ('addcomment', 'showhideaddcomment', '<?php echo JText::_('COM_BILLETS_ADD_COMMENT_OR_ATTACHMENT'); ?>', '<?php echo JText::_('COM_BILLETS_ADD_COMMENT_OR_ATTACHMENT'); ?>');">
					<?php echo JText::_('COM_BILLETS_ADD_COMMENT_OR_ATTACHMENT'); ?>
					</span>
					
	                <div id="addcomment" style="display: none">
						<?php
						$bbcode = "";
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderForm', array( 'document.adminForm.message', &$bbcode) );
						echo $bbcode;
						?>
						<div style="padding-bottom: 2px;"> 
						<textarea id="billets_message" name="message" class="text_area" rows="10" style="width: 98%;" ></textarea>
						</div>
						<div style="padding-bottom: 2px;">
						<?php echo JText::_('COM_BILLETS_ATTACH_FILE'); ?>:
              			<p><input class="text_area" name="userfile[]" type="file" size="25" /> <a href="javascript:void(0);" onclick="Billets.addAnotherFile('<?php echo JText::_('COM_BILLETS_REMOVE');?>')"><?php echo JText::_('COM_BILLETS_ADD_MORE_FILES');?></a></p>
						<div id="more_files"></div>
              			</div>
						<?php
						JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
						$model = JModel::getInstance('Frequents', 'BilletsModel');
						$model->setState('order', 'title');
						$model->setState('direction', 'ASC');
						$frequents = $model->getList();
						$sender = JFactory::getUser( $row->sender_userid );
						$admin = JFactory::getUser();
						echo '<div id="BilletsFrequent_" style="display: none;"></div>';
						for ($i=0; $i<count($frequents); $i++)
						{
							$frequent = $frequents[$i];
							$id = "BilletsFrequent_".$frequent->id;
								
							$frequent->description = str_replace(
										array( '%USER%', '%MANAGER%' ),
										array( $sender->get( 'name' ), $admin->get( 'name' ) ),
							$frequent->description );
							echo '<div id="'.$id.'" style="display: none;">'.( $frequent->description ).'</div>';
						}
						?>
              			<div style="padding-bottom: 2px;">
              			<?php echo JText::_('COM_BILLETS_APPEND_FREQUENTLY_USED_TEXT'); ?>:
              			<?php $attribs = 'onchange="appendTo( \'BilletsFrequent_\' + this.options[ this.selectedIndex ].value, \'billets_message\' );" style="width: 200px;"'; ?>
              			<?php echo BilletsSelect::frequent( '', 'apply_frequentid', $attribs, 'apply_frequentid', true, 'COM_BILLETS_SELECT_TEXT' ); ?>
              			</div>
              			<div style="padding-bottom: 2px;">
	              			<?php echo JText::_('COM_BILLETS_ADD_HOURS_SPENT'); ?>:
	              			<input type="text" name="hours_spent" id="hours_spent" value="" />
              			</div>
						<button onclick="document.getElementById('task').value='addcomment'; this.form.submit();"><?php echo JText::_('COM_BILLETS_ADD_COMMENT_AND_ATTACHMENT'); ?></button>
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
					  	echo " (".JHTML::_('date', $message->datetime, $datelayout)."):<br>";
                        echo BilletsHelperTicket::displayMessageFiles( $message->id );
						echo "<div>";
							$fulltext = nl2br( htmlspecialchars( $message->message ) );
							$dispatcher = JDispatcher::getInstance();
							$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
							echo $fulltext;
						echo "</div>";
	                echo "</td>";
	                echo "</tr>";
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
                        <?php 
                       
                            if(BilletsHelperUser::isLoggedIn($row->sender_userid))
                            {
                                echo '<img src="'.JURI::root().'/media/com_billets/images/asterisk_orange.png" alt="'.JText::_('COM_BILLETS_ONLINE').'" title="'.JText::_('COM_BILLETS_ONLINE').'" style="float:right"/>';
                            }
                        ?>
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
						<?php echo JText::_('COM_BILLETS_HOURS_SPENT'); ?>
					</th>
					<td>
						<?php echo @$row->hours_spent; ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_CREATED'); ?>
					</th>
					<td>
						<?php echo JHTML::_('date', @$row->created_datetime, $datelayout); ?>
					</td>
				</tr>
				<tr>
					<th>
						<?php echo JText::_('COM_BILLETS_LAST_MODIFIED'); ?>
					</th>
					<td>
						<?php echo JHTML::_('date', @$row->last_modified_datetime, $datelayout); ?>
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
					<th>
						<?php echo JText::_('COM_BILLETS_MANAGER_COMMENTS'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
					<?php $comments = BilletsHelperTicket::getComments( $row->id ); ?>
					<?php if (empty($comments)) {
						echo JText::_('COM_BILLETS_NO_MANAGER_COMMENTS_ON_THIS_TICKET');
					} 
						else 
					{
						echo '<div class="note_pink" style="width: 95%; text-align: center; margin-left: auto; margin-right: auto;">';
						echo BilletsUrl::popup( "index.php?option=com_billets&view=tickets&task=managercomments&tmpl=component&id=".$row->id, JText::_("COM_BILLETS_VIEW_COMMENTS") );
						echo "<br/>".JText::_('COM_BILLETS_TOTAL_COMMENTS').": ".count($comments);
						echo '</div>';	
					}
					?>
					</td>
				</tr>
				<tr>
					<td style="text-align: left;">					
						<span style="float: right;">
							[<?php echo BilletsUrl::popup( "index.php?option=com_billets&view=tickets&task=selectusers&tmpl=component&id=".$row->id, JText::_("COM_BILLETS_ADD_MANAGERS") ); ?>]
						</span>
						<span style="float: left;">
							[<?php echo BilletsUrl::popup( "index.php?option=com_billets&view=tickets&task=managercomments&tmpl=component&id=".$row->id, JText::_("COM_BILLETS_ADD_COMMENT") ); ?>]
						</span>
					</td>
				</tr>
			</tbody>
			</table>
			
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
								echo $value ? stripslashes( $value ) : JText::_('COM_BILLETS_NOT_PROVIDED');
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
							echo JHTML::_('date', @$file->datetime, $datelayout);
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
					<th colspan="2">
						<?php echo JText::_('COM_BILLETS_USERS_TICKETS'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
					<?php
						//Displaying links to user's tickets						
						$counter = 0;
						foreach(@$this->ticket_list as $ticket)
						{
							echo "<tr>";							
							echo "<td>";
							echo '<a href="index.php?option=com_billets&view=tickets&view=tickets&task=view&id='.$ticket->id.'" target="_blank">[#'.$ticket->id.'] - '.$ticket->title.' ('.JHTML::_('date', $ticket->created_datetime, $datelayout).')</a>';
							$counter++;
							if($counter==Billets::getInstance()->get( 'number_of_tickets_links' ))//Setting from config (Display), how tickets we will display
								break;
							echo "</td>";
						  	echo "</tr>";
						}
					?>
			</tbody>
			</table>
			
			<?php
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onAfterDisplayTicketInfo', array( $row, JFactory::getUser() ) );					
			?>
			
			</td>
		</tr>
	</table>

		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplayTicket', array( @$row, JFactory::getUser() ) );
			?>
		</div>
	<?php

		$surrounding = BilletsHelperTicket::getSurrounding( @$row->id );
	?>
	<input type="hidden" name="count_files"	id="count_files" value="1" />
	<input type="hidden" name="prev" value="<?php echo intval($surrounding["prev"]); ?>" />
	<input type="hidden" name="next" value="<?php echo intval($surrounding["next"]); ?>" />        
	<input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
	<input type="hidden" name="task" id="task" value="" />
</form>