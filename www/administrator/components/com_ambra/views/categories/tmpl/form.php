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
                        <?php echo JText::_( 'Name' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="category_name" id="category_name" size="48" maxlength="250" value="<?php echo @$row->category_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Enabled' ); ?>:
                    </td>
                    <td>
                        <?php echo JHTML::_('select.booleanlist', 'category_enabled', '', @$row->category_enabled ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Description' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor(); ?>
                        <?php echo $editor->display( 'category_description',  @$row->category_description, '100%', '450', '100', '20' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Category Params' ); ?>:
                    </td>
                    <td>
                        <textarea name="category_params" id="category_params" rows="10" cols="35"><?php echo @$row->category_params; ?></textarea>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="id" value="<?php echo @$row->category_id; ?>" />
            <input type="hidden" name="task" value="" />
    </fieldset>
</form>