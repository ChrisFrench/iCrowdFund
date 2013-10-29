<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Forgotten Username" ); ?></span>
</div>

<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">

    <p><?php echo JText::_('REMIND_USERNAME_DESCRIPTION'); ?></p>

    <label for="email" class="hasTip" title="<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TEXT'); ?>"><?php echo JText::_('Email Address'); ?>:</label>
    <input id="email" name="email" type="text" class="required validate-email" />

	<button  class="btn btn-primary" type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>

    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="remind" />
    <input type="hidden" name="task" id="task" value="username" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
