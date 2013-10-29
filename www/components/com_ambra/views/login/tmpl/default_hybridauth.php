<?php
//TODO this should maybe be handled by something in HybridAuth like maybe a helper + template? kinda like Tienda getBuyButton system

if ( !class_exists('HybridAuth') ) {
    JLoader::register( "HybridAuth", JPATH_ADMINISTRATOR."/components/com_hybridauth/defines.php" );
}
HybridAuth::load( 'HybridAuthModelConfig', 'models.config' );
$model = new HybridAuthModelConfig();
$config = $model->getHAConfigArray();
$lang = JFactory::getLanguage();
$lang->load('com_hybridauth');

?>

<h3><?php echo JText::_( "COM_HYBRIDAUTH_LOGIN_WITH_FOLLOWING" ); ?>:</h3>

<ul id="sso_providers" class="flat">
    <?php if (!empty($config["providers"]["Facebook"]["enabled"])) { ?>
    <li>
        <a href="<?php echo JRoute::_( "index.php?option=com_hybridauth&view=login&task=login&type=facebook" ); ?>">
            <img src="<?php echo HybridAuth::getURL('images') . 'button_facebook_connect.png'; ?>" />
        </a>
    </li>
    <?php } ?>
    
    <?php if (!empty($config["providers"]["Twitter"]["enabled"])) { ?>
    <li>
        <a href="<?php echo JRoute::_( "index.php?option=com_hybridauth&view=login&task=login&type=twitter" ); ?>">
            <img src="<?php echo HybridAuth::getURL('images') . 'button_twitter.png'; ?>" />
        </a>
    </li>
    <?php } ?>
    
    <?php if (!empty($config["providers"]["Google"]["enabled"])) { ?>
    <li>
        <a href="<?php echo JRoute::_( "index.php?option=com_hybridauth&view=login&task=login&type=google" ); ?>">
            <img src="<?php echo HybridAuth::getURL('images') . 'button_google_login.png'; ?>" />
        </a>
    </li>
    <?php } ?>
    
    <?php if (!empty($config["providers"]["OpenID"]["enabled"])) { ?>
    <li>
        <a href="javascript:void(0);" onclick="hybridauthShowHideDiv('openid_form');">
            <img src="<?php echo HybridAuth::getURL('images') . 'button_openid.png'; ?>" />
        </a>
        
        <div id="openid_form" style="display: none;">
            <form method="post" action="<?php echo JRoute::_( "index.php?option=com_hybridauth&view=login&task=login&type=openid" ); ?>">
                <?php echo JText::_( "COM_HYBRIDAUTH_OPENID_URL" ); ?>
                <div>
                    <input type="text" size="50" value="" id="openid_url" name="openid_url" />
                    <input class="button" type="submit" value="<?php echo JText::_( "COM_HYBRIDAUTH_LOGIN" ); ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form> 
        </div>
    </li>
    <?php } ?>
    
</ul>