
<?php 

defined('_JEXEC') or die('Restricted access');

$form = @$this->form;
$row = @$this->row;
$select_redirect = @$this->select_redirect;



echo DSCGrid::pagetooltip( JRequest::getVar('view') );
?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" class="adminForm adminform"  id="adminForm" enctype="multipart/form-data">

		<div id='onBeforeDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onBeforeDisplayConfigForm', array() );
			?>
		</div>                

		<table style="width: 100%;">
			<tbody>
                <tr>
					<td style="vertical-align: top;">

					<?php
					// display defaults
					$pane = '1';
					echo $this->sliders->startPane( "pane_$pane" );

                    $legend = JText::_('COM_BILLETS_ATTACHMENTS');
                    echo $this->sliders->startPanel( JText::_( $legend ), 'attachments' );
                    ?>
                        <table class="userlist">
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_ENABLE_ATTACHMENTS'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.booleanlist', 'files_enable', 'class="inputbox"', @$row->get('files_enable', '1') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_MAXIMUM_SIZE_IN_KB'); ?>
                            </td>
                            <td class="input">
                                <input name="files_maxsize" type="text" class="text_area" size="50" value="<?php echo @$row->get('files_maxsize', '3000'); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_FILE_STORAGE_METHOD'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.genericlist', BilletsFile::getArrayListStorageMethods(), "files_storagemethod", "size='1' ", "value", "text", @$row->get('files_storagemethod', '1') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_RESTRICT_FILE_EXTENSIONS'); ?>
                            </td>
                            <td class="input">
                                <input name="restricted_file_extensions" type="text" class="text_area" size="50" value="<?php echo @$row->get('restricted_file_extensions', 'php,html,asp,aspx'); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_AUTOMATICALLY_APPEND_TXT_TO_RESTRICTED_FILE_EXTENSION'); ?>
                            </td>
                            <td class="input">
                                <input name="auto_convert_file_extension" type="radio" value="0" <?php echo (@$row->get('auto_convert_file_extension')==0?'checked="checked"':''); ?> /> <?php echo JText::_('COM_BILLETS_NO'); ?>
								<input name="auto_convert_file_extension" type="radio" value="1" <?php echo (@$row->get('auto_convert_file_extension','1')==1?'checked="checked"':''); ?> /> <?php echo JText::_('COM_BILLETS_YES'); ?>
                            </td>
                        </tr>
						<tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_SAVE_ATTACHMENTS_FROM_EMAIL'); ?>
                            </td>
                            <td class="input">
                               <?php echo JHTML::_('select.booleanlist', 'emails_save_attachments', 'class="inputbox"', @$row->get('emails_save_attachments', '0') ); ?>
                            </td>
                        </tr>
                        </table>
                    <?php
                    echo $this->sliders->endPanel();
                    
                    $legend = JText::_('COM_BILLETS_DISPLAY');
                    echo $this->sliders->startPanel( JText::_( $legend ), 'display' );
                    ?>
                        <table class="userlist">
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_REQUIRE_LOGIN'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.booleanlist', 'require_login', 'class="inputbox"', @$row->get('require_login', '0') ); ?>
                            </td>
                        </tr>
                        
                        <tr>
                        	<td class="title">
                        		<?php echo JText::_('COM_BILLETS_REDIRECT_TO_MENU_ITEM'); ?>
                        	</td>
                        	<td class="input">
                        		<?php echo BilletsSelect::menuitem( @$row->get('redirect_menu_id', ''), 'redirect_menu_id' ); ?>
                        	</td>
                        </tr>
                        
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_SHOW_NAME_OR_EMAIL_IN_COMMENTS'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.genericlist', BilletsHelperUser::getArrayListNameDisplay(), "display_name", "size='1' ", "value", "text", @$row->get('display_name', '1') ); ?>
                            </td>
                        </tr>
                        
                        
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_NUMBER_OF_USERS_TICKETS_IN_TICKETS_LINKS_LIST'); ?>
                            </td>
                            <td class="input">
                                <input name="number_of_tickets_links" value="<?php echo $this->row->get('number_of_tickets_links', '5'); ?>" class="inputbox" type="text" />
                            </td>
                        </tr>
                         <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_CONFIG_DATELAYOUT'); ?>
                            </td>
                            <td class="input">
                                <input name="datelayout" value="<?php echo $this->row->get('datelayout', 'DATE_FORMAT_LC1'); ?>" class="inputbox" type="text" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'COM_BILLETS_JAVASCRIPT_FRAMEWORK' ); ?>
                            </th>
                            <td>
                              <?php echo BilletsSelect::usejQuery( @$this->row->get('use_jquery', '0'), 'use_jquery' ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        </table>
                        
                   	<?php
                    echo $this->sliders->endPanel();
                    
                    $legend = JText::_('COM_BILLETS_EMAILS');
                    echo $this->sliders->startPanel( JText::_( $legend ), 'emails' );
                    ?>
                        <table class="userlist">
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_DEFAULT_SENDER_NAME'); ?>
                            </td>
                            <td class="input">
                                <input name="emails_defaultname" type="text" class="text_area" size="50" value="<?php echo @$row->get('emails_defaultname', $this->lists->fromname ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_DEFAULT_SENDER_EMAIL'); ?>
                            </td>
                            <td class="input">
                                <input name="emails_defaultemail" type="text" class="text_area" size="50" value="<?php echo @$row->get('emails_defaultemail', $this->lists->mailfrom ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_INCLUDE_DESCRIPTION_IN_EMAIL_NOTICES'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.booleanlist', 'emails_includedescription', 'class="inputbox"', @$row->get('emails_includedescription', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_DESCRIPTION_MAXIMUM_CHARACTERS'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.integerlist', '-1', '599', '150', 'emails_descriptionmaxlength', 'class="inputbox"', @$row->get('emails_descriptionmaxlength', '-1') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_INCLUDE_COMMENTS_IN_EMAIL_NOTICES'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.booleanlist', 'emails_includecomments', 'class="inputbox"', @$row->get('emails_includecomments', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_COMMENTS_MAXIMUM_LENGTH'); ?>
                            </td>
                            <td class="input">
                                <?php echo JHTML::_('select.integerlist', '-1', '599', '150', 'emails_commentmaxlength', 'class="inputbox"', @$row->get('emails_commentmaxlength', '-1') ); ?>
                            </td>
                        </tr>
						<tr>
                            <td class="title">
                                <?php echo wordwrap( JText::_('COM_BILLETS_SEND_EMAILS_TO_USERS_AND_ADMINS_WHEN_THEY_ADD_COMMENTS_TO_TICKETS'), 30, '<br />' ); ?>
                            </td>
                            <td class="input">
                               <?php echo JHTML::_('select.booleanlist', 'emails_sendselfnotification', 'class="inputbox"', @$row->get('emails_sendselfnotification', '0') ); ?>
                            </td>
                        </tr>
						<tr>
                            <td class="title">
                                <?php echo JText::_('COM_BILLETS_DEBUG_ENCODING'); ?>
                            </td>
                            <td class="input">
                               <?php echo JHTML::_('select.booleanlist', 'emails_encoding_debug', 'class="inputbox"', @$row->get('emails_encoding_debug', '0') ); ?>
                            </td>
                        </tr>
                        </table>
                    <?php
                    echo $this->sliders->endPanel();
					
					$legend = JText::_('COM_BILLETS_TICKET_STATES');
					echo $this->sliders->startPanel( JText::_( $legend ), 'ticketstates' );
					?>
					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_STATUS_FOR_NEW_TICKETS'); ?>
							</th>
			                <td>
			                	<?php echo BilletsSelect::ticketstate( @$row->get( 'state_new', '1' ), 'state_new' ); ?>
			                </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_STATUS_FOR_CLOSED_TICKETS'); ?>
							</th>
			                <td>
			                	<?php echo BilletsSelect::ticketstate( @$row->get( 'state_closed', '2' ), 'state_closed' ); ?>
			                </td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_STATUS_FOR_TICKETS_AWAITING_USER_FEEDBACK'); ?>
							</th>
			                <td>
			                	<?php echo BilletsSelect::ticketstate( @$row->get( 'state_feedback', '3' ), 'state_feedback' ); ?>
			                </td>
						</tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_ENABLE_FRONT_END_USERS_TO_EDIT_TICKET_PROPERTIES'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'enable_frontend_editing', 'class="inputbox"', $this->row->get('enable_frontend_editing', '0') ); ?>
                            </td>
                        </tr>
					</tbody>
					</table>
					<?php	
					echo $this->sliders->endPanel();
					
					$legend = JText::_('COM_BILLETS_OTHER_SETTINGS');
					echo $this->sliders->startPanel( JText::_( $legend ), 'others' );
					?>
					
					<table class="adminlist">
					<tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_DISPLAY_IE_8_NOTICE'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'display_ie8_notice', 'class="inputbox"', $this->row->get('display_ie8_notice', '1') ); ?>
                            </td>
                            <td>
                                <?php echo JText::_('COM_BILLETS_DISPLAY_IE_8_NOTICE_DESC'); ?>
                            </td>
                        </tr>                        
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_SHOW_DIOSCOURI_LINK_IN_FOOTER'); ?>
							</th>
			                <td>
								<?php echo JHTML::_('select.booleanlist', 'show_linkback', 'class="inputbox"', $this->row->get('show_linkback', '1') ); ?>
			                </td>
			                <td></td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_YOUR_DIOSCOURI_AFFILIATE_ID'); ?>
							</th>
			                <td>
			                	<input type="text" name="amigosid" value="<?php echo $this->row->get('amigosid', ''); ?>" class="inputbox" />
			                </td>
			                <td>
			                	<a href='http://www.dioscouri.com/index.php?option=com_amigos' target='_blank'>
			                	<?php echo JText::_('COM_BILLETS_NO_AMIGOSID'); ?>
			                	</a>			                	
			                </td>
						</tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_ENABLE_TICKET_LOCKING'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'locking_enabled', 'class="inputbox"', $this->row->get('locking_enabled', '1') ); ?>
                            </td>
                            <td>                                
                            </td>
                        </tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_AUTO_CHECKIN_TICKETS_UNLOCK_AFTER'); ?>
							</th>
			                <td>
			                	<input type="text" name="maxCheckOutTime" value="<?php echo $this->row->get('maxCheckOutTime', ''); ?>" class="inputbox" />
			                </td>
			                <td>
			                	<?php echo JText::_('COM_BILLETS_SECONDS'); ?>			                	
			                </td>
						</tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_ENABLE_TICKET_LIMITING_GLOBALLY'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'limit_tickets_globally', 'class="inputbox"', $this->row->get('limit_tickets_globally', '0') ); ?>
                            </td>
                            <td>
                                <?php echo JText::_('COM_BILLETS_ENABLE_TICKET_LIMITING_GLOBALLY_DESC'); ?>                                
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_DEFAULT_TICKET_MAXIMUM'); ?>
                            </th>
                            <td>
                                <input name="default_max_tickets" value="<?php echo $this->row->get('default_max_tickets', '10'); ?>" class="inputbox" type="text" />
                            </td>
                            <td>                               
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_ENABLE_HOUR_LIMITING_GLOBALLY'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'limit_hours_globally', 'class="inputbox"', $this->row->get('limit_tickets_globally', '0') ); ?>
                            </td>
                            <td>
                                <?php echo JText::_('COM_BILLETS_ENABLE_HOUR_LIMITING_GLOBALLY_DESC'); ?>                                
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_DEFAULT_HOURS_MAXIMUM'); ?>
                            </th>
                            <td>
                                <input name="default_max_hours" value="<?php echo $this->row->get('default_max_hours', '10'); ?>" class="inputbox" type="text" />
                            </td>
                            <td>                               
                            </td>
                        </tr>
					</tbody>
					</table>
					<?php	
					echo $this->sliders->endPanel();
					
					$legend = JText::_('COM_BILLETS_ADMINISTRATOR_TOOLTIPS');
					echo $this->sliders->startPanel( JText::_( $legend ), 'defaults' );
					?>
					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_DASHBOARD_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_dashboard_disabled', 'class="inputbox"', $this->row->get('page_tooltip_dashboard_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_CONFIGURATION_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_config_disabled', 'class="inputbox"', $this->row->get('page_tooltip_config_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_TOOLS_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_tools_disabled', 'class="inputbox"', $this->row->get('page_tooltip_tools_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_CATEGORIES_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_categories_disabled', 'class="inputbox"', $this->row->get('page_tooltip_categories_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_FIELDS_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_fields_disabled', 'class="inputbox"', $this->row->get('page_tooltip_fields_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_TICKETS_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_tickets_disabled', 'class="inputbox"', $this->row->get('page_tooltip_tickets_disabled', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_HIDE_CANNED_TEXT_NOTE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'page_tooltip_frequents_disabled', 'class="inputbox"', $this->row->get('page_tooltip_frequents_disabled', '0') ); ?>
							</td>
						</tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_HIDE_USER_DASHBOARD_NOTE'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_users_view_disabled', 'class="inputbox"', $this->row->get('page_tooltip_users_view_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_('COM_BILLETS_HIDE_MERGE_TICKETS_NOTE'); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_tickets_merge_disabled', 'class="inputbox"', $this->row->get('page_tooltip_tickets_merge_disabled', '0') ); ?>
                            </td>
                        </tr>
					</tbody>
					</table>
					<?php	
					echo $this->sliders->endPanel();
					
					$legend = JText::_('COM_BILLETS_TICKET_TO_ARTICLE_CONVERSION_DEFAULTS');
					echo $this->sliders->startPanel( JText::_( $legend ), 't2adefaults' );
					?>					
					<table class="adminlist">
					<tbody>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_DEFAULT_CATEGORY'); ?>
							</th>
							<td>
		                    	<?php echo BilletsSelect::contentcategory( $this->row->get( 'kb_category' ), 'kb_category', null, null, true ); ?>					
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_AUTOPUBLISH_ARTICLES'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'kb_publish', 'class="inputbox"', $this->row->get('kb_publish', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_INCLUDE_USERNAMES'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'kb_username', 'class="inputbox"', $this->row->get('kb_username', '0') ); ?>
							</td>
						</tr>
						<tr>
			            	<th style="width: 25%;">
								<?php echo JText::_('COM_BILLETS_USE_READMORE'); ?>
							</th>
							<td>
		                        <?php echo JHTML::_('select.booleanlist', 'kb_readmore', 'class="inputbox"', $this->row->get('kb_readmore', '0') ); ?>
							</td>
						</tr>
					</tbody>
					</table>
					<?php	
					echo $this->sliders->endPanel();
					
                    $legend = JText::_('COM_BILLETS_INTEGRATIONS');
                    echo $this->sliders->startPanel( JText::_( $legend ), 'integrations' );
                    ?>                  
                    <table class="adminlist">
                    <tbody>
                    <tr>
                        <th style="width: 25%;">
                            <?php echo JText::_('COM_BILLETS_ENABLE_TIENDA_INTEGRATION'); ?>
                        </th>
                        <td>
                            <?php echo JHTML::_('select.booleanlist', 'enable_tienda', 'class="inputbox"', $this->row->get('enable_tienda', '1') ); ?>                  
                        </td>
                    </tr>
                    </tbody>
                    </table>
                    <?php   
                    echo $this->sliders->endPanel();
					
					// if there are plugins, display them accordingly
	                if ($this->items_sliders) 
	                {               	
                		$tab=1;
						$pane=2;
						for ($i=0, $count=count($this->items_sliders); $i < $count; $i++) {
							if ($pane == 1) {
								// echo $this->sliders->startPane( "pane_$pane" );
							}
							$item = $this->items_sliders[$i];
							echo $this->sliders->startPanel( JText::_( $item->element ), $item->element );
							
							// load the plugin
								$import = JPluginHelper::importPlugin( strtolower( 'Billets' ), $item->element );
							// fire plugin
								$dispatcher = JDispatcher::getInstance();
								$dispatcher->trigger( 'onDisplayConfigFormSliders', array( $item, $this->row ) );
								
							echo $this->sliders->endPanel();
							if ($i == $count-1) {
								// echo $this->sliders->endPane();
							}
						}
					}
					
					echo $this->sliders->endPane();
					
					?>
					</td>
                </tr>
            </tbody>
		</table>

		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplayConfigForm', array() );
			?>
		</div>
        
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>