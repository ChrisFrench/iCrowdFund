<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Confirm Your Email Token" ); ?></span>
</div>

<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">

    <p><?php echo JText::_('RESET_PASSWORD_CONFIRM_DESCRIPTION'); ?></p>
    
    <label for="token" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('Token'); ?>:</label>
    <input id="token" name="token" type="text" class="required" size="36" />

	<button class="btn btn-primary" type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>
	
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="reset" />
    <input type="hidden" name="task" id="task" value="confirmreset" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
