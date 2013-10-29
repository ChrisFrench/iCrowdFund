<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; 
JFilterOutput::objectHTMLSafe( $row );
?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

    <fieldset>
        <legend><?php echo JText::_('Form'); ?></legend>
            <table class="admintable">
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Scope' ); ?>:
                    </td>
                    <td>
                        <?php echo AmbraSelect::scope( @$row->pointrule_scope, 'pointrule_scope' ); ?>
                        <?php echo JText::_( "or new" ); ?>: <input type="text" name="pointrule_scope_new" id="pointrule_scope_new" size="48" maxlength="250" value="" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Event' ); ?>:
                    </td>
                    <td>
                        <?php echo AmbraSelect::event( @$row->pointrule_event, 'pointrule_event' ); ?>
                        <?php echo JText::_( "or new" ); ?>: <input type="text" name="pointrule_event_new" id="pointrule_event_new" size="48" maxlength="250" value="" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Profile' ); ?>:
                    </td>
                    <td>
                        <?php echo AmbraSelect::profile( @$row->profile_id, 'profile_id', '', '', true ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Points' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointrule_value" id="pointrule_value" size="10" maxlength="11" value="<?php echo @$row->pointrule_value; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Enabled' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'pointrule_enabled', '', @$row->pointrule_enabled ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Approve Points Automatically' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'pointrule_auto_approve', '', @$row->pointrule_auto_approve ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Expires' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->expire_date, "expire_date", "expire_date", '%Y-%m-%d %H:%M:%S' ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointrule_uses_max" id="pointrule_uses_max" size="10" maxlength="11" value="<?php echo @$row->pointrule_uses_max; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses Per User' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointrule_uses_per_user" id="pointrule_uses_per_user" size="10" maxlength="11" value="<?php echo @$row->pointrule_uses_per_user; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Maximum Uses Per User Per Day' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointrule_uses_per_user_per_day" id="pointrule_uses_per_user_per_day" size="10" maxlength="11" value="<?php echo @$row->pointrule_uses_per_user_per_day; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Name' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="pointrule_name" id="pointrule_name" size="48" maxlength="250" value="<?php echo @$row->pointrule_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor(); ?>
                        <?php echo $editor->display( 'pointrule_description',  @$row->pointrule_description, '100%', '450', '100', '20' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="pointrule_params" id="pointrule_params" rows="10" cols="35"><?php echo @$row->pointrule_params; ?></textarea>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="id" value="<?php echo @$row->pointrule_id; ?>" />
            <input type="hidden" name="task" value="" />
    </fieldset>
</form>