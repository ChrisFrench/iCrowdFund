<?php 
defined('_JEXEC') or die('Restricted access');
JHTML::_('script', 'billets.js', 'media/com_billets/js/');
JHTML::_('stylesheet', 'billets.css', 'media/com_billets/css/');
$form = @$this->form;
$row = @$this->row;
?>
<script>
function onCloseModal() {
    doTask( 'index.php?option=com_billets&view=manage&format=raw&task=updateTiendaOrders', 'tienda_integration', document.adminForm );
}
</script>

<div class='componentheading'>
	<span><?php echo JText::_('COM_BILLETS_CREATE_TICKET'); ?></span>
</div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" id="adminForm" name="adminForm" enctype="multipart/form-data" onsubmit="formValidation( '<?php echo @$form['validation']; ?>', 'validationmessage', document.adminForm.task.value, document.adminForm )">

<?php
	if (!empty($row->id))
	{
		echo DSCGrid::checkoutnotice( @$row, "COM_BILLETS_TICKET", "view" );
		echo "<< <a href='".JRoute::_("index.php?option=com_billets&view=manage&task=view&id=".$row->id)."'>".JText::_('COM_BILLETS_CANCEL_AND_RETURN_TO_TICKET')."</a>";		
	} 
	else
	{
		echo "<< <a href='".JRoute::_("index.php?option=com_billets&view=manage")."'>".JText::_('COM_BILLETS_CANCEL_AND_RETURN_TO_TICKET_LIST')."</a>";	
	}
?>

		<div id="validationmessage"></div>
	
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2" style="text-align: left;"><?php echo JText::_('COM_BILLETS_TICKET_DETAILS'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100" align="right" class="key">
						<label for="sender_userid">
						<?php echo JText::_('COM_BILLETS_USER'); ?>:
						</label>
					</td>
					<td>
                        <?php echo $this->elementUser; ?>
						<?php echo $this->resetUser; ?>				
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="user_email">	
						<?php echo JText::_('COM_BILLETS_EMAIL_ADDRESS'); ?>:
						</label>
					</td>
					<td>						
						<input id="user_email" name="user_email" type="text" class="text_area" size='20' />
					</td>
				</tr>											
				<tr>
					<td width="100" align="right" class="key">
						<label for="categoryid">
						<?php echo JText::_('COM_BILLETS_CATEGORY'); ?>:
						</label>
					</td>
					<td>
						<?php $id = @$row->id; ?>
						<?php $url = "index.php?option=com_billets&format=raw&view=tickets&task=getFields&ticketid={$id}&categoryid="; ?>
						<?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => "doTask( '$url'+document.adminForm.categoryid.options[document.adminForm.categoryid.selectedIndex].value, 'additional_info_wrapper', document.adminForm );" ); ?>
						<?php echo BilletsSelect::category( @$row->categoryid, 'categoryid', $attribs, 'categoryid', true ); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="title">
						<?php echo JText::_('COM_BILLETS_SUBJECT'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="title" id="title" size="48" maxlength="250" value="<?php echo htmlspecialchars(@$row->title); ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="description">
						<?php echo JText::_('COM_BILLETS_DESCRIPTION'); ?>:
						</label>
					</td>
					<td>
						<?php
						$bbcode = "";
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderForm', array( 'document.adminForm.description', &$bbcode) );
						echo $bbcode;
						?>
						<textarea id="description" name="description" rows="10" style="width:98%" onkeydown="Billets.tabHandleKeyDown(event);"><?php echo htmlspecialchars(@$row->description); ?></textarea>						
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="title">
						<?php echo JText::_('COM_BILLETS_HOURS_SPENT'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="hours_spent" id="hours_spent" size="48" maxlength="250" value="<?php echo @$row->hours_spent; ?>" />
					</td>
				</tr>
			</tbody>
			</table>
			
            <div id="tienda_integration">
        
                <?php if (!empty($row->sender_userid) && Billets::getInstance()->get('enable_tienda', '1') && BilletsHelperTienda::isInstalled()) 
                {
                ?>
                <table class="admintable">
                    <tr>
                        <td width="100" align="right" class="key">
                            <?php echo JText::_('COM_BILLETS_ORDER_ID'); ?>:
                        </td>
                        <td>
                            <?php Tienda::load("TiendaSelect", 'library.select'); ?>
                            <?php echo TiendaSelect::order( $row->sender_userid, @$row->tienda_orderid, 'tienda_orderid' ); ?>
                        </td>
                    </tr>
                </table>
                <?php
                }
                ?>
            </div>
			
			<div id='additional_info_wrapper'>
				<?php 
				$fields = BilletsHelperCategory::getFields( @$row->categoryid );
				if (@$fields)
				{
					?>
					<table class="admintable">
					<?php	
				}
				
				foreach (@$fields as $field)
				{
					?>
					<tr>
						<td width="100" align="right" class="key">
							<label for="<?php echo $field->db_fieldname; ?>">
							<?php echo JText::_( $field->title ); ?>:
							</label>
						</td>
						<td>
                        <?php
                        	$name = $field->db_fieldname;
                        	// field could be an associative array or just string
                        	$hField	= BilletsField::display( $field, 'ticketdata', @$row->$name );
                        	if ( !is_array( $hField ) ) {
                        		echo $hField;
                        	} else {
                        		echo $hField['msg'];
                        		if ( isset ( $hField['script'] ) && is_array( $hField ) && ( !empty( $hField['script'] ) ) ) {
                        			// As this is not an Ajax call, we need script to be embeded in html
                        			// for that purpose, we will need to embed it within <script> tags
                        			echo '<script language="javascript">' . $hField['script'] . '</script>';
                        		}
                        	}
                        
                        ?>
						</td>
					</tr>
					<?php
				}
				
				if (@$fields)
				{
					?>
					</table>
					<?php
				}
				?>
			</div>
			
	        <?php if (Billets::getInstance()->get('files_enable', '1')) 
	        {
	        ?>
				<table class="adminlist">
				<thead>
					<tr>
						<th style="text-align: left;">
						<?php echo JText::_('COM_BILLETS_ATTACHMENTS'); ?> 
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
						<div class="note"><?php echo JText::_('COM_BILLETS_ATTACHMENT_INTRO'); ?></div>
						<p><input class="text_area" name="userfile[]" type="file" size="25" /> <a href="javascript:void(0);" onclick="Billets.addAnotherFile('<?php echo JText::_('COM_BILLETS_REMOVE');?>')"><?php echo JText::_('COM_BILLETS_ADD_MORE_FILES');?></a></p>
						<div id="more_files"></div>
						</td>
					</tr>
				</tbody>
				</table>
				<?php 
	        }
	        ?>
		<input type="hidden" name="count_files"	id="count_files" value="1" />	
		<input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
		<input type="hidden" name="task" id="task" value="" />
		<?php JHTML::_( 'form.token' ); ?>
		<?php echo @$form['validate']; ?>
			
		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplayTicketForm', array( @$row, JFactory::getUser() ) );
			?>
		</div>

	<input type="button" class="button" onclick="document.adminForm.task.value='save'; submitbutton('save');" value="<?php echo JText::_('COM_BILLETS_SUBMIT'); ?>" />

</form>