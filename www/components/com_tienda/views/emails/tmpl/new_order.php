<?php echo sprintf(JText::_('COM_TIENDA_EMAIL_DEAR'),$this->user_name); ?><br>
<?php echo JText::_('COM_TIENDA_EMAIL_THANKS_NEW_ORDER'); ?><br>
<?php echo sprintf(JText::_('COM_TIENDA_EMAIL_CHECK'),$this->link); ?><br>
<?php echo JText::_('COM_TIENDA_EMAIL_RECEIPT_FOLLOWS'); ?><br>
<?php // if you want to over ride this, you do it in orders/email.php override
 echo $this->order; ?>