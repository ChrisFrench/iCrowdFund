<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$vars->state; ?>
<?php echo @$vars->token; ?>

    <p><?php echo JText::_('THIS TOOL MIGRATES DATA FROM RSTICKETS TO BILLETS'); ?></p>

    <div class="note">
        <span style="float: right; font-size: large; font-weight: bold;"><?php echo JText::_('STEP ONE OF THREE'); ?></span>
        <p><?php echo JText::_('PLEASE PROVIDE THE REQUESTED INFORMATION'); ?></p>
    </div>
    
    <fieldset>
        <legend><?php echo JText::_('DATABASE CONNECTION'); ?></legend>
            <table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('HOST'); ?>: *
                    </td>
                    <td>
                        <input type="text" name="host" id="host" size="48" maxlength="250" value="<?php echo @$state->host; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('COM_BILLETS_USERNAME'); ?>: *
                    </td>
                    <td>
                        <input type="text" name="user" id="user" size="48" maxlength="250" value="<?php echo @$state->user; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('COM_BILLETS_PASSWORD'); ?>: *
                    </td>
                    <td>
                        <input type="password" name="password" id="password" size="48" maxlength="250" value="<?php echo @$state->password; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE NAME'); ?>: *
                    </td>
                    <td>
                        <input type="text" name="database" id="database" size="48" maxlength="250" value="<?php echo @$state->database; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('JOOMLA! TABLE PREFIX'); ?>: *
                    </td>
                    <td>
                        <input type="text" name="prefix" id="prefix" size="48" maxlength="250" value="<?php echo @$state->prefix; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('RSTICKETS TABLE PREFIX'); ?>: *
                    </td>
                    <td>
                        <input type="text" name="rs_prefix" id="rs_prefix" size="48" maxlength="250" value="<?php echo @$state->rs_prefix; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE TYPE'); ?>:
                    </td>
                    <td>
                        <input type="text" name="driver" id="driver" size="48" maxlength="250" value="<?php echo @$state->driver; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <?php echo JText::_('DATABASE PORT'); ?>:
                    </td>
                    <td>
                        <input type="text" name="port" id="port" size="48" maxlength="250" value="<?php echo @$state->port; ?>" />
                    </td>
                    <td>
                    
                    </td>
                </tr>
            </table>    
    <br />
    * <?php echo JText::_('INDICATES A REQUIRED FIELD'); ?>
    </fieldset>
        