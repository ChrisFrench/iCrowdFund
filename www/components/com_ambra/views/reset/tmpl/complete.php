<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Reset Your Password" ); ?></span>
</div>

<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">

    <p><?php echo JText::_('RESET_PASSWORD_COMPLETE_DESCRIPTION'); ?></p>
    
    <div>
	<label for="password1" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TEXT'); ?>"><?php echo JText::_('Password'); ?>:</label>
	<input id="password1" name="password1" type="password" class="required validate-password" />
    </div>
    
    <div>  
	<label for="password2" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TEXT'); ?>"><?php echo JText::_('Verify Password'); ?>:</label>
	<input id="password2" name="password2" type="password" class="required validate-password" />
    </div>
    
	<button  class="btn btn-primary" type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>
	
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="reset" />
    <input type="hidden" name="task" id="task" value="completereset" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
