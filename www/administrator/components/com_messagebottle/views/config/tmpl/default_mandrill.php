<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
echo $this->sliders->startPanel( JText::_( "COM_MESSAGEBOTTLE_MANDRILL" ), 'general' );
?>

<table class="adminlist">
<tbody>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_MANDRILL_HOST' ); ?>
    </th>
    <td>
        <input name="md_host" value="<?php echo $this->row->get('md_host', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        <?php echo JText::_( "COM_MESSAGEBOTTLE_MANDRILL_TIP" ); ?>
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_MANDRILL_PORT' ); ?>
    </th>
    <td>
        <input name="md_port" value="<?php echo $this->row->get('md_port', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_MANDRILL_USERNAME' ); ?>
    </th>
    <td>
       <input name="md_smtp_username" value="<?php echo $this->row->get('md_smtp_username', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        
    </td>
</tr>
<tr>
    <th style="width: 25%;">
        <?php echo JText::_( 'COM_MESSAGEBOTTLE_MANDRILL_APIKEY' ); ?>
    </th>
    <td>
       <input name="md_smtp_api_key" value="<?php echo $this->row->get('md_smtp_api_key', ''); ?>" type="text" size="40"/>
    </td>
    <td>
        
    </td>
</tr>
</tbody>
</table>
<?php
echo $this->sliders->endPanel();

?>