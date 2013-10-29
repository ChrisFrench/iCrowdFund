
<div>

<?php echo sprintf(JText::_('COM_TIENDA_EMAIL_DEAR'),$this->user_name); ?>
<?php echo JText::_('COM_TIENDA_EMAIL_THANKS_NEW_ORDER'); ?>
<?php echo sprintf(JText::_('COM_TIENDA_EMAIL_CHECK'),$this->link); ?>
<?php echo JText::_('COM_TIENDA_EMAIL_RECEIPT_FOLLOWS'); ?>




</div>
<?php echo $this->body; ?>

 // Status Change
                    $return->subject = JText::_('COM_TIENDA_EMAIL_ORDER_STATUS_CHANGE');
                    $last_history = count($data->orderhistory) - 1;
                    $view->set('last_history', $last_history);
                    $text = sprintf(JText::_('COM_TIENDA_EMAIL_DEAR'),$user_name).",\n\n";
                    $text .= sprintf( JText::_('COM_TIENDA_EMAIL_ORDER_UPDATED'), $data->order_id );
                    $text .= JText::_('COM_TIENDA_EMAIL_NEW_STATUS')." ".$data->orderhistory[$last_history]->order_state_name."\n\n";
                    if (!empty($data->orderhistory[$last_history]->comments))
                    {
                        $text .= sprintf( JText::_('COM_TIENDA_EMAIL_ADDITIONAL_COMMENTS'), $data->orderhistory[$last_history]->comments );
                    }
                    $text .= sprintf(JText::_('COM_TIENDA_EMAIL_CHECK'),$link)."\n\n";

                    if ($this->use_html)
                    {
                        $text = nl2br( $text );
                    }