<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $url = JRoute::_( "index.php?option=com_tienda&view=opc", false ); ?>
<form action="<?php echo JRoute::_( 'index.php', true, Tienda::getInstance()->get('usesecure', '0') ); ?>" method="post" name="login" id="form-login">

    <ul class="unstyled">
        <li>
            <label>
                <?php echo JText::_('COM_TIENDA_USERNAME'); ?> <span class>*</span>
            </label>
            <input id="tienda-username" type="text" name="username" size="18" alt="username" />
        </li>
        <li class="passwrd">
            <label>
                <?php echo JText::_('COM_TIENDA_PASSWORD'); ?><span>*</span>
            </label>
            <input id="tienda-password" type="password" name="passwd" size="18" alt="password" />
        </li>
        <?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
        <li class="rembr">
            <label class="checkbox">
                <input id="tienda-remember" type="checkbox" name="remember" value="yes" />
                <?php echo JText::_('COM_TIENDA_REMEMBER_ME'); ?>                
            </label>            
        </li>
        <?php } ?>
    </ul>

         <input type="submit" name="submit" class="btn btn-primary" value="<?php echo JText::_('COM_TIENDA_LOGIN') ?>" />
   <ul class="unstyled frgt">
        <li> 
            <a href="<?php echo JRoute::_('index.php?option=com_ambra&view=reset'); ?>"> 
                <?php echo JText::_('COM_TIENDA_FORGOT_YOUR_PASSWORD'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_ambra&view=remind'); ?>"> 
                <?php echo JText::_('COM_TIENDA_FORGOT_YOUR_USERNAME'); ?>
            </a>
        </li>
    </ul>

    <div>
        <input type="hidden" name="option" value="com_ambra" /> 
        <input type="hidden" name="task" value="login" />
        <input type="hidden" name="view" value="login" />  
        <input type="hidden" name="return" value="<?php echo base64_encode( $url ); ?>" />    
        <?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>