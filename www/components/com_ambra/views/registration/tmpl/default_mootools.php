<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.mootools');
        JHTML::_('behavior.framework', true);
?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php JHTML::_('script', 'ambra_registration.js', 'media/com_ambra/js/'); ?>
<?php $user = JFactory::getUser(); ?>
<?php $row = @$this->row; ?>
<?php $categories = @$this->categories; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Registration" ); ?></span>
</div>

<div id="ambra_registration" class="registration default">
    <?php $validationUrl = JRoute::_( "index.php?option=com_ambra&view=registration&format=json&task=validate", true, $this->params->get('usesecure') ); ?>
    <form name="ambra_registration_form" id="ambra_registration_form" action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure') ); ?>" onsubmit="ambraFormValidation( '<?php echo $validationUrl; ?>', 'message-container', this.task.value, this )" method="post" enctype="multipart/form-data" >
        
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeDisplayRegistrationForm)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplayRegistrationForm; ?>
        </div>
    <?php endif; ?>
    
    <div id="registration_maincolumn<?php if (empty($this->onDisplayRegistrationFormRightColumn)) { echo "_full"; } ?>">
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Name').' '.DSCGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input name="name" id="name" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-name"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Username').' '.DSCGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckUsername( 'message-username', document.ambra_registration_form );" name="username" id="username" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-username"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Email').' '.DSCGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckEmail( 'message-email', document.ambra_registration_form );" name="email" id="email" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-email"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Password').' '.DSCGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword( 'message-password', document.ambra_registration_form ); ambraCheckPassword2( 'message-password2', document.ambra_registration_form );" name="password" id="password" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Verify Password').' '.DSCGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword2( 'message-password2', document.ambra_registration_form );" name="password2" id="password2" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password2"></div>
        </div>
        
        <div class="reset"></div>
        
        <div class="profile_header">
            <span><?php echo JText::_( "Avatar" ); ?></span>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_( "Upload an Avatar" ); ?>
            </div>        
            <div class="form_input">
                <input type="file" name="avatar" />
            </div>
        </div>
        
        <div class="reset"></div>
        
        
        <?php foreach ($categories as $category) : ?>
            <div class="profile_header">
                <span><?php echo JText::_( $category->category_name ); ?></span>
            </div>
            
            <?php foreach ($category->fields as $field) : ?>
                <div class="form_item">
                    <div class="form_key">
                        <?php echo JText::_( $field->field_name ); if (!empty($field->required)) { echo ' '.DSCGrid::required(); } ?>
                    </div>        
                    <div class="form_input">
                        <?php echo Ambra::getClass( "AmbraField", 'library.field' )->display( $field, 'userdata', '' ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
                
            <div class="reset"></div>            
        <?php endforeach; ?>

        <?php if ( Ambra::getClass( "AmbraHelperAmigos", 'helpers.amigos' )->isInstalled() && AmbraConfig::getInstance()->get( 'amigos_registration' ) ) : ?>
            <div class="reset"></div>
            
            <div class="profile_header">
                <span><?php echo JText::_( "Affiliate Account" ); ?></span>
            </div>
    
            <div class="form_item">
                <div class="form_key">
                    <?php echo JText::_( "Automatically Register as an Affiliate" ); ?>
                </div>        
                <div class="form_input">
                    <?php echo JHTML::_('select.booleanlist', 'amigos_autoregister', '', '1' ); ?>
                </div>
            </div>            
        <?php endif; ?>
        
        <?php if ( Ambra::getClass( "AmbraHelperPhplist", 'helpers.phplist' )->isInstalled() && AmbraConfig::getInstance()->get( 'phplist_registration' ) ) : ?>
        
            <?php if ($newsletters = Ambra::getClass( "AmbraHelperPhplist", 'helpers.phplist' )->getNewsletters() ) : ?>
            <div class="reset"></div>
            
            <div class="profile_header">
                <span><?php echo JText::_( "Sign Up for Our Newsletters" ); ?></span>
            </div>

            <?php
            foreach ($newsletters as $newsletter) :
                ?>
                <div class="form_item">
                    <div class="form_key">
                        <?php echo $newsletter->name; ?>
                    </div>        
                    <div class="form_input">
                        <input type="checkbox" name="phplist_newsletters[]" value="<?php echo $newsletter->id; ?>" /> 
                    </div>
                </div>
                <?php
            endforeach;
            ?>
            
            <?php endif; ?>            
        <?php endif; ?>
        
        <?php if ( Ambra::getClass( "AmbraHelperAllchimp", 'helpers.allchimp' )->isInstalled() && AmbraConfig::getInstance()->get( 'allchimp_registration' ) ) : ?>
        
            <?php if ($newsletters = Ambra::getClass( "AmbraHelperAllchimp", 'helpers.allchimp' )->getNewsletters() ) : ?>
            <div class="reset"></div>
            
            <div class="profile_header">
                <span><?php echo JText::_( "Sign Up for Our Newsletters" ); ?></span>
            </div>

            <?php
            foreach ($newsletters as $newsletter) :
                ?>
                <div class="form_item">
                    <div class="form_key">
                        <?php echo $newsletter->mail_list_name; ?>
                    </div>        
                    <div class="form_input">
                        <input type="checkbox" name="allchimp_newsletters[]" value="<?php echo $newsletter->mail_list_index; ?>" /> 
                    </div>
                </div>
                <?php
            endforeach;
            ?>
            
            <?php endif; ?>            
        <?php endif; ?>
        
        <?php if ( Ambra::getClass( "AmbraHelperCampaignMonitor", 'helpers.campaignmonitor' )->isInstalled() && AmbraConfig::getInstance()->get( 'campaignmonitor_registration' ) ) : ?>
        
            <?php if ($newsletters = Ambra::getClass( "AmbraHelperCampaignMonitor", 'helpers.campaignmonitor' )->getNewsletters() ) : ?>
            <div class="reset"></div>
            
            <div class="profile_header">
                <span><?php echo JText::_( "Sign Up for Our Newsletters" ); ?></span>
            </div>

            <?php
            foreach ($newsletters as $newsletter) :
                ?>
                <div class="form_item">
                    <div class="form_key">
                        <?php echo JText::_( "Click Here to Subscribe" ); ?>
                    </div>        
                    <div class="form_input">
                        <input type="checkbox" name="campaignmonitor_newsletters[]" value="<?php echo $newsletter; ?>" /> 
                    </div>
                </div>
                <?php
            endforeach;
            ?>
            
            <?php endif; ?>            
        <?php endif; ?>
        
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
    
    <input onclick="Dsc.submitForm('save', document.ambra_registration_form);" value="<?php echo JText::_('Register'); ?>" type="button" />
    <?php if ($this->profiles > '1') : ?>
        <a href="index.php?option=com_ambra&view=registration&task=selectprofile"><?php echo JText::_( "Select a Different Profile Type" ); ?></a>
        <?php echo JText::_( "or" ); ?>
    <?php endif; ?>
    <a href="index.php"><?php echo JText::_( "Cancel" ); ?></a>
    
    <input type="hidden" name="profile_id" value="<?php echo $this->profile_id; ?>" />
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="registration" />
    <input type="hidden" name="task" id="task" value="" />
    <input type="hidden" name="return" value="<?php echo @$this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    
</div>