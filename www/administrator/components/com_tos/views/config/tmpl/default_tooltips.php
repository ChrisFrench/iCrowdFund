<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
echo $this->sliders->startPanel( JText::_( "COM_TOS_ADMIN_TOOLTIPS" ), 'admin_tooltips' );
?>

<table class="adminlist">
    <tbody>
        <tr>
            <th style="width: 25%;">
            <?php echo JText::_( 'COM_TOS_HIDE_DASHBOARD_TOOLTIPS' ); ?>
            </th>
            <td>
            <?php echo JHTML::_('select.booleanlist', 'page_tooltip_dashboard_disabled', 'class="inputbox"', $this->row->get('page_tooltip_dashboard_disabled', '0') ); ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th style="width: 25%;">
            <?php echo JText::_( 'COM_TOS_HIDE_CONFIGURATION_TOOLTIPS' ); ?>
            </th>
            <td>
            <?php echo JHTML::_('select.booleanlist', 'page_tooltip_config_disabled', 'class="inputbox"', $this->row->get('page_tooltip_config_disabled', '0') ); ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th style="width: 25%;">
            <?php echo JText::_( 'COM_TOS_HIDE_TOOLS_TOOLTIPS' ); ?>
            </th>
            <td>
            <?php echo JHTML::_('select.booleanlist', 'page_tooltip_tools_disabled', 'class="inputbox"', $this->row->get('page_tooltip_tools_disabled', '0') ); ?>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>


<?php
echo $this->sliders->endPanel();

?>