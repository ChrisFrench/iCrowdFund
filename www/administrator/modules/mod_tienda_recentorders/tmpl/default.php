<?php
/**
 * @version    1.5
 * @package    Tienda
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>

<?php $options = array('num_decimals'=>'0'); ?>
    
<table class="table table-striped table-bordered" style="margin-bottom: 5px;">
<thead>
<tr>
    <th colspan="3"><?php echo JText::_('COM_TIENDA_RECENT_ORDERS'); ?></th>
</tr>
</thead>
<tbody>
<tr>
    <th><?php echo JText::_('COM_TIENDA_CUSTOMER'); ?></th>
    <th style="text-align: center;"><?php echo JText::_('COM_TIENDA_DATE'); ?></th>
    <th style="text-align: right;"><?php echo JText::_('COM_TIENDA_TOTAL'); ?></th>
</tr>
<?php
foreach ($orders as $order)
{
    ?>
    <tr>
        <td><a href="<?php echo $order->link; ?>"><?php echo $order->user_name; ?></a></td>
        <td style="text-align: center;"><?php echo JHTML::_('date', $order->created_date, Tienda::getInstance()->get('date_format')); ?></td>
        <td style="text-align: right;"><?php echo TiendaHelperBase::currency( $order->order_total, $order->currency ); ?></td>
    </tr>
    <?php
} 
?>
</tbody>
</table>