<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this -> form; ?>
<?php $row = @$this -> row; ?>
<?php JHTML::_('behavior.calendar');
	JHtml::_('behavior.formvalidation');

?>
 
<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" >

	<fieldset>
		<legend>
			<?php echo JText::_("BASIC INFORMATION"); ?>
		</legend>

		<table class="admintable">
			<tr>
				<td class="key"> <?php echo JText::_('ID'); ?>: </td>
				<td> <?php echo @$row -> email_id; ?> </td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('sender_id'); ?>: </td>
				<td>
				<?php echo @$row -> sender_id; ?> 
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('sender_name'); ?>: </td>
				<td>
					<input type="text" name="sender_name" id="sender_name" value="<?php echo @$row -> sender_name; ?> " />
				
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('replyto'); ?>: </td>
				<td>
						<input type="text" name="replyto" id="replyto" value="<?php echo @$row -> replyto; ?> " />
			
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('receiver_id'); ?>: </td>
				<td>
						<input type="text" name="receiver_id" id="receiver_id" value="<?php echo @$row -> receiver_id; ?> " />
				
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('receiver_name'); ?>: </td>
				<td>
				<input type="text" name="receiver_name" id="receiver_name" value="<?php echo @$row -> receiver_name; ?> " />

				</td>
			</tr>

			<tr>
				<td class="key"> <?php echo JText::_('receiver_email'); ?>: </td>
				<td>
				<input type="text" name="receiver_email" id="receiver_email" value="<?php echo @$row -> receiver_email; ?> " />
			
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('bcc'); ?>: </td>
				<td>
				<input type="text" name="bcc" id="bcc" value="<?php echo @$row -> bcc; ?> " />
			
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('cc'); ?>: </td>
				<td>
				<input type="text" name="cc" id="cc" value="<?php echo @$row -> cc; ?> " />
			
				</td>
			</tr>
			
			<tr>
				<td class="key"> <?php echo JText::_('title'); ?>: </td>
				<td>
				<input type="text" name="title" id="title" value="<?php echo @$row -> title; ?> " />
			
				</td>
			</tr>

			<tr>
				<td class="key"> <?php echo JText::_('body'); ?>: </td>
				<td>
					 <?php
    $editor = JFactory::getEditor();
/*$params = array( 'smilies'=> '0' ,
                 'style'  => '1' ,  
                 'layer'  => '0' , 
                 'table'  => '0' ,
                 'clear_entities'=>'0'
                 );*/
echo $editor->display( 'body', @$row->body, '500', '500', '20', '20', false, null, null, null, @$params );		
	
	
	?>
				
				</td>
			</tr>



			<tr>
				<td class="key"> <?php echo JText::_('Component'); ?>: </td>
				<td>
				<input type="text" name="option" id="option" value="<?php echo @$row -> option; ?> " />
			
				</td>
			</tr>

			<tr>
				<td class="key"> <?php echo JText::_('view'); ?>: </td>
				<td>
				<input type="text" name="view" id="view" value="<?php echo @$row -> view; ?> " />
			
				</td>
			</tr>
			<tr>
				<td class="key"> <?php echo JText::_('senddate'); ?>: </td>
				<td>
				<input type="text" name="senddate" id="senddate" value="<?php echo @$row -> senddate; ?> " />
			
				</td>
			</tr>
			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Sent' ); ?>:
                    </td>
                    <td>
                        <?php echo  DSCGrid::btbooleanlist('sent', '', @$row->sent, 'Sent', 'Not Sent') ; ?>
                      
                    </td>
                </tr>    
			
				<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'sentdate' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="sentdate" id="sentdate" value="<?php echo @$row -> sentdate; ?> " />
                      
                    </td>
                </tr>    
			<tr>
				<td class="key"> <?php echo JText::_('datecreated'); ?>: </td>
				<td>
				<input type="text" name="datecreated" id="datecreated" value="<?php echo @$row -> datecreated; ?> " />
			
				</td>
			</tr>

			<tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Enabled' ); ?>:
                    </td>
                    <td>
                        <?php echo  DSCGrid::btbooleanlist('enabled', '', @$row->enabled, 'Enabled', 'Disabled') ; ?>
                      
                    </td>
                </tr>    
			<tr>
				<td class="key"> <?php echo JText::_('datecreated'); ?>: </td>
				<td>
				<?php echo @$row -> datecreated ;?>
				</td>
			</tr>
			
			
		</table>
	</fieldset>
	<div>
		<input type="hidden" name="email_id" value="<?php echo $row->email_id; ?>" />
    <input type="hidden" name="option" value="com_messagebottle" />
    <input type="hidden" name="view" value="emails" />
    <input type="hidden" name="task" id="task" value="" />
    
		
	</div>
</form>
