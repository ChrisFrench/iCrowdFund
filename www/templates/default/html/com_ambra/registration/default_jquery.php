<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php  DSC::loadjQuery(); 
 JHTML::_('script', 'ambra_registration_jquery.js', 'media/com_ambra/js/'); ?>
<?php $user = JFactory::getUser(); ?>
<?php $row = @$this->row; ?>
<?php $categories = @$this->categories; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Sign-up" ); ?></span>
</div>

<div id="ambra_registration" class="registration default">
    <?php $validationUrl = JRoute::_( "index.php?option=com_ambra&view=registration&format=json&task=validate", true, $this->params->get('usesecure') ); ?>
    <form name="ambra_registration_form" id="ambra_registration_form" action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure') ); ?>" onsubmit="ambraFormValidation( '<?php echo $validationUrl; ?>', 'message-container', this.task.value, this )" method="post" enctype="multipart/form-data" >
        <div id="formsnwrp">
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeDisplayRegistrationForm)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplayRegistrationForm; ?>
        </div>
    <?php endif; ?>
    
    <div id="registration_maincolumn<?php if (empty($this->onDisplayRegistrationFormRightColumn)) { echo "_full"; } ?>">
        <div class="form_item">
             <div class="form_key" style="width:100%;">
            <strong>New to iCrowdFund? </strong><br />Please fill out the following information to create an account.
        </div>
        </div>
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Full Name').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input name="name" id="name" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-name"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Username').' '.DSCGrid::required('*'); ?>
                  <a href="#" id="usernameTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Username must be at least 8 Characters and contain both letters and numbers" data-original-title="Username" ><i class="icon-white icon-info-sign"></i></a>
          </div>        
            <div class="form_input">
                <input name="username" id="username" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-username"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Email').' '. DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input  name="email" id="email" type="email" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-email"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Password').' '. DSCGrid::required('*'); ?>
                <a href="#" id="passwordTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Password must be at least 8 Characters and contain both letters and numbers" data-original-title="Password"><i class="icon-white icon-info-sign"></i></a>

            </div>        
            <div class="form_input">
                <input  name="password" id="password" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Verify Password').' '. DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input  name="password2" id="password2" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password2"></div>
        </div>
        
        <div class="reset"></div>
        
        <?php if (!empty($this->onAfterDisplayRegistrationForm)) : ?>
            <div id='onAfterDisplay_wrapper'>
            <?php echo $this->onAfterDisplayRegistrationForm; ?>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php if (!empty($this->onDisplayRegistrationFormRightColumn)) : ?>
    <div id="registration_rightcolumn">
        <div id='onDisplayRightColumn_wrapper'>
        <?php echo $this->onDisplayRegistrationFormRightColumn; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="form_item">
            <div class="form_key">
             
            </div>        
            <div class="form_input">
                 <button id="register"  class="btn btn-primary"><?php echo JText::_('Sign Up'); ?></button><br>
                 <span class="dsc-required">* All Fields Required</span><br />
                 By clicking Sign Up, you agree to our <br /><a href="/terms-conditions">Terms and Conditions</a>.
            </div>
            <div class="form_message" id="message-password2"></div>
        </div>
   
    <?php if ($this->profiles > '1') : ?>
        <a href="index.php?option=com_ambra&view=registration&task=selectprofile"><?php echo JText::_( "Select a Different Profile Type" ); ?></a>
    <?php endif; ?>
   
    
    <input type="hidden" name="profile_id" value="<?php echo $this->profile_id; ?>" />
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="registration" />
    <input type="hidden" name="task" id="task" value="save" />
    <input type="hidden" name="return" value="<?php echo @$this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
		<br clear="all" />
	</div>
    </form>
    <script>
    jQuery('#usernameTIP').popover();
    jQuery('#passwordTIP').popover();
  
</script>
</div>