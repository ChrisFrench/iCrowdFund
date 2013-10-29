<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
echo $this->sliders->startPanel( JText::_( "COM_MESSAGEBOTTLE_GENERAL_SETTINGS" ), 'general' );
?>

<table class="adminlist">
<tbody>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_DEFAULT_KEY' ); ?>
    </th>
    <td>
        <input name="key" value="<?php echo $this->row->get('key', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        <?php echo JText::_( "COM_MESSAGEBOTTLE_DEFAULT_KEY_TIP" ); ?>
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_DEFAULT_NAME' ); ?>
    </th>
    <td>
        <input name="default_name" value="<?php echo $this->row->get('default_name', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        <?php echo JText::_( "COM_MESSAGEBOTTLE_DEFAULT_NAME_TIP" ); ?>
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_DEFAULT_EMAIL' ); ?>
    </th>
    <td>
        <input name="default_email" value="<?php echo $this->row->get('default_email', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        <?php echo JText::_( "COM_MESSAGEBOTTLE_DEFAULT_EMAIL_TIP" ); ?>
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_SET_DATE_FORMAT' ); ?>
    </th>
    <td>
        <input name="date_format" value="<?php echo $this->row->get('date_format', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        <?php echo JText::_( "COM_MESSAGEBOTTLE_SET_DATE_FORMAT_TIP" ); ?>
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_SHOW_LINKBACK' ); ?>
    </th>
    <td>
        <?php echo JHTML::_('select.booleanlist', 'show_linkback', 'class="inputbox"', $this->row->get('show_linkback', '1') ); ?>
    </td>
    <td>
        
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_INCLUDE_SITE_CSS' ); ?>
    </th>
    <td>
        <?php echo JHTML::_('select.booleanlist', 'include_site_css', 'class="inputbox"', $this->row->get('include_site_css', '1') ); ?>
    </td>
    <td>
        
    </td>
</tr>
</tbody>
</table>
<?php
echo $this->sliders->endPanel();

?>