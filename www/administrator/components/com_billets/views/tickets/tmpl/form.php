<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<script>
function onCloseModal() {
    doTask( 'index.php?option=com_billets&view=tickets&format=raw&task=updateTiendaOrders', 'tienda_integration', document.adminForm );
}
</script>


<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="Dsc.formValidation( '<?php echo @$form['validation']; ?>', 'validationmessage', document.adminForm.task.value, document.adminForm )">

	<div id="validationmessage"></div>
	
	<fieldset>
		<legend><?php echo JText::_('COM_BILLETS_FORM'); ?></legend>
			<table class="admintable">
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
						<label for="title">
						<?php echo JText::_('COM_BILLETS_TITLE'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="title" id="title" size="48" maxlength="250" value="<?php echo htmlspecialchars(@$row->title); ?>" />
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
						<?php $url = "index.php?option=com_billets&format=raw&view=categories&task=getFields&ticketid={$id}&categoryid="; ?>
						<?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'Dsc.doTask( \''.$url.'\'+document.adminForm.categoryid.options[document.adminForm.categoryid.selectedIndex].value, \'additional_info_wrapper\', document.adminForm);' ); ?>
						<?php echo BilletsSelect::category( @$row->categoryid, 'categoryid', $attribs, 'categoryid', true ); ?>
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
						<?php $fulltext = trim( htmlspecialchars( @$row->description ) ); ?>
						<textarea id="description" name="description" rows="10" style="width:98%" onkeydown="Billets.tabHandleKeyDown(event);"><?php echo $fulltext; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="hours_spent">
						<?php echo JText::_('COM_BILLETS_HOURS_SPENT'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="hours_spent" id="hours_spent" value="<?php echo @$row->hours_spent ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="attachment">
						<?php echo JText::_('COM_BILLETS_ATTACHMENT'); ?>:
						</label>
					</td>
					<td>
						<p><input class="text_area" name="userfile[]" type="file" size="25" /> <a href="javascript:void(0);" onclick="Billets.addAnotherFile('<?php echo JText::_('COM_BILLETS_REMOVE');?>')"><?php echo JText::_('COM_BILLETS_ADD_MORE_FILES');?></a></p>
						<div id="more_files"></div>
					</td>
				</tr>
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
		if ( isset( $hField['script'] ) ) {
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
			<input type="hidden" name="count_files"	id="count_files" value="1" />
			<input type="hidden" name="id" value="<?php echo @$row->id?>" />
			<input type="hidden" name="task" id="task" value="" />
	</fieldset>
	
		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplayTicketForm', array( @$row, JFactory::getUser() ) );
			?>
		</div>
</form>