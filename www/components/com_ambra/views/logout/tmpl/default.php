<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $user = JFactory::getUser(); ?>
<?php $row = @$this->row; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Logout" ); ?></span>
</div>
    <?php if ($menu = AmbraMenu::getInstance()) { $menu->display(); } ?>
 
    
<div id="ambra_logout" class="logout default">

    <form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="com-logout" id="com-form-logout">
    
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeDisplayLogoutForm)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplayLogoutForm; ?>
        </div>
    <?php endif; ?>
    
    <div id="logout_maincolumn<?php if (empty($this->onDisplayLogoutFormRightColumn)) { echo "_full"; } ?>">

        <?php if (!empty($this->logoutArticle)) : ?>
            <div id='logout_article'>
            <?php echo $this->logoutArticle; ?>
            </div>
        <?php endif; ?>    
    
        <div class="reset"></div>
    
        <div id="logout-message">
            <?php echo JText::_( "LOGOUT MESSAGE" ); ?>
        </div>

        <div class="reset"></div>
        
        <?php if (!empty($this->onAfterDisplayLogoutForm)) : ?>
            <div id='onAfterDisplay_wrapper'>
            <?php echo $this->onAfterDisplayLogoutForm; ?>
            </div>
        <?php endif; ?>

        <input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGOUT') ?>" />
        
    </div>
    
    <?php if (!empty($this->onDisplayLogoutFormRightColumn)) : ?>
    <div id="logout_rightcolumn">
        <div id='onDisplayRightColumn_wrapper'>
        <?php echo $this->onDisplayLogoutFormRightColumn; ?>
        </div>
    </div>
    <?php endif; ?>

    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="logout" />
    <input type="hidden" name="task" id="task" value="logout" />
    <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    
</div>