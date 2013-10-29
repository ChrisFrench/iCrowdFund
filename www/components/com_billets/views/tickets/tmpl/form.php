<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.mootools');  JHTML::_('behavior.framework', true); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php JHTML::_('stylesheet', 'billets.css', 'media/com_billets/css/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<div class='componentheading'>
	<span><?php echo JText::_('COM_BILLETS_CREATE_TICKET'); ?></span>
</div>

<?php
	if (!empty($row->id))
	{
		echo "<< <a href='".JRoute::_("index.php?option=com_billets&view=tickets&task=view&id=".$row->id)."'>".JText::_('COM_BILLETS_CANCEL_AND_RETURN_TO_TICKET')."</a>";		
	} 
	else
	{
		echo "<< <a href='".JRoute::_("index.php?option=com_billets&view=tickets")."'>".JText::_('COM_BILLETS_CANCEL_AND_RETURN_TO_TICKET_LIST')."</a>";	
	}
?>

<div class="note"><?php echo JText::_('COM_BILLETS_CATEGORIZE_TICKET'); ?></div>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" id="adminForm" name="adminForm" enctype="multipart/form-data" onsubmit="Dsc.formValidation( '<?php echo @$form['validation']; ?>', 'validationmessage', document.adminForm.task.value, document.adminForm );">

		<div id="validationmessage"></div>
	
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2" style="text-align: left;"><?php echo JText::_('COM_BILLETS_TICKET_DETAILS'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="100" align="left" class="key">
						<label for="categoryid">
						<?php echo JText::_('COM_BILLETS_CATEGORY'); ?>:
						</label>
					</td>
					<td>
						<?php $id = @$row->id; ?>
						<?php $url = "index.php?option=com_billets&format=raw&view=tickets&task=getFields&ticketid={$id}&categoryid="; ?>
						<?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => "Dsc.doTask( '$url'+document.adminForm.categoryid.options[document.adminForm.categoryid.selectedIndex].value, 'additional_info_wrapper', document.adminForm );" ); ?>
						<?php echo BilletsSelect::category( @$row->categoryid, 'categoryid', $attribs, 'categoryid', true, false, 'COM_BILLETS_SELECT_CATEGORY', '', true ); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="left" class="key">
						<label for="title">
						<?php echo JText::_('COM_BILLETS_SUBJECT'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="title" id="title" size="48" maxlength="250" value="<?php echo htmlspecialchars(@$row->title); ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="left" class="key">
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
						<?php $fulltext = trim( htmlspecialchars( @$row->description ) ); ?>
						<textarea id="description" name="description" rows="10" style="width:98%" onkeydown="Billets.tabHandleKeyDown(event);"><?php echo $fulltext; ?></textarea>						
					</td>
				</tr>
				<?php
					$require_login = Billets::getInstance()->get( 'require_login', '1' );
					if (!$require_login && empty(JFactory::getUser()->id)) 
					{
						?>
		                <tr>
							<td align="left" class="key">
								<?php echo JText::_('COM_BILLETS_USERNAME'); ?>:
							</td>
							<td>
								<input name="user_name" type="text" class="text_area" size='20' />
							</td>
						</tr>
						<tr>
							<td align="left" class="key">
								<?php echo JText::_('COM_BILLETS_PASSWORD'); ?>:								
							</td>
							<td>
								<input name="user_password" type="password" class="text_area" size='20' />
							</td>
						</tr>
						<tr id="newuser_email_row" style="visibility:hidden">
							<td  align="left" class="key">
								<?php echo JText::_('COM_BILLETS_EMAIL_ADDRESS'); ?>:
							</td>
							<td>
								<input name="newuser_email" type="text" class="text_area" size='20' />
							</td>
						</tr>
						<tr>
							<td align="left" class="key">
								<?php echo JText::_('COM_BILLETS_REGISTER_NEW_USER'); ?>		
							</td>
							<td>
								<input id="newuser_register_checkbox" name="newuser_register" type="checkbox" onchange="ShowHideCell('newuser_register_checkbox','newuser_email_row')"/>
							</td>
		                </tr>
		                <script type="text/javascript">
			                function ShowHideCell(check,cell) 
			                { 			                	
			                    if(document.getElementById(check).checked == true){
			                	document.getElementById(cell).style.visibility='visible';
			                	}else{
			                    document.getElementById(cell).style.visibility='hidden';
			                	}	
			                }
						</script>

						<?php
					}
				?>
			</tbody>
			</table>
			
        	
            <?php if (JFactory::getUser()->id && Billets::getInstance()->get('enable_tienda', '1') && BilletsHelperTienda::isInstalled()) 
            {
            ?>
                <table class="adminlist">
                <thead>
                    <tr>
                        <th style="text-align: left;" colspan="2">
                        <?php echo JText::_('COM_BILLETS_ORDER_INFORMATION'); ?> 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                        <div class="note"><?php echo JText::_('COM_BILLETS_PLEASE_SELECT_THE_ORDER_THIS_ISSUE_APPLIES_TO'); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo JText::_('COM_BILLETS_ORDER_ID'); ?>
                        </td>
                        <td>
                            <?php Tienda::load("TiendaSelect", 'library.select'); ?>
                            <?php echo TiendaSelect::order( JFactory::getUser()->id, @$row->tienda_orderid, 'tienda_orderid' ); ?>
                        </td>
                    </tr>
                </tbody>
                </table>
                <?php 
            }
            ?>
			
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