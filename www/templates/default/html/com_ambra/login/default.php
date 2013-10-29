<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php $user = JFactory::getUser(); ?>
<?php $row = @$this->row; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Login" ); ?></span>
</div>

<?php if (JPluginHelper::isEnabled('authentication', 'openid')) {
        $lang = JFactory::getLanguage();
        $lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
        $langScript =   'var JLanguage = {};'.
                        ' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
                        ' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
                        ' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
                        ' var comlogin = 1;';
        $document = JFactory::getDocument();
        $document->addScriptDeclaration( $langScript );
        JHTML::_('script', 'openid.js');        
} ?>

<div id="ambra_login" class="login default">
<?php
if(Ambra::getInstance()->get('hybridauth_int', 0)) {


        $this->setLayout( 'default_hybridauth' ); echo $this->loadTemplate();
  } ?>


    <form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="com-login" id="com-form-login">
    
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeDisplayLoginForm)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplayLoginForm; ?>
        </div>
    <?php endif; ?>
       
    <div id="login_maincolumn<?php if (empty($this->onDisplayLoginFormRightColumn)) { echo "_full"; } ?>">
    
        <?php if (!empty($this->loginArticle)) : ?>
            <div id='login_article'>
            <?php echo $this->loginArticle; ?>
            </div>
        <?php endif; ?>    
    
        <div id="login-message">
            <?php echo JText::_( "LOGIN_MESSAGE" ); ?>
        </div>
    
        <div id="com-form-login-username">
            <label for="username"><?php echo JText::_('Username'); ?></label>
            <input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
        </div>

        <div id="com-form-login-password">
            <label for="passwd"><?php echo JText::_('Password'); ?></label>
            <input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" />
        </div>

        <?php if(JPluginHelper::isEnabled('system', 'remember')) { ?>
             <label class="checkbox">
                    <input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" />
<?php echo JText::_('Remember me') ?>
  </label>

        <?php } ?>

        <div class="reset"></div>
        
        <?php if (!empty($this->onAfterDisplayLoginForm)) : ?>
            <div id='onAfterDisplay_wrapper'>
            <?php echo $this->onAfterDisplayLoginForm; ?>
            </div>
        <?php endif; ?>

        <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('LOGIN') ?>" />
        
        <ul>
            <li>
                <a href="<?php echo DSCRoute::_( 'index.php?option=com_ambra&view=reset' ); ?>">
                <?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
            </li>
            <li>
                <a href="<?php echo DSCRoute::_( 'index.php?option=com_ambra&view=remind' ); ?>">
                <?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
            </li>
            <?php
            $allowUserRegistration = Ambra::getInstance()->get( 'allowUserRegistration' , '1' );
            if ($allowUserRegistration) { ?>
            <li>
                <a href="<?php echo DSCRoute::_( 'index.php?option=com_ambra&view=registration' ); ?>">
                    <?php echo JText::_('REGISTER'); ?></a>
            </li>
            <?php } ?>
        </ul>
    </div>
    
    <?php if (!empty($this->onDisplayLoginFormRightColumn)) : ?>
    <div id="login_rightcolumn">
        <div id='onDisplayRightColumn_wrapper'>
        <?php echo $this->onDisplayLoginFormRightColumn; ?>
        </div>
    </div>
    <?php endif; ?>

    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="login" />
    <input type="hidden" name="task" id="task" value="login" />
    <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    
</div>
