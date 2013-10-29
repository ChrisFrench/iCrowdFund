<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	echo $this -> sliders -> startPanel(JText::_("COM_WEPAY_CHECKOUT_SETTINGS"), 'checkout');
?>

<table class="adminlist">
	<tbody>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_PLATFORM_FEE'); ?> </th>
			<td> <input name="platform_fee" value="<?php echo $this -> row -> get('platform_fee', ''); ?>" type="text" size="40"/></td>
			<td></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_PLATFORM_FEE_OPTION'); ?> </th>
			<td> <?php echo WepaySelect::feeOptions($this -> row -> get('platform_fee_option', ''), 'platform_fee_option',null, 'platform_fee_option', true); ?></td>
			<td></td>
		</tr>
		
	</tbody>
</table>
<?php
echo $this -> sliders -> endPanel();
?>