<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$vars->state; ?>
<?php echo @$vars->token; ?>

    <p><?php echo JText::_('THIS TOOL MIGRATES DATA FROM RSTICKETS TO BILLETS'); ?></p>

    <div class="note">
        <span style="float: right; font-size: large; font-weight: bold;"><?php echo JText::_('STEP TWO OF THREE'); ?></span>
        <p><?php echo JText::_('YOU PROVIDED THE FOLLOWING INFORMATION'); ?></p>
    </div>

    <fieldset>
        <legend><?php echo JText::_('DATABASE CONNECTION'); ?></legend>
            <table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('HOST'); ?>:
                    </td>
                    <td>
                        <?php echo @$state->host; ?>
                        <input type="hidden" name="host" id="host" size="48" maxlength="250" value="<?php echo @$state->host; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('COM_BILLETS_USERNAME'); ?>:
                    </td>
                    <td>
                        <?php echo @$state->user; ?>
                        <input type="hidden" name="user" id="user" size="48" maxlength="250" value="<?php echo @$state->user; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('COM_BILLETS_PASSWORD'); ?>:
                    </td>
                    <td>
                       *****
                        <input type="hidden" name="password" id="password" size="48" maxlength="250" value="<?php echo @$state->password; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE NAME'); ?>:
                    </td>
                    <td>
                        <?php echo @$state->database; ?>
                        <input type="hidden" name="database" id="database" size="48" maxlength="250" value="<?php echo @$state->database; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('JOOMLA! TABLE PREFIX'); ?>: *
                    </td>
                    <td>
                        <?php echo @$state->prefix; ?>
                        <input type="hidden" name="prefix" id="prefix" size="48" maxlength="250" value="<?php echo @$state->prefix; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('RSTICKETS TABLE PREFIX'); ?>: *
                    </td>
                    <td>
                        <?php echo @$state->rs_prefix; ?>
                        <input type="hidden" name="rs_prefix" id="rs_prefix" size="48" maxlength="250" value="<?php echo @$state->rs_prefix; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE TYPE'); ?>:
                    </td>
                    <td>
                        <?php echo @$state->driver; ?>
                        <input type="hidden" name="driver" id="driver" size="48" maxlength="250" value="<?php echo @$state->driver; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE PORT'); ?>:
                    </td>
                    <td>
                        <?php echo @$state->port; ?>
                        <input type="hidden" name="port" id="port" size="48" maxlength="250" value="<?php echo @$state->port; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                
                <?php if (!empty($state->existing_states)) : ?>
                <?php $config = Billets::getInstance(); ?>
                <?php foreach ($state->existing_states as $status) : ?>
                    <tr>
                        <td width="100" align="right" class="key">
                            <?php echo JText::_('NEW STATUS FOR TICKETS THAT ARE') . " `".$status."`"; ?>:
                        </td>
                        <td>
                            <?php switch ($status) 
                            {
                                case "closed":
                                    $default = $config->get( 'state_closed', '2' );
                                    break;
                                case "on-hold":
                                    $default = $config->get( 'state_feedback', '3' );
                                    break;
                                case "open":
                                default:
                                    $default = $config->get( 'state_new', '1' );
                                    break;
                            } ?>
                            <?php echo BilletsSelect::ticketstate( $default, "status_map[$status]" ); ?>
                        </td>
                        <td>
                        
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </table>    
    </fieldset>